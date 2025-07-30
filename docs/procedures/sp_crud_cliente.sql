USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_cliente]    Script Date: 28/7/2025 23:21:21 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_cliente] (
 @i_operacion char(1) ,
 @i_tipo char(1)= null,
 @i_emp_id int = null,
 @i_cli_id int = null,
 @i_cli_nombre varchar(50) = null,
 @i_cli_ruc varchar(50) = null,
 @i_cli_telefono varchar(50) = null,
 @i_cli_direccion varchar(50) = null,
 @i_cli_correo varchar(50) = null,
 @i_cli_estado tinyint = 1
)
as
declare 
@w_monto_cuenta decimal(14,2),
@w_cta_id int ,
@w_val_pedido_abo decimal(16,2),
@w_val_pedido decimal(16,2),
@w_val_ventas decimal(16,2)

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
			
			set @w_cta_id = 0
			set @w_val_pedido_abo = 0
			set @w_val_pedido = 0
			set @w_val_ventas = 0

			if exists (select 1 from tm_cuenta_cliente where cli_id = @i_cli_id and cta_estado = 1)
			begin
				select @w_monto_cuenta = cta_monto, @w_cta_id= cta_id from tm_cuenta_cliente where cli_id = @i_cli_id and cta_estado = 1
				-- SE OBTIENEN VALORES DE PEDIDOS Y VENTAS
				select  @w_val_pedido_abo = sum(movc_valor) from tm_salida_lote s 
				inner join tm_movimiento_cuenta mc on mc.salida_id = s.salida_id
				where s.cli_id = @i_cli_id
				and s.salida_estado = 1 
				and salida_vpagado not in ('C')
				and movc_tipo = '-'
				and mc.movc_estado = 1

				select  @w_val_pedido = sum(movc_valor) from tm_salida_lote s 
				inner join tm_movimiento_cuenta mc on mc.salida_id = s.salida_id
				where s.cli_id = @i_cli_id
				and s.salida_estado = 1 
				and salida_vpagado not in ('C')
				and movc_tipo = '+'
				and mc.movc_estado = 1

				set @w_val_pedido = ISNULL(@w_val_pedido, 0) - isnull(@w_val_pedido_abo ,0)

				-- VALOR PENDIENTE VENTAS
				select @w_val_ventas = sum(rvc_monto - rvc_abonado) from tm_ventas v
				inner join tm_registro_vencred vc on vc.ven_id = v.ven_id
				inner join tm_movimiento_cuenta mc on mc.ven_id = v.ven_id
				where v.cli_id = @i_cli_id
				and vc.rvc_estado = 1
				and vc.rvc_est_cta not in ('C')
				and mc.movc_estado = 1

			end

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
				emp_id,
				isnull(@w_val_pedido, 0.00) as 'total_pedidos',
				isnull(@w_val_ventas, 0.00) as 'total_ventas'
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
			where cli_id not in (select cli_id from tm_cuenta_cliente where cta_estado = 1) 
			and cli_estado = 1
		end
	end

end
