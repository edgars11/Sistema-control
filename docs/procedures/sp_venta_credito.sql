USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_venta_credito]    Script Date: 28/7/2025 22:20:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_venta_credito] (
 @i_operacion char(1),
 @i_tipo char(1) = null,
 @i_rvc_id tinyint = null,
 @i_pago_id int = null,
 @i_cta_id int = null,
 @i_usu_id int = null,
 @i_suc_id int = null,
 @i_cli_id int = null,
 @i_ven_id int = null,
 @i_fecha_ini varchar(20) = null,
 @i_fecha_hasta varchar(20) = null,
 @i_monto decimal(14,2) = null,
 @i_pago_obs varchar(75) = null
)
as
set nocount on
declare 
@w_fecha varchar(75),
@w_pagc_id int,
@w_cta_obs varchar(150),
@w_abono_ven decimal(14,2),
@w_monto_ven decimal(14,2),
@w_cta_id int,
@w_prox_recibo int,
@w_saldo_recibo decimal(14,2),
@w_val_total decimal(14,2),
@w_val_total_pago decimal(14,2),
@w_estado_ven char(1),
@w_estado_ven_act char(1),
@w_saldo_pago decimal(14,2)


	select @w_prox_recibo = -1

	if @i_operacion = 'L'
	begin
		select 
			vc.rvc_id, 
			vc.ven_id, 
			vc.rvc_monto, 
			vc.rvc_abonado, 
			vc.rvc_est_cta 
		from tm_registro_vencred vc
		inner join tm_movimiento_cuenta mc on mc.ven_id = vc.ven_id
		where mc.cta_id= @i_cta_id
		and vc.rvc_estado = 1
		and vc.rvc_est_cta not in ('C')
		and mc.movc_tipo = '+'
		and mc.movc_estado = 1
		order by ven_id asc

	end
	
	if @i_operacion = 'P'
	begin
		select @w_fecha = GETDATE()

		select @w_monto_ven = rvc_monto, @w_abono_ven = rvc_abonado, @w_estado_ven_act = rvc_est_cta 
		from tm_registro_vencred where ven_id = @i_ven_id and rvc_estado = 1

		print '@w_monto_ven: ' + CONVERT(varchar, @w_monto_ven)
		print '@w_abono_ven: ' + CONVERT(varchar, @w_abono_ven)

		if @w_estado_ven_act = 'A'
		begin
			set @w_val_total = @w_monto_ven - @w_abono_ven
		end
		else 
		begin
			set @w_val_total = @w_monto_ven
		end

		print '@w_val_total: ' + CONVERT(varchar, @w_val_total)

		set @w_val_total_pago = @i_monto - @w_val_total

		print '@w_val_total_pago: ' + CONVERT(varchar, @w_val_total_pago)

		if @w_val_total_pago = 0 or @w_val_total_pago > 0
		begin
			select @w_estado_ven = 'C',
				@w_saldo_recibo = @w_monto_ven
		end
		else if @w_val_total_pago < 0
		begin
			select @w_estado_ven = 'A',
				@w_saldo_recibo =  @i_monto -- @w_val_total * (-1)
		end

		print '@w_estado_ven_act: ' + CONVERT(varchar, @w_estado_ven_act)
		print '@w_estado_ven: ' + CONVERT(varchar, @w_estado_ven)
		print '@w_saldo_recibo: ' + CONVERT(varchar, @w_saldo_recibo)

		if (@w_estado_ven_act = 'P' or @w_estado_ven_act = 'A') and @w_estado_ven = 'C'
		begin
			update tm_registro_vencred
			set rvc_abonado = rvc_monto
				, rvc_est_cta = @w_estado_ven
			where ven_id = @i_ven_id
		end

		if @w_estado_ven_act = 'P' and @w_estado_ven = 'A'
		begin
			update tm_registro_vencred
			set rvc_abonado = @w_saldo_recibo
				, rvc_est_cta = @w_estado_ven
			where ven_id = @i_ven_id
		end

		if @w_estado_ven_act = 'A' and @w_estado_ven = 'A'
		begin
			update tm_registro_vencred
			set rvc_abonado = rvc_abonado + @w_saldo_recibo
				, rvc_est_cta = @w_estado_ven
			where ven_id = @i_ven_id
		end
		
		if LEN(@i_pago_obs) = 0
				set @i_pago_obs = 'Sin observación'

		insert into tm_pago_cuenta 
		(cta_id, 	pago_id,	pagc_obs,	 pagc_fecha,	pagc_estado,	usu_id,		pagc_monto,
		ven_id)
		values 
		(@i_cta_id, @i_pago_id, @i_pago_obs, @w_fecha,		1,				@i_usu_id,  @w_saldo_recibo,
		@i_ven_id)

		print 'Se inserta en la tm_pago_cuenta'

		select @w_pagc_id = SCOPE_IDENTITY()

		if @i_pago_obs = 'Sin observación'
		begin 
			select @w_cta_obs = 'Pago # ' + CONVERT(varchar, @w_pagc_id)
		end
		else
		begin
			select @w_cta_obs = @i_pago_obs
		end

		exec sp_crud_cuenta_cli  
		@i_operacion = 'U',
		@i_cta_id = @i_cta_id, 
		@i_suc_id = @i_suc_id,
		@i_movc_tipo = '-',
		@i_cta_monto = @w_saldo_recibo,
		@i_cta_fecha = @w_fecha,
		@i_usu_id = @i_usu_id,
		@i_cta_obs = @w_cta_obs,
		@i_ven_id = @i_ven_id,
		@i_pagc_id = @w_pagc_id

		-- SE VALIDA SI SOBRA SALDO SE BUSCA LA SIGUIENTE CUENTA PARA PAGAR
		if @w_val_total_pago > 0
		begin
			select top 1 @w_prox_recibo = mc.ven_id from tm_movimiento_cuenta mc
			inner join tm_registro_vencred vc on vc.ven_id = mc.ven_id
			where mc.cta_id = @i_cta_id
			and vc.rvc_est_cta not in('C')
			order by rvc_id
		end

		print 'Se retorna valores'
		print '@w_val_total_pago: ' + CONVERT(varchar, @w_val_total_pago)
		print '@w_prox_recibo: ' + CONVERT(varchar, @w_prox_recibo)

		-- SE RETORNAN VALORES PARA EL CALCULO DESDE PHP
        select 
			@w_val_total_pago as 'total', 
            @w_prox_recibo as 'prox_recibo'

	end

	if @i_operacion = 'I'
	begin
		select 
			cli_id,
			pago_id,
			ven_subtotal, 
			ven_total, 
			ven_fecha_crea, 
			ven_coment, 
			rvc_abonado, 
			rvc_est_cta 
		from tm_ventas v
		inner join tm_registro_vencred rv on rv.ven_id = v.ven_id
		where v.ven_id = @i_ven_id
		and v.ven_estado = 1
		and rv.rvc_estado = 1
	end

	if @i_operacion = 'T'
	begin
		if @i_tipo = 'A'
		begin
			select @w_monto_ven = SUM(v.ven_total)
			from tm_ventas v
			where v.ven_estado = 1
			and CAST(ven_fecha_crea as date) between @i_fecha_ini and @i_fecha_hasta
			and v.cli_id = @i_cli_id
			and v.suc_id = @i_suc_id

			set @w_val_total_pago = 0
			set @w_val_total  = 0
		end

		if @i_tipo = 'C'
		begin
			select 
				@w_val_total = SUM(rv.rvc_monto), 
				@w_val_total_pago = SUM(rv.rvc_abonado) 
			from tm_registro_vencred rv
			inner join tm_ventas v on v.ven_id = rv.ven_id
			where v.ven_estado = 1
			and CAST(ven_fecha_crea as date) between @i_fecha_ini and @i_fecha_hasta
			and v.cli_id = @i_cli_id
			and v.suc_id = @i_suc_id

			set @w_monto_ven = 0
		end
			
		select 
			@w_val_total as 'totalVentasCredito',
			@w_val_total_pago as  'totalAbonado',
			@w_monto_ven as 'totalVentas'

	end

return 0

