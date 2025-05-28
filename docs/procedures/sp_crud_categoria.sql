USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_categoria]    Script Date: 28/5/2025 17:52:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_categoria] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_suc_id int = null,
 @i_cat_id tinyint = null,
 @i_cat_nombre varchar(75) = null,
 @i_cat_estado tinyint = null
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_categoria 
		(suc_id, cat_nombre, cat_fecha_crea, cat_estado)
		values
		(@i_suc_id, @i_cat_nombre, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_categoria set 
			cat_nombre = @i_cat_nombre
		where cat_id = @i_cat_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'D'
	begin
		update tm_categoria set 
			cat_estado = @i_cat_estado
		where cat_id = @i_cat_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			select * from tm_categoria
			where cat_estado = 1
			and suc_id = @i_suc_id
		end
		if @i_tipo = 'S'
		begin
			select 
			cat_id,
			suc_id,
			cat_nombre,
			CONVERT(varchar, cat_fecha_crea , 103) as cat_fecha_crea,
			cat_estado
			from tm_categoria
			where cat_estado = 1
			and suc_id = @i_suc_id
		end
		if @i_tipo = 'I'
		begin
			select * from tm_categoria
			where cat_estado = 1
			and cat_id = @i_cat_id
		end
	end

end
