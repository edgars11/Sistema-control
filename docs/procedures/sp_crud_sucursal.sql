USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_sucursal]    Script Date: 28/5/2025 18:51:52 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_sucursal] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_suc_id tinyint = null,
 @i_suc_nombre varchar(125) = null,
 @i_emp_id tinyint = null,
 @i_suc_estado tinyint = 1
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_sucursal
		(suc_nombre, emp_id, suc_fecha_crea, suc_estado)
		values
		(@i_suc_nombre, @i_emp_id, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_sucursal set 
			suc_nombre = @i_suc_nombre
		where suc_id = @i_suc_id
		and emp_id = @i_emp_id
	end

	if @i_operacion = 'D'
	begin
		update tm_sucursal set 
			suc_estado = @i_suc_estado
		where suc_id = @i_suc_id 
		and emp_id = @i_emp_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			select 
				suc_id,
				suc_nombre,
				CONVERT(varchar(30), suc_fecha_crea, 22) as suc_fecha_crea,
				suc_estado
			from tm_sucursal
			where suc_estado = 1
			and emp_id = @i_emp_id
		end
		if @i_tipo = 'I'
		begin
			select 
				suc_id,
				suc_nombre,
				emp_id,
				CONVERT(varchar(30), suc_fecha_crea, 22) as suc_fecha_crea,
				suc_estado
			from tm_sucursal
			where suc_estado = 1
			and suc_id = @i_suc_id
		end
		if @i_tipo = 'N'
		begin
			select 
				suc_id,
				suc_nombre,
				CONVERT(varchar(30), suc_fecha_crea, 22) as suc_fecha_crea,
				suc_estado
			from tm_sucursal
			where suc_estado = @i_suc_estado
			and suc_nombre like '%'+@i_suc_nombre+'%'
		end
	end

end
