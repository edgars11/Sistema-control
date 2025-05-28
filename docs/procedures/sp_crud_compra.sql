USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_compra]    Script Date: 28/5/2025 17:53:25 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_compra] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_comp_id int = null,
 @i_pago_id int = null,
 @i_prov_id int = null,
 @i_comp_subtotal numeric(18,2) = null,
 @i_comp_iva numeric(18,2) = null,
 @i_comp_total numeric(18,2) = null,
 @i_comp_comment varchar(200) = null,
 @i_usu_id int = null,
 @i_suc_id int = null,
 @i_mon_id int = null,
 @i_comp_estado tinyint = null
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
		insert into tm_compra 
		(comp_estado	,usu_id,	suc_id)
		values
		(2, @i_usu_id, @i_suc_id)

		select comp_id from tm_compra where comp_id = @@IDENTITY
	end
	if @i_operacion = 'T'
	begin
		select @w_subtotal = SUM(detc_total) FROM [SistemaControl].[dbo].[tm_detalle_compra] where comp_id = @i_comp_id  and detc_estado = 1
		select @w_iva = @w_subtotal * 0.15
		select @w_total = @w_subtotal + @w_iva

		update tm_compra
		set comp_subtotal = @w_subtotal,
		comp_iva = @w_iva,
		comp_total = @w_total
		where comp_id = @i_comp_id

		select 
			@w_subtotal as 'subtotal',
			@w_iva as 'iva', 
			@w_total as 'total'
	end

	if @i_operacion = 'U'
	begin
		update tm_compra
		set 
		pago_id = @i_pago_id,
		prov_id = @i_prov_id,
		comp_comment = @i_comp_comment,
		comp_fecha_crea = GETDATE(),
		mon_id = @i_mon_id,
		comp_estado = 1
		where comp_id = @i_comp_id

		exec sp_update_stock @i_operacion= 'SC', @i_comp_id= @i_comp_id

	end

	if @i_operacion = 'L'
	begin
		SELECT        
		tm_compra.comp_id, 
		(select pago_nombre from tm_pago where pago_id = tm_compra.pago_id) as pago_nom, 
		(select prov_nombre from tm_proveedor where prov_id = tm_compra.prov_id) as prov_nombre, 
		tm_compra.comp_subtotal, 
		tm_compra.comp_iva, 
		tm_compra.comp_total, 
		tm_compra.comp_comment, 
		tm_compra.comp_fecha_crea, 
		tm_compra.comp_estado,
		tm_compra.usu_id, 
		(select CONCAT(usu_nombre,' ',usu_apellido) from tm_usuario where usu_id = tm_compra.usu_id ) as usu_nom,
		tm_compra.suc_id, 
		(SELECT mon_nombre FROM tm_moneda where mon_id = tm_compra.mon_id ) as mon_nombre, 
		tm_sucursal.suc_nombre, 
		tm_empresa.emp_nombre, 
		tm_empresa.emp_ruc, 
		tm_empresa.emp_correo, 
		tm_empresa.emp_telefono, 
		tm_empresa.emp_web,
		tm_empresa.emp_direccion, 
		tm_compania.com_nombre
		FROM   tm_compra INNER JOIN
				tm_sucursal ON tm_compra.suc_id = tm_sucursal.suc_id INNER JOIN
				tm_empresa ON tm_sucursal.emp_id = tm_empresa.emp_id INNER JOIN
				tm_compania ON tm_empresa.com_id = tm_compania.com_id
		where tm_compra.comp_id = @i_comp_id
	end

	if @i_operacion = 'A'
	begin
		SELECT        
		tm_compra.comp_id, 
		(select pago_nombre from tm_pago where pago_id = tm_compra.pago_id) as pago_nom, 
		(select prov_nombre from tm_proveedor where prov_id = tm_compra.prov_id) as prov_nombre, 
		(select prov_ruc from tm_proveedor where prov_id = tm_compra.prov_id) as prov_ruc, 
		tm_compra.comp_subtotal, 
		tm_compra.comp_iva, 
		tm_compra.comp_total, 
		tm_compra.comp_comment, 
		tm_compra.comp_fecha_crea, 
		tm_compra.comp_estado,
		tm_compra.usu_id, 
		(select CONCAT(usu_nombre,' ',usu_apellido) from tm_usuario where usu_id = tm_compra.usu_id ) as usu_nom,
		tm_compra.suc_id, 
		(SELECT mon_nombre FROM tm_moneda where mon_id = tm_compra.mon_id ) as mon_nombre, 
		tm_sucursal.suc_nombre, 
		tm_empresa.emp_nombre, 
		tm_empresa.emp_ruc, 
		tm_empresa.emp_correo, 
		tm_empresa.emp_telefono, 
		tm_empresa.emp_web,
		tm_empresa.emp_direccion, 
		tm_compania.com_nombre
		FROM   tm_compra INNER JOIN
				tm_sucursal ON tm_compra.suc_id = tm_sucursal.suc_id INNER JOIN
				tm_empresa ON tm_sucursal.emp_id = tm_empresa.emp_id INNER JOIN
				tm_compania ON tm_empresa.com_id = tm_compania.com_id
		where tm_compra.suc_id = @i_suc_id
		and tm_compra.comp_estado = 1
	end

set nocount off
end
