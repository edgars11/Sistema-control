USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_rol]    Script Date: 28/5/2025 18:51:29 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_rol] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_suc_id int = null,
 @i_rol_id tinyint = null,
 @i_rol_nombre varchar(120) = null,
 @i_rol_estado tinyint = null,
 @i_mend_id int = null,
 @i_menu_permi char(1) = null
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_rol 
		(suc_id, rol_nombre, rol_fecha_crea, rol_estado)
		values
		(@i_suc_id, @i_rol_nombre, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_rol set 
			rol_nombre = @i_rol_nombre
		where rol_id = @i_rol_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'D'
	begin
		update tm_rol set 
			rol_estado = @i_rol_estado
		where rol_id = @i_rol_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			select * from tm_rol
			where rol_estado = 1
		end
		if @i_tipo = 'S'
		begin
			select * from tm_rol
			where rol_estado = 1
			and suc_id = @i_suc_id
		end
		if @i_tipo = 'I'
		begin
			select * from tm_rol
			where rol_estado = 1
			and rol_id = @i_rol_id
			and suc_id = @i_suc_id
		end
	end

	if @i_operacion = 'M'
	begin
		SELECT     
		tm_menu_rol.mend_id, 
		tm_menu_rol.men_id, 
		tm_menu_rol.rol_id , 
		tm_menu_rol.mend_permiso , 
		tm_menu_rol.mend_fecha_crea , 
		tm_menu_rol.mend_estado , 
		tm_menu.men_nombre, 
		tm_menu.men_ruta, 
		tm_menu.men_identi,
		tm_menu.men_grupo
		FROM            
		tm_menu_rol inner join
		tm_menu on tm_menu_rol.men_id = tm_menu.men_id
		where tm_menu_rol.rol_id = @i_rol_id
	end

	if @i_operacion = 'H'
	begin
		update tm_menu_rol
		set mend_permiso = @i_menu_permi
		where mend_id = @i_mend_id
		and rol_id = @i_rol_id
	end

end
