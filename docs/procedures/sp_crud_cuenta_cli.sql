USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_cuenta_cli]    Script Date: 22/7/2025 16:34:47 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_cuenta_cli] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_suc_id int = null,
 @i_usu_id int = null,
 @i_cli_id int = null,
 @i_cta_id int = null,
 @i_movc_tipo char(1) = null,
 @i_salida_id int = null,
 @i_pagc_id int = null,
 @i_ven_id int = null,
 @i_cta_monto decimal(14,2) = null,
 @i_cta_obs varchar(75) = null,
 @i_fecha_desde varchar(75) = null,
 @i_fecha_hasta varchar(75) = null,
 @i_cta_fecha varchar(70) = null,
 @o_cta_cli int = 0 out
)
as
declare
@w_cta_id int,
@w_cli_id int,
@w_val_actual decimal(14,2),
@w_val_nuevo decimal(14,2),
@w_val_total decimal(14,2),
@w_val_pedido_abo decimal(14,2),
@w_val_pedido decimal(14,2),
@w_val_ventas decimal(14,2),
@w_total_registros int,
@w_max_reg int,
@w_min_reg int

begin
	set nocount on

	if @i_operacion = 'C'
	begin
		insert into tm_cuenta_cliente 
		(cli_id,		cta_monto,		cta_estado,		cta_fecha_upd,	cta_obs,		suc_id)
		values
		(@i_cli_id, 	@i_cta_monto, 	1, 				@i_cta_fecha, 		@i_cta_obs, 	@i_suc_id)

		select @w_cta_id = SCOPE_IDENTITY()

		-- SE REGISTRA EL MOVIMIENO DE LA CUENTA

		insert into tm_movimiento_cuenta 
		(salida_id,		movc_tipo,	movc_val_actual,	movc_valor,			movc_nuevo_val,		cta_id,		movc_fecha,
		usu_id,		movc_obs, 		ven_id)
		values
		(@i_salida_id, 	'+', 		0, 					@i_cta_monto, 		@i_cta_monto, 		@w_cta_id, 	@i_cta_fecha,
		@i_usu_id,  @i_cta_obs,     @i_ven_id)

		select @o_cta_cli = @w_cta_id
	end

	if @i_operacion = 'U'
	begin
		select @w_val_actual = cta_monto from tm_cuenta_cliente where cta_id = @i_cta_id and suc_id = @i_suc_id and cta_estado = 1
		
		print ' @@w_val_actual : '+ convert(varchar, @w_val_actual)
		print ' @@i_movc_tipo : '+ convert(varchar, @i_movc_tipo)
		print ' @@i_cta_monto : '+ convert(varchar, @i_cta_monto)
		print ' @@i_cta_id : '+ convert(varchar, @i_cta_id)

		if @i_movc_tipo = '+'
		begin 
			select @w_val_total =  @w_val_actual + @i_cta_monto
		end

		if @i_movc_tipo = '-'
		begin 
			select @w_val_total = @w_val_actual - @i_cta_monto
		end

		update tm_cuenta_cliente 
		set cta_monto = @w_val_total, 
			cta_fecha_upd = @i_cta_fecha, 
			cta_obs = @i_cta_obs
		where cta_id = 	@i_cta_id
		and suc_id = @i_suc_id
		and cta_estado = 1

		-- SE REGISTRA EL MOVIMIENO DE LA CUENTA
		insert into tm_movimiento_cuenta 
		(salida_id,		movc_tipo,			movc_val_actual,	movc_valor,			movc_nuevo_val,		
		cta_id,			movc_fecha,			usu_id ,		movc_obs,	pagc_id,  	ven_id)
		values
		(@i_salida_id, 	@i_movc_tipo, 		@w_val_actual,		@i_cta_monto, 		@w_val_total, 		
		@i_cta_id, 		@i_cta_fecha, 		@i_usu_id,		@i_cta_obs,		@i_pagc_id,  @i_ven_id)
	end

	if @i_operacion = 'R'
	begin
		select 
			cta_id,
			cli_nombre,
			cli_ruc,
			cta_monto,
			cta_estado,
			CONVERT(varchar, cta_fecha_upd , 20) as cta_fecha_upd,
			cta_obs,
			cc.cli_id
		from 
		tm_cuenta_cliente cc
		inner join tm_cliente c on c.cli_id = cc.cli_id
		where suc_id = @i_suc_id
		and cta_estado = 1
		order by cli_nombre
	end

	if @i_operacion = 'M'
	begin
		select 
			movc_id,
			movc_tipo,
			movc_val_actual,
			movc_valor,
			movc_nuevo_val,
			movc_fecha,
			ISNULL(movc_obs,'Sin observación') as movc_obs
		from 
		tm_movimiento_cuenta mc
		inner join tm_cuenta_cliente cc on cc.cta_id = mc.cta_id
		where cc.cta_id = @i_cta_id
		and cc.suc_id = @i_suc_id
		and cc.cta_estado = 1
		and movc_estado = 1
		order by movc_fecha desc
	end

	if @i_operacion = 'W'
	begin

		set @w_val_pedido = null
		set	@w_val_pedido_abo = null
		set @w_val_ventas = null
		
		-- SE OBTIENE EL CLIENTE DE LA CUENTA
		select @w_cli_id = cli_id from tm_cuenta_cliente where cta_id = @i_cta_id and suc_id = @i_suc_id

		-- SE OBTIENEN VALORES DE PEDIDOS Y VENTAS
		select  @w_val_pedido_abo = sum(movc_valor) from tm_salida_lote s 
		inner join tm_movimiento_cuenta mc on mc.salida_id = s.salida_id
		where s.cli_id = @w_cli_id
		and s.salida_estado = 1 
		and salida_vpagado not in ('C')
		and movc_tipo = '-'

		select  @w_val_pedido = sum(movc_valor) from tm_salida_lote s 
		inner join tm_movimiento_cuenta mc on mc.salida_id = s.salida_id
		where s.cli_id = @w_cli_id
		and s.salida_estado = 1 
		and salida_vpagado not in ('C')
		and movc_tipo = '+'

		set @w_val_pedido = ISNULL(@w_val_pedido, 0) - isnull(@w_val_pedido_abo ,0)

		-- VALOR PENDIENTE VENTAS
		select @w_val_ventas = sum(rvc_monto - rvc_abonado) from tm_ventas v
		inner join tm_registro_vencred vc on vc.ven_id = v.ven_id
		where v.cli_id = @w_cli_id
		and vc.rvc_estado = 1
		and vc.rvc_est_cta not in ('C')

		select 
			cta_id,
			cli_nombre,
			cli_telefono,
			cta_monto,
			(select top 1 CONVERT(varchar,movc_fecha,22) from tm_movimiento_cuenta 
			where cta_id = @i_cta_id and movc_tipo = '+' and movc_estado = 1
			order by movc_fecha desc) as ult_fecha_sal,
			(select top 1 CONVERT(varchar,movc_fecha,22) from tm_movimiento_cuenta 
			where cta_id = @i_cta_id and movc_tipo = '-' and movc_estado = 1
			order by movc_fecha desc) as ult_fecha_pago,
			c.cli_id,
			isnull(@w_val_pedido, 0) as 'total_pedidos',
			isnull(@w_val_ventas, 0) as 'total_ventas'
		from 
		tm_cuenta_cliente cc
		inner join tm_cliente c on c.cli_id = cc.cli_id
		where suc_id = @i_suc_id
		and cta_id = @i_cta_id
		and cta_estado = 1
	end

	if @i_operacion = 'D'
	begin

		select @w_cli_id = cli_id from tm_cuenta_cliente
		where cta_id = @i_cta_id

		update tm_movimiento_cuenta
		set movc_estado = 0
		where cta_id = @i_cta_id

		update tm_pago_cuenta
		set pagc_estado = 0
		where cta_id = @i_cta_id

		update tm_cuenta_cliente
		set cta_estado = 0
		where cta_id = @i_cta_id
		and suc_id = @i_suc_id

		update tm_salida_lote
		set salida_vpagado = 'C', 
		salida_estado = 0
		where cli_id = @w_cli_id

	end

	if @i_operacion = 'B'
	begin
		select 
			cta_id,
			cli_nombre,
			cli_telefono,
			cta_monto,
			(select top 1 CONVERT(varchar,movc_fecha,22) from tm_movimiento_cuenta 
			where cta_id = @i_cta_id and movc_tipo = '+' and movc_estado = 1
			order by movc_fecha desc) as ult_fecha_sal,
			(select top 1 CONVERT(varchar,movc_fecha,22) from tm_movimiento_cuenta 
			where cta_id = @i_cta_id and movc_tipo = '-' and movc_estado = 1
			order by movc_fecha desc) as ult_fecha_pago,
			c.cli_id
		from 
		tm_cuenta_cliente cc
		inner join tm_cliente c on c.cli_id = cc.cli_id
		where suc_id = @i_suc_id
		and c.cli_id = @i_cli_id
		and cta_estado = 1
	end

	if @i_operacion = 'L'
	begin
		
		create table #reporte_cuenta_cli(
			cli_id int not null,
			cli_nombre varchar(150),
			cta_id int not null,
			cta_monto decimal(16,2),
			monto_pedidos decimal(16,2),
			monto_ventas decimal(16,2),
			cta_obs varchar(120),
			cta_fecha_ult date
		)
		insert into #reporte_cuenta_cli
			(cli_id, cli_nombre , cta_id, cta_monto, cta_obs, cta_fecha_ult)
		select  
			cc.cli_id, cli_nombre, cta_id, cta_monto, cta_obs, cta_fecha_upd
		from tm_cuenta_cliente cc
		inner join tm_cliente c on c.cli_id = cc.cli_id
		where cc.cta_estado = 1
		and cc.cta_monto > 0
		and cc.suc_id = @i_suc_id
		order by cli_id

		select @w_total_registros = COUNT(*) from #reporte_cuenta_cli
		if @w_total_registros > 0
		begin
			select @w_min_reg = MIN(cli_id),
					@w_max_reg = MAX(cli_id)
			from #reporte_cuenta_cli

			while(@w_min_reg <= @w_max_reg)
			begin
				set @w_cli_id = @w_min_reg
				set @w_val_pedido = null
				set @w_val_ventas = null
				set @w_val_pedido_abo = null

				-- SE OBTIENEN VALORES DE PEDIDOS Y VENTAS
				select  @w_val_pedido_abo = sum(movc_valor) from tm_salida_lote s 
				inner join tm_movimiento_cuenta mc on mc.salida_id = s.salida_id
				where s.cli_id = @w_min_reg
				and s.salida_estado = 1 
				and salida_vpagado not in ('C')
				and movc_tipo = '-'

				select  @w_val_pedido = sum(movc_valor) from tm_salida_lote s 
				inner join tm_movimiento_cuenta mc on mc.salida_id = s.salida_id
				where s.cli_id = @w_min_reg
				and s.salida_estado = 1 
				and salida_vpagado not in ('C')
				and movc_tipo = '+'

				set @w_val_pedido = ISNULL(@w_val_pedido, 0) - isnull(@w_val_pedido_abo ,0)

				-- VALOR PENDIENTE VENTAS
				select @w_val_ventas = sum(rvc_monto - rvc_abonado) from tm_ventas v
				inner join tm_registro_vencred vc on vc.ven_id = v.ven_id
				where v.cli_id = @w_min_reg
				and vc.rvc_estado = 1
				and vc.rvc_est_cta not in ('C')

				update #reporte_cuenta_cli
				set monto_pedidos = ISNULL(@w_val_pedido,0),
					monto_ventas = ISNULL(@w_val_ventas , 0)
				where cli_id = @w_min_reg

				print  'Antes:' + convert(varchar(20),@w_cli_id )

				select top 1 @w_cli_id = cli_id from #reporte_cuenta_cli where cli_id > @w_cli_id order by cli_id asc

				print 'Despues:' + convert(varchar(20),@w_cli_id )

				if @w_max_reg = @w_min_reg
					break

				set @w_min_reg = @w_cli_id
			end

			select * from #reporte_cuenta_cli order by cli_nombre
		end

	end
	
	if @i_operacion = 'Z'
	begin
		select 
			movc_id, 
			isnull(salida_id, 0) as salida_id, 
			isnull(ven_id, 0) as ven_id, 
			movc_tipo, 
			movc_valor,
			CONVERT(varchar,movc_fecha,20) as movc_fecha, 
			movc_obs, 
			isnull(pagc_id , '') as pagc_id
		from tm_movimiento_cuenta 
		where cta_id = @i_cta_id
		and movc_estado = 1
		and CAST(movc_fecha as date) between @i_fecha_desde and @i_fecha_hasta
		and movc_tipo = isnull(@i_movc_tipo, movc_tipo)
	end
end
