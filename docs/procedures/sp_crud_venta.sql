USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_venta]    Script Date: 3/6/2025 23:14:14 ******/
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
 @i_ven_estado tinyint = null,
 @i_ven_comment varchar(100) = null
)
as
begin
declare 
@w_subtotal decimal(18,2), 
@w_iva decimal(18,2),
@w_total decimal(18,2)

set nocount on
	if @i_operacion = 'C'
	begin
		insert into tm_ventas 
		(ven_estado	,usu_id,	suc_id)
		values
		(2, @i_usu_id, @i_suc_id)

		select ven_id from tm_ventas where ven_id = @@IDENTITY
	end
	if @i_operacion = 'T'
	begin
		select @w_subtotal = SUM(detv_total) FROM [SistemaControl].[dbo].[tm_detalle_venta] where ven_id = @i_ven_id  and detv_estado = 1
		select @w_iva = @w_subtotal * 0.15
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
		update tm_ventas
		set 
		pago_id = @i_pago_id,
		cli_id = @i_cli_id,
		tc_id = @i_tc_id,
		ven_coment = @i_ven_comment,
		ven_fecha_crea = GETDATE(),
		ven_estado = 1
		where ven_id = @i_ven_id

		exec sp_update_stock @i_operacion= 'SV', @i_ven_id = @i_ven_id

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
	end

set nocount off
end
