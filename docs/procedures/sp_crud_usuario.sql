USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_usuario]    Script Date: 28/5/2025 18:52:58 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_usuario] (
 @i_operacion char(1),
 @i_tipo char(1) = null,
 @i_usu_id tinyint = null,
 @i_usu_nombre varchar(60) = null,
 @i_usu_apellido varchar(60) = null,
 @i_usu_correo varchar(70) = null,
 @i_usu_dni varchar(20) = null,
 @i_usu_telefono varchar(20) = null,
 @i_usu_password varchar(20) = null,
 @i_suc_id tinyint = null,
 @i_rol_id tinyint = null,
 @i_usu_estado tinyint = 1
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_usuario
		(usu_correo, usu_nombre, usu_apellido, usu_dni, usu_telefono, usu_password, usu_rol_id, suc_id, usu_fecha_crea, usu_estado)
		values
		(@i_usu_correo, @i_usu_nombre, @i_usu_apellido, @i_usu_dni, @i_usu_telefono, @i_usu_password, @i_rol_id, @i_suc_id, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_usuario set 
			usu_nombre = @i_usu_nombre,
			usu_correo = @i_usu_correo,
			usu_apellido = @i_usu_apellido,
			usu_dni = @i_usu_dni,
			usu_telefono = @i_usu_telefono,
			usu_password = @i_usu_password,
			usu_rol_id = @i_rol_id
		where usu_id = @i_usu_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'D'
	begin
		update tm_usuario set 
			usu_estado = @i_usu_estado
		where usu_id = @i_usu_id 
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'S'
		begin
			SELECT        
			tm_usuario.usu_id, 
			tm_usuario.suc_id, 
			tm_usuario.usu_correo, 
			tm_usuario.usu_nombre, 
			tm_usuario.usu_apellido, 
			tm_usuario.usu_dni, 
			tm_usuario.usu_telefono, 
			tm_usuario.usu_password, 
			tm_usuario.usu_fecha_crea, 
			tm_usuario.usu_estado, 
			tm_rol.rol_nombre, 
			tm_rol.rol_id, 
			tm_usuario.usu_rol_id
			FROM tm_usuario inner join
			tm_rol on tm_rol.rol_id = tm_usuario.usu_rol_id
			where usu_estado = @i_usu_estado
			and tm_usuario.suc_id	= @i_suc_id
		end
		if @i_tipo = 'I'
		begin
			SELECT        
			tm_usuario.usu_id, 
			tm_usuario.suc_id, 
			tm_usuario.usu_correo, 
			tm_usuario.usu_nombre, 
			tm_usuario.usu_apellido, 
			tm_usuario.usu_dni, 
			tm_usuario.usu_telefono, 
			tm_usuario.usu_password, 
			tm_usuario.usu_fecha_crea, 
			tm_usuario.usu_estado, 
			tm_rol.rol_nombre, 
			tm_rol.rol_id, 
			tm_usuario.usu_rol_id
			FROM tm_usuario inner join
			tm_rol on tm_rol.rol_id = tm_usuario.usu_rol_id
			where usu_estado = @i_usu_estado
			and usu_id = @i_usu_id
			and tm_usuario.suc_id	= @i_suc_id

		end
		if @i_tipo = 'N'
		begin
			SELECT        
			tm_usuario.usu_id, 
			tm_usuario.suc_id, 
			tm_usuario.usu_correo, 
			tm_usuario.usu_nombre, 
			tm_usuario.usu_apellido, 
			tm_usuario.usu_dni, 
			tm_usuario.usu_telefono, 
			tm_usuario.usu_password, 
			tm_usuario.usu_fecha_crea, 
			tm_usuario.usu_estado, 
			tm_rol.rol_nombre, 
			tm_rol.rol_id, 
			tm_usuario.usu_rol_id
			FROM tm_usuario inner join
			tm_rol on tm_rol.rol_id = tm_usuario.usu_rol_id
			where usu_estado = @i_usu_estado
			and usu_nombre like '%'+@i_usu_nombre+'%'
			and tm_usuario.suc_id	= @i_suc_id

		end
		if @i_tipo = 'R'
		begin
			SELECT        
			tm_usuario.usu_id, 
			tm_usuario.suc_id, 
			tm_usuario.usu_correo, 
			tm_usuario.usu_nombre, 
			tm_usuario.usu_apellido, 
			tm_usuario.usu_dni, 
			tm_usuario.usu_telefono, 
			tm_usuario.usu_password, 
			tm_usuario.usu_fecha_crea, 
			tm_usuario.usu_estado, 
			tm_rol.rol_nombre, 
			tm_rol.rol_id, 
			tm_usuario.usu_rol_id
			FROM tm_usuario inner join
			tm_rol on tm_rol.rol_id = tm_usuario.usu_rol_id
			where usu_estado = @i_usu_estado
			and usu_rol_id = @i_rol_id
			and tm_usuario.suc_id	= @i_suc_id

		end
	end

	if @i_operacion = 'L'
	begin
		SELECT        
		tm_usuario.usu_id, 
		tm_usuario.suc_id, 
		tm_usuario.usu_nombre, 
		tm_usuario.usu_apellido, 
		tm_usuario.usu_correo, 
		tm_usuario.usu_password, 
		tm_usuario.usu_dni, 
		tm_usuario.usu_telefono, 
		tm_sucursal.emp_id, 
		tm_sucursal.suc_nombre, 
		tm_empresa.emp_id, 
		tm_empresa.emp_nombre, 
		tm_empresa.emp_ruc, 
		tm_empresa.com_id, 
		tm_compania.com_nombre,
		tm_usuario.usu_rol_id
		FROM       
		tm_usuario INNER JOIN
		tm_sucursal ON tm_usuario.suc_id = tm_sucursal.suc_id INNER JOIN
		tm_empresa ON tm_sucursal.emp_id = tm_empresa.emp_id INNER JOIN
		tm_compania ON tm_empresa.com_id = tm_compania.com_id
		WHERE 
		tm_usuario.suc_id = @i_suc_id
		AND tm_usuario.usu_correo = @i_usu_correo
		AND tm_usuario.usu_password = @i_usu_password
		AND tm_usuario.usu_estado = 1
	end

	if @i_operacion = 'P'
	begin
		update tm_usuario
		set usu_password = @i_usu_password
		where usu_id = @i_usu_id
	end
end
