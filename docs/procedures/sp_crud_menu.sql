USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_menu]    Script Date: 28/5/2025 18:49:00 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_menu] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_usu_id int = null,
 @i_men_identi varchar(120) = null,
 @i_rol_id int = null
)
as
declare @w_valida char(1)
begin

	if @i_operacion = 'V'
	begin
		set @w_valida = 'N'

		if @i_tipo = 'I'
		begin
			select @w_valida = 'S' from tm_menu_rol mr
			inner join tm_menu m on m.men_id = mr.men_id
			inner join tm_rol rl on rl.rol_id = mr.rol_id
			inner join tm_usuario u on u.usu_rol_id = rl.rol_id
			where u.usu_id = @i_usu_id
			and mr.mend_permiso = 'S'
			and m.men_identi = @i_men_identi

			if @@ROWCOUNT = 0
			begin
				select @w_valida = 'N'
			end

			select @w_valida as validacion
		end

	end

	if @i_operacion = 'I'
	begin
		if (select COUNT(*) from tm_menu_rol where rol_id = @i_rol_id) = 0
		begin	
			insert into tm_menu_rol 
			(men_id , rol_id , mend_permiso, mend_fecha_crea, mend_estado)
			(select men_id, @i_rol_id, 'N', GETDATE(), 1 from tm_menu where men_estado=1)
		end
		else
		begin
			insert into tm_menu_rol 
			(men_id , rol_id , mend_permiso, mend_fecha_crea, mend_estado)
			(select men_id, @i_rol_id, 'N', GETDATE(), 1 
			from tm_menu where men_estado=1 and men_id not in(Select men_id from tm_menu_rol where rol_id = @i_rol_id))
		end
	end

end
