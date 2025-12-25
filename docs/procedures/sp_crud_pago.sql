USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_pago]    Script Date: 21/12/2025 18:59:55 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_pago] (
 @i_operacion char(1),
 @i_tipo char(1) = null,
 @i_pago_id tinyint = null,
 @i_pagc_id int = null,
 @i_cta_id int = null,
 @i_usu_id int = null,
 @i_suc_id int = null,
 @i_cli_id int = null,
 @i_salida_id int = null,
 @i_saldo_recibo decimal(14,2) = null,
 @i_pagc_monto decimal(14,2) = null,
 @i_pago_nombre varchar(75) = null,
 @i_pagc_obs varchar(75) = null,
 @i_pago_estado tinyint = 1,
 @i_fecha_desde varchar(75) = null,
 @i_fecha_hasta varchar(75) = null
)
as
declare 
@w_fecha varchar(75),
@w_pagc_id int,
@w_cta_obs varchar(150),
@w_monto_trn decimal(14,2),
@w_cta_id int,
@w_prox_recibo int,
@w_salida_id int,
@w_venta_id int,
@w_saldo_recibo decimal(14,2),
@w_val_total decimal(14,2),
@w_val_total_w decimal(14,2),
@w_estado_recibo char(1),
@w_saldo_pago decimal(14,2),
@w_saldo_cuenta_pendiente decimal(14,2),
@w_abonado_cuenta_pendiente decimal(14,2)

begin
	set nocount on
	select @w_prox_recibo = -1

	if @i_operacion = 'C'
	begin
		insert into tm_tipo_pago 
		( pago_nombre, pago_fecha_crea, pago_estado)
		values
		( @i_pago_nombre, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		SELECT @w_fecha = GETDATE()
		select @w_cta_obs = 'Upd Pago # ' + CONVERT(varchar, @i_pagc_id)

		-- SE OBTIENE LOS DATOS PARA LA MODIFICACIÓN
		select 
			@w_monto_trn = pagc_monto,
			@w_cta_id = cta_id
		from tm_pago_cuenta
		where pagc_id = @i_pagc_id

		-- SE MODIFICA EL VALOR DE LA CUENTA

		update tm_cuenta_cliente 
		set cta_monto = (cta_monto + @w_monto_trn) - @i_pagc_monto,
		cta_fecha_upd = @w_fecha,
		cta_obs = @w_cta_obs
		where cta_id = @w_cta_id

		update tm_movimiento_cuenta
		set movc_valor = @i_pagc_monto,
		movc_nuevo_val = movc_val_actual - @i_pagc_monto,
		movc_obs = @i_pagc_obs,
		movc_fecha = @w_fecha
		where salida_id = @i_pagc_id

		update tm_pago_cuenta 
		set pagc_monto =  @i_pagc_monto,
		pago_id = @i_pago_id,
		pagc_fecha = @w_fecha,
		pagc_obs = @i_pagc_obs
		where pagc_id = @i_pagc_id
	end

	if @i_operacion = 'D'
	begin
		
		SELECT @w_fecha = GETDATE()

		-- SE OBTIENE LOS DATOS PARA LA MODIFICACIÓN
		select 
			@w_monto_trn = pagc_monto,
			@w_cta_id = cta_id,
			@w_salida_id = salida_id,
			@w_venta_id = ven_id
		from tm_pago_cuenta
		where pagc_id = @i_pagc_id

		-- SE MODIFICA EL VALOR DE LA CUENTA
		update tm_cuenta_cliente 
		set cta_monto = cta_monto + @w_monto_trn,
		cta_fecha_upd = @w_fecha,
		cta_obs = 'Reversa pago cuenta'
		where cta_id = @w_cta_id

		-- SE VALIDA EL ESTADO DEL PAGO DEL RECIBO
		if @w_salida_id is not null
		begin
			select @w_estado_recibo = salida_vpagado from tm_salida_lote where salida_id = @w_salida_id and salida_estado = 1

			if @w_estado_recibo = 'C'
				set @w_estado_recibo = 'N'

			-- SE MODIFICA EL ESTADO DEL RECIBO DE SALIDA
			update tm_salida_lote
			set salida_vpagado = @w_estado_recibo
			where salida_id = @w_salida_id
		end

		-- SE VALIDA EL ESTADO DEL PAGO DEL RECIBO
		if @w_venta_id is not null
		begin
			select @w_estado_recibo = rvc_est_cta from tm_registro_vencred where ven_id = @w_venta_id and rvc_estado = 1

			if @w_estado_recibo = 'C'
				set @w_estado_recibo = 'P'

			-- SE MODIFICA EL ESTADO DEL RECIBO DE SALIDA
			update tm_registro_vencred
			set rvc_est_cta = @w_estado_recibo
			where ven_id = @w_venta_id
		end

		-- SE ELIMINA EL MOVIMIENTO DE LA CUENTA
		delete from tm_movimiento_cuenta
		where pagc_id = @i_pagc_id 
		and movc_tipo = '-'

		update tm_pago_cuenta set 
			pagc_estado = 0
		where pagc_id = @i_pagc_id

	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			select * from tm_tipo_pago
			where pago_estado = 1
		end
		if @i_tipo = 'S'
		begin
			select 
			pago_id,
			pago_nombre,
			CONVERT(varchar, pago_fecha_crea , 103) as pago_fecha_crea,
			pago_estado
			from tm_tipo_pago
			where pago_estado = 1
		end
		if @i_tipo = 'I'
		begin
			select * from tm_tipo_pago
			where pago_estado = 1
			and pago_id = @i_pago_id
		end

		if @i_tipo = 'C'
		begin
			select pc.*, cl.cli_nombre from tm_pago_cuenta pc
			inner join tm_cuenta_cliente cc on cc.cta_id = pc.cta_id
			inner join tm_cliente cl on cl.cli_id = cc.cli_id
			where pc.pagc_id = @i_pago_id
			and pc.pagc_estado = 1
		end

	end

	if @i_operacion = 'P'
	begin
		select @w_fecha = GETDATE(),
			@w_saldo_pago = 0
			
		if @i_salida_id > 0
		begin
			-- SE OBTIENE EL SALDO COMPLETO DE SALIDA
			select @w_saldo_recibo = salida_total, @w_estado_recibo = salida_vpagado from tm_salida_lote where salida_id = @i_salida_id and salida_estado = 1
		
			-- SE VALIDA SI HAY SALDO DE PAGO
			if @w_estado_recibo = 'A'
				select @w_saldo_pago = SUM(pagc_monto) from tm_pago_cuenta where salida_id = @i_salida_id and pagc_estado = 1

			-- SE CALCULAN LOS VALORES A RESTAR
			select @w_saldo_recibo = @w_saldo_recibo - isnull(@w_saldo_pago, 0)

			select @w_val_total = @i_pagc_monto - @w_saldo_recibo

			if @w_val_total = 0 or @w_val_total > 0
			begin
				select @w_estado_recibo = 'C'
			end
			else if @w_val_total < 0
			begin
				select @w_estado_recibo = 'A',
					@w_saldo_recibo = @i_pagc_monto -- @w_val_total * (-1)
			end

			print 'valores: @w_val_total:' + convert(varchar, @w_val_total)  + ', @w_saldo_recibo : ' + convert(varchar,  @w_saldo_recibo )
			+ ', @i_salida_id:' + convert(varchar,  @i_salida_id )+ ' , @i_pagc_monto : '+ convert(varchar,  @i_pagc_monto) + ' @w_estado_recibo:' + convert(varchar, @w_estado_recibo)

			if LEN(@i_pagc_obs) = 0
				set @i_pagc_obs = 'Sin observación'

			insert into tm_pago_cuenta 
			(cta_id, 	pago_id,	pagc_obs,	 pagc_fecha,	pagc_estado,	usu_id,		pagc_monto,
			salida_id)
			values 
			(@i_cta_id, @i_pago_id, @i_pagc_obs, @w_fecha,		1,				@i_usu_id,  @w_saldo_recibo,
			@i_salida_id)

			print 'Se inserta en la tm_pago_cuenta'

			select @w_pagc_id = SCOPE_IDENTITY()

			if @i_pagc_obs = 'Sin observación'
			begin 
				select @w_cta_obs = 'Pago # ' + CONVERT(varchar, @w_pagc_id)
			end
			else
			begin
				select @w_cta_obs = @i_pagc_obs
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
			@i_salida_id = @i_salida_id,
			@i_pagc_id = @w_pagc_id

			print 'Se actualiza el campo de salida'
			print ' @i_salida_id : '+ convert(varchar,  @i_salida_id)
			print ' @w_estado_recibo : '+ convert(varchar,  @w_estado_recibo)

			update tm_salida_lote
			set salida_vpagado = @w_estado_recibo
			where salida_id = @i_salida_id

			print 'Validación @w_val_total : '+ convert(varchar,  @w_val_total)

            if @w_val_total > 0
            begin
                select top 1 @w_prox_recibo = salida_id 
                from tm_salida_lote 
                where salida_id > @i_salida_id and cli_id = @i_cli_id and salida_vpagado not in ('C') and salida_estado = 1 order by salida_id
            end

            -- SE RETORNAN VALORES PARA EL CALCULO DESDE PHP
            select 
				@w_val_total as 'total', 
                @w_prox_recibo as 'prox_recibo'

		end
		else if @i_salida_id = 0
		begin

			print 'Validación para cuenta pendiente'
			-- Se obtienen los valores totales de cuenta pendiente
			select @w_saldo_cuenta_pendiente = SUM(movc_valor) 
			from tm_movimiento_cuenta where cta_id =  @i_cta_id and salida_id = 0 and movc_estado = 1 and movc_tipo = '+'

			select @w_abonado_cuenta_pendiente = SUM(movc_valor) 
			from tm_movimiento_cuenta where cta_id =  @i_cta_id and salida_id = 0 and movc_estado = 1 and movc_tipo = '-'

			set @w_saldo_cuenta_pendiente = @w_saldo_cuenta_pendiente - @w_abonado_cuenta_pendiente

			set @w_saldo_pago = @w_saldo_cuenta_pendiente - @i_pagc_monto

			print '************** Valores Calculadors ************************'
			print 'Validación @i_cta_id : '+ convert(varchar,  @i_cta_id)
			print 'Validación @w_saldo_cuenta_pendiente : '+ convert(varchar,  @w_saldo_cuenta_pendiente)
			print 'Validación @w_abonado_cuenta_pendiente : '+ convert(varchar,  @w_abonado_cuenta_pendiente)
			print 'Validación @w_saldo_pago : '+ convert(varchar,  @w_saldo_pago)

			if @w_saldo_pago < 0
			begin
				set @i_pagc_monto = @w_saldo_cuenta_pendiente
				set @w_val_total = (@w_saldo_pago * (-1))

				select top 1
					@w_prox_recibo = sl.salida_id
				from tm_salida_lote sl
				inner join tm_movimiento_cuenta mc on mc.salida_id = sl.salida_id
				where salida_vpagado not in ('C')
				and cli_id = @i_cli_id
				and salida_estado = 1
				and mc.movc_estado = 1
				and movc_tipo = '+'
				order by sl.salida_id

			end

			print 'Validación @i_cli_id : '+ convert(varchar,  @i_cli_id)
			print 'Validación @w_val_total : '+ convert(varchar,  @w_val_total)
			print 'Validación @w_prox_recibo : '+ convert(varchar,  @w_prox_recibo)


			insert into tm_pago_cuenta 
			(cta_id, 	pago_id,	pagc_obs,	 pagc_fecha,	pagc_estado,	usu_id,		pagc_monto,
			salida_id)
			values 
			(@i_cta_id, @i_pago_id, @i_pagc_obs, @w_fecha,		1,				@i_usu_id,  @i_pagc_monto,
			@i_salida_id)

			select @w_pagc_id = SCOPE_IDENTITY()

			if @i_pagc_obs = 'Sin observación'
			begin 
				select @w_cta_obs = 'Pago # ' + CONVERT(varchar, @w_pagc_id)
			end
			else
			begin
				select @w_cta_obs = @i_pagc_obs
			end

			exec sp_crud_cuenta_cli  
			@i_operacion = 'U',
			@i_cta_id = @i_cta_id, 
			@i_suc_id = @i_suc_id,
			@i_movc_tipo = '-',
			@i_cta_monto = @i_pagc_monto,
			@i_cta_fecha = @w_fecha,
			@i_usu_id = @i_usu_id,
			@i_cta_obs = @w_cta_obs,
			@i_salida_id = @i_salida_id,
			@i_pagc_id = @w_pagc_id

			-- SE RETORNAN VALORES PARA EL CALCULO DESDE PHP
            select 
				@w_val_total as 'total', 
                @w_prox_recibo as 'prox_recibo'

		end
	end

	if @i_operacion = 'L'
	begin
		SELECT 
			pc.cta_id,
			cl.cli_nombre,
			p.pago_nombre,
			pagc_monto,
			pagc_obs,
			pagc_fecha,
			concat(u.usu_nombre, ' ' , u.usu_apellido) as usu_nombre,
			pc.pagc_id,
			salida_id,
			ven_id
		from tm_pago_cuenta pc
		inner join tm_tipo_pago p on p.pago_id = pc.pago_id
		inner join tm_cuenta_cliente cc on cc.cta_id = pc.cta_id
		inner join tm_cliente cl on cl.cli_id = cc.cli_id
		inner join tm_usuario u on u.usu_id = pc.usu_id
		where cc.cli_id = isnull(@i_cli_id, cc.cli_id)
		and p.pago_id = isnull(@i_pago_id,p.pago_id)
		and CAST(pc.pagc_fecha as date) between @i_fecha_desde and @i_fecha_hasta
		and cl.cli_estado = 1
		and pc.pagc_estado = 1
		and cc.suc_id = @i_suc_id
		order by pc.pagc_fecha desc

	end
	
	return 0
end
