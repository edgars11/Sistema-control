USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_umedida]    Script Date: 28/5/2025 18:52:40 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_umedida] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_suc_id int = null,
 @i_unm_id tinyint = null,
 @i_unm_nombre varchar(120) = null,
 @i_unm_estado tinyint = 1
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_unidad 
		(suc_id, unm_nombre, unm_fecha_crea, unm_estado)
		values
		(@i_suc_id, @i_unm_nombre, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_unidad set 
			unm_nombre = @i_unm_nombre
		where unm_id = @i_unm_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'D'
	begin
		update tm_unidad set 
			unm_estado = @i_unm_estado
		where unm_id = @i_unm_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			select
				unm_id,
				unm_nombre,
				CONVERT(varchar(30), unm_fecha_crea , 22) as unm_fecha_crea,
				unm_estado
			from tm_unidad
			where unm_estado = @i_unm_estado
		end
		if @i_tipo = 'S'
		begin
			select 
				unm_id,
				unm_nombre,
				CONVERT(varchar(30), unm_fecha_crea , 22) as unm_fecha_crea,
				unm_estado
			from tm_unidad
			where unm_estado = 1
			and suc_id = @i_suc_id
		end
		if @i_tipo = 'I'
		begin
			select 
				unm_id,
				suc_id,
				unm_nombre,
				CONVERT(varchar(30), unm_fecha_crea , 22) as unm_fecha_crea,
				unm_estado
			from tm_unidad
			where unm_estado = 1
			and unm_id = @i_unm_id
		end
	end

end
