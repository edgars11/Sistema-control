USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_proveedor]    Script Date: 28/5/2025 18:51:12 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_proveedor] (
 @i_operacion char(1) ,
 @i_tipo char(1)= null,
 @i_emp_id int = null,
 @i_prov_id tinyint = null,
 @i_prov_nombre varchar(100) = null,
 @i_prov_ruc varchar(40) = null,
 @i_prov_telefono varchar(40) = null,
 @i_prov_direccion varchar(40) = null,
 @i_prov_correo varchar(40) = null,
 @i_prov_estado tinyint = 1
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_proveedor
		(emp_id, prov_nombre, prov_ruc, prov_telefono, prov_direccion, prov_correo, prov_fecha_crea, prov_estado)
		values
		(@i_emp_id, @i_prov_nombre, @i_prov_ruc, @i_prov_telefono, @i_prov_direccion, @i_prov_correo, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_proveedor set 
			prov_nombre = @i_prov_nombre,
			prov_ruc	= @i_prov_ruc,
			prov_telefono = @i_prov_telefono,
			prov_direccion = @i_prov_direccion,
			prov_correo = @i_prov_correo
		where prov_id = @i_prov_id
		and emp_id = @i_emp_id
	end

	if @i_operacion = 'D'
	begin
		update tm_proveedor set 
			prov_estado = @i_prov_estado
		where prov_id = @i_prov_id
		and emp_id = @i_emp_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'I'
		begin
			select * from tm_proveedor
			where prov_estado = @i_prov_estado
			and prov_id = @i_prov_id
		end
		if @i_tipo = 'E'
		begin
			select * from tm_proveedor
			where prov_estado = 1
			and emp_id = @i_emp_id
		end
		if @i_tipo = 'C'
		begin
			select * from tm_proveedor
			where prov_estado = 1
			and emp_id = @i_emp_id
			and prov_ruc = @i_prov_ruc
		end
		if @i_tipo = 'N'
		begin
			select * from tm_proveedor
			where prov_estado = @i_prov_estado
			and emp_id = @i_emp_id
			and prov_nombre like '%'+@i_prov_nombre+'%'
		end
	end

end
