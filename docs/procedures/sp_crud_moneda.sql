USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_moneda]    Script Date: 28/5/2025 18:49:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_moneda] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_suc_id int = null,
 @i_mon_id tinyint = null,
 @i_mon_nombre varchar(50) = null,
 @i_mon_estado tinyint = null
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_moneda 
		(suc_id, mon_nombre, mon_fecha_crea, mon_estado)
		values
		(@i_suc_id, @i_mon_nombre, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_moneda set 
			mon_nombre = @i_mon_nombre
		where mon_id = @i_mon_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'D'
	begin
		update tm_moneda set 
			mon_estado = @i_mon_estado
		where mon_id = @i_mon_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'S'
		begin
			select * from tm_moneda
			where mon_estado = 1
			and suc_id = @i_suc_id
		end
		if @i_tipo = 'I'
		begin
			select * from tm_moneda
			where mon_estado = 1
			and mon_id = @i_mon_id
		end
	end

end
