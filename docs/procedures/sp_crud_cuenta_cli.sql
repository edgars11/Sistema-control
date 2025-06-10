USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_cuenta_cli]    Script Date: 2/6/2025 23:00:02 ******/
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
 @i_cta_fecha varchar(70) = null,
 @o_cta_cli int = 0 out
)
as
declare
@w_cta_id int,
@w_val_actual decimal(14,2),
@w_val_nuevo decimal(14,2),
@w_val_total decimal(14,2)
begin
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
			cta_obs
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
		and cta_id = @i_cta_id
		and cta_estado = 1
	end

	if @i_operacion = 'D'
	begin
		update tm_cuenta_cliente
		set cta_estado = 0
		where cta_id = @i_cta_id
		and suc_id = @i_suc_id
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

end
