USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_empresa]    Script Date: 20/2/2025 20:53:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_empresa] (
 @i_operacion char(1) ,
 @i_tipo char(1)= null,
 @i_emp_nombre varchar(150) = null,
 @i_emp_ruc varchar(15) = null,
 @i_com_id tinyint = null,
 @i_emp_id tinyint = null,
 @i_emp_estado tinyint = 1
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_empresa
		(emp_nombre, com_id, emp_ruc, emp_fecha_crea, emp_estado)
		values
		(@i_emp_nombre, @i_com_id, @i_emp_ruc, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_empresa set 
			emp_nombre = @i_emp_nombre,
			emp_ruc = @i_emp_ruc			
		where emp_id = @i_emp_id
		and com_id = @i_com_id
	end

	if @i_operacion = 'D'
	begin
		update tm_empresa set 
			emp_estado = @i_emp_estado
		where emp_id = @i_emp_id 
		and com_id = @i_com_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			select
				emp_id,
				emp_nombre,
				emp_ruc,
				CONVERT(varchar(30), emp_fecha_crea ,22) as emp_fecha_crea,
				emp_estado
			from tm_empresa
			where emp_estado = @i_emp_estado
			and com_id = @i_com_id
		end
		if @i_tipo = 'I'
		begin
			select * from tm_empresa
			where emp_estado = 1
			and emp_id = @i_emp_id
			and com_id = @i_com_id
		end
		if @i_tipo = 'R'
		begin
			select * from tm_empresa
			where emp_estado = @i_emp_estado
			and emp_ruc = @i_emp_ruc
			and com_id = @i_com_id
		end

	end

end
