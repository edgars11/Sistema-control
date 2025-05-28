USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_compania]    Script Date: 28/5/2025 17:53:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_compania] (
 @i_operacion char(1) ,
 @i_tipo char(1) null,
 @i_com_id tinyint null,
 @i_com_nombre varchar(150) null,
 @i_com_estado tinyint = 1
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_compania
		(com_nombre, com_fecha_crea, com_estado)
		values
		(@i_com_nombre, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_compania set 
			com_nombre = @i_com_nombre
		where com_id = @i_com_id
	end

	if @i_operacion = 'D'
	begin
		update tm_compania set 
			com_estado = @i_com_estado
		where com_id = @i_com_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			select * from tm_compania
			where com_estado = @i_com_estado
		end
		if @i_tipo = 'I'
		begin
			select * from tm_compania
			where com_estado = @i_com_estado
			and com_id = @i_com_id
		end
	end

end
