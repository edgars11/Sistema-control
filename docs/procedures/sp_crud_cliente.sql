USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_cliente]    Script Date: 2/3/2025 9:58:52 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_cliente] (
 @i_operacion char(1) ,
 @i_tipo char(1)= null,
 @i_emp_id int = null,
 @i_cli_id tinyint = null,
 @i_cli_nombre varchar(50) = null,
 @i_cli_ruc varchar(50) = null,
 @i_cli_telefono varchar(50) = null,
 @i_cli_direccion varchar(50) = null,
 @i_cli_correo varchar(50) = null,
 @i_cli_estado tinyint = 1
)
as
declare 
@w_monto_cuenta decimal(14,2)
begin
	if @i_operacion = 'C'
	begin
		insert into tm_cliente 
		(emp_id, cli_nombre, cli_ruc, cli_telefono, cli_direccion, cli_correo, cli_fecha_crea, cli_estado)
		values
		(@i_emp_id, @i_cli_nombre, @i_cli_ruc, @i_cli_telefono, @i_cli_direccion, @i_cli_correo, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_cliente set 
			cli_nombre = @i_cli_nombre,
			cli_ruc	= @i_cli_ruc,
			cli_telefono = @i_cli_telefono,
			cli_direccion = @i_cli_direccion,
			cli_correo = @i_cli_correo
		where cli_id = @i_cli_id
		and emp_id = @i_emp_id
	end

	if @i_operacion = 'D'
	begin
		update tm_cliente set 
			cli_estado = @i_cli_estado
		where cli_id = @i_cli_id
		and emp_id = @i_emp_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			select * from tm_cliente
			where cli_estado = @i_cli_estado
		end
		if @i_tipo = 'E'
		begin

			select * 
			from tm_cliente
			where cli_estado = @i_cli_estado
			and emp_id = @i_emp_id
		end
		if @i_tipo = 'I'
		begin
			select @w_monto_cuenta = cta_monto from tm_cuenta_cliente where cli_id = @i_cli_id
			select 
				cli_id,
				cli_nombre,
				cli_ruc,
				cli_telefono,
				cli_direccion,
				cli_correo,
				cli_fecha_crea,
				cli_estado,
				ISNULL(@w_monto_cuenta,0.00) as cta_monto,
				emp_id
			from tm_cliente
			where cli_estado = @i_cli_estado
			and cli_id = @i_cli_id

		end
		if @i_tipo = 'C'
		begin
			select * from tm_cliente
			where cli_estado = @i_cli_estado
			and emp_id = @i_emp_id
			and cli_ruc = @i_cli_ruc
		end
		if @i_tipo = 'N'
		begin
			select * from tm_cliente
			where cli_estado = @i_cli_estado
			and emp_id = @i_emp_id
			and cli_nombre like '%'+@i_cli_nombre+'%'
		end
		if @i_tipo = 'W'
		begin
			select * 
			from tm_cliente
			where cli_id not in (select cli_id from tm_cuenta_cliente) 
			and cli_estado = 1
		end
	end

end
