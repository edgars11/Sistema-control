USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_venta]    Script Date: 8/7/2025 20:26:26 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_venta] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_ven_id int = null,
 @i_pago_id int = null,
 @i_cli_id int = null,
 @i_ven_subtotal numeric(18,2) = null,
 @i_ven_iva numeric(18,2) = null,
 @i_ven_total numeric(18,2) = null,
 @i_usu_id int = null,
 @i_suc_id int = null,
 @i_tc_id int = null,
 @i_fecha_ini varchar(20) = null,
 @i_fecha_hasta varchar(20) = null,
 @i_ven_estado tinyint = null,
 @i_ven_comment varchar(100) = null
)
as
begin
declare 
@w_subtotal 	decimal(18,2), 
@w_iva 			decimal(18,2),
@w_total 		decimal(18,2),
@w_fecha 		datetime,
@w_cta_obs 		varchar(100),
@w_pago_des 	varchar(50),
@w_pago_cred 	int,
@w_cta_cli		int

set nocount on
	if @i_operacion = 'C'
	begin
		delete from tm_ventas
		where ven_estado = 2 and usu_id = @i_usu_id and suc_id = @i_suc_id

		insert into tm_ventas 
		(ven_estado	,usu_id,	suc_id)
		values
		(2, @i_usu_id, @i_suc_id)

		select ven_id from tm_ventas where ven_id = @@IDENTITY
	end
	if @i_operacion = 'T'
	begin
		select @w_subtotal = SUM(detv_total) FROM tm_detalle_venta where ven_id = @i_ven_id  and detv_estado = 1
		select @w_iva = 0.00 -- @w_subtotal * 0.15
		select @w_total = @w_subtotal + @w_iva

		update tm_ventas
		set ven_subtotal = @w_subtotal,
		ven_iva = @w_iva,
		ven_total = @w_total
		where ven_id = @i_ven_id

		select 
			@w_subtotal as 'subtotal',
			@w_iva as 'iva', 
			@w_total as 'total'
	end

	if @i_operacion = 'U'
	begin

		select  @w_fecha = GETDATE(),
				@w_pago_cred = 5

		update tm_ventas
		set 
		pago_id = @i_pago_id,
		cli_id = @i_cli_id,
		tc_id = @i_tc_id,
		ven_coment = @i_ven_comment,
		ven_fecha_crea = @w_fecha,
		ven_estado = 1
		where ven_id = @i_ven_id

		exec sp_update_stock @i_operacion= 'SV', @i_ven_id = @i_ven_id

		-- SE VALIDA SI LA CUENTA ES A CRÉDITO PARA AGREGAR EL SALDO A LA CUENTA
		select @w_pago_des = pago_nombre from tm_tipo_pago where pago_id = @i_pago_id and pago_estado = 1

		if @w_pago_des = 'CREDITO' or @w_pago_cred = @i_pago_id
		begin
			-- OBSERVACION CUENTA CLIENE
			select @w_cta_obs = 'Venta # ' + CONVERT(varchar, @i_ven_id)

			-- VALIDA SI EL CLIENTE TIENE CUENTA CREADA SINO SE CREA UNA NUEVA
			Select @w_cta_cli = cta_id from tm_cuenta_cliente where cli_id = @i_cli_id and suc_id = @i_suc_id and cta_estado = 1

			if ISNULL(@w_cta_cli,0) = 0
			begin
				exec sp_crud_cuenta_cli 
					@i_operacion = 'C',
					@i_cli_id	 = @i_cli_id,
					@i_cta_monto = @i_ven_total,
					@i_cta_fecha = @w_fecha,
					@i_cta_obs	 = @w_cta_obs,
					@i_ven_id 	 = @i_ven_id,
					@i_suc_id	 = @i_suc_id,
					@i_usu_id	 = @i_usu_id,
					@o_cta_cli	 = @w_cta_cli

			end 
			else if @w_cta_cli > 0
			begin
				exec sp_crud_cuenta_cli 
					@i_operacion = 'U', 
					@i_cta_id	 = @w_cta_cli,
					@i_cli_id	 = @i_cli_id,
					@i_cta_monto = @i_ven_total,
					@i_cta_fecha = @w_fecha,
					@i_cta_obs	 = @w_cta_obs,
					@i_ven_id 	 = @i_ven_id,
					@i_suc_id	 = @i_suc_id,
					@i_usu_id	 = @i_usu_id,
					@i_movc_tipo = '+'
			end	

			-- SE REGISTRA LA VENTA A CREDITO
			INSERT INTO tm_registro_vencred
			(ven_id,		rvc_monto,		rvc_abonado,	rvc_est_cta,	rvc_fecha_upd,	rvc_estado,		rvc_observacion)VALUES
			(@i_ven_id,	@i_ven_total,	0,				'P',			@w_fecha,		1,				'Ingresado')
		end
	end

	if @i_operacion = 'L'
	begin
		SELECT        
		tm_ventas.ven_id, 
		(select pago_nombre from tm_tipo_pago where pago_id = tm_ventas.pago_id) as pago_nom, 
		(select cli_nombre from tm_cliente where cli_id = tm_ventas.cli_id) as cli_nombre, 
		tm_ventas.ven_subtotal, 
		tm_ventas.ven_iva, 
		tm_ventas.ven_total, 
		(select tc_descripcion from tm_tipo_comprobante where tc_id = tm_ventas.tc_id ) as tipo_comp,
		tm_ventas.ven_fecha_crea, 
		tm_ventas.ven_estado,
		tm_ventas.usu_id, 
		(select CONCAT(usu_nombre,' ',usu_apellido) from tm_usuario where usu_id = tm_ventas.usu_id ) as usu_nom,
		tm_ventas.suc_id, 
		tm_sucursal.suc_nombre, 
		tm_empresa.emp_nombre, 
		tm_empresa.emp_ruc, 
		tm_empresa.emp_correo, 
		tm_empresa.emp_telefono, 
		tm_empresa.emp_web,
		tm_empresa.emp_direccion, 
		tm_compania.com_nombre,
		tm_ventas.ven_coment
		FROM   tm_ventas INNER JOIN
				tm_sucursal ON tm_ventas.suc_id = tm_sucursal.suc_id INNER JOIN
				tm_empresa ON tm_sucursal.emp_id = tm_empresa.emp_id INNER JOIN
				tm_compania ON tm_empresa.com_id = tm_compania.com_id
		where tm_ventas.ven_id = @i_ven_id
		
	end

	if @i_operacion = 'A'
	begin
		SELECT        
		tm_ventas.ven_id, 
		(select pago_nombre from tm_tipo_pago where pago_id = tm_ventas.pago_id) as pago_nom, 
		(select cli_nombre from tm_cliente where cli_id = tm_ventas.cli_id) as cli_nombre, 
		(select cli_ruc from tm_cliente where cli_id = tm_ventas.cli_id) as cli_ruc, 
		tm_ventas.ven_subtotal, 
		tm_ventas.ven_iva, 
		tm_ventas.ven_total, 
		tm_ventas.tc_id, 
		tm_ventas.ven_fecha_crea, 
		tm_ventas.ven_estado,
		tm_ventas.usu_id, 
		(select CONCAT(usu_nombre,' ',usu_apellido) from tm_usuario where usu_id = tm_ventas.usu_id ) as usu_nom,
		(select tc_descripcion from tm_tipo_comprobante where tc_id = tm_ventas.tc_id ) as tipo_comp,
		tm_ventas.suc_id, 
		tm_sucursal.suc_nombre, 
		tm_empresa.emp_nombre, 
		tm_empresa.emp_ruc, 
		tm_empresa.emp_correo, 
		tm_empresa.emp_telefono, 
		tm_empresa.emp_web,
		tm_empresa.emp_direccion, 
		tm_compania.com_nombre,
		tm_ventas.ven_coment
		FROM   tm_ventas INNER JOIN
				tm_sucursal ON tm_ventas.suc_id = tm_sucursal.suc_id INNER JOIN
				tm_empresa ON tm_sucursal.emp_id = tm_empresa.emp_id INNER JOIN
				tm_compania ON tm_empresa.com_id = tm_compania.com_id
		where tm_ventas.suc_id = @i_suc_id
		and tm_ventas.ven_estado = 1
		and CAST(ven_fecha_crea as date) between ISNULL(@i_fecha_ini,CAST(ven_fecha_crea as date)) and ISNULL(@i_fecha_hasta,CAST(ven_fecha_crea as date))
		and tm_ventas.cli_id = isnull(@i_cli_id, tm_ventas.cli_id)
		and tm_ventas.pago_id = ISNULL(@i_pago_id , tm_ventas.pago_id)
		order by ven_fecha_crea desc
	end

	if @i_operacion = 'P'
	begin
		select 
		ven_id, 
		ven_total, 
		ven_fecha_crea, 
		ven_coment, 
		pago_nombre, 
		cli_nombre ,
		v.suc_id
		from tm_ventas v
		inner join tm_tipo_pago p on v.pago_id = p.pago_id
		inner join tm_cliente cl on cl.cli_id = v.cli_id
		where v.ven_estado = 1
		and CAST(ven_fecha_crea as date) between @i_fecha_ini and @i_fecha_hasta
		and v.pago_id = ISNULL(@i_pago_id, v.pago_id)
		and v.suc_id = @i_suc_id
		and v.cli_id = @i_cli_id
	end


set nocount off
end
