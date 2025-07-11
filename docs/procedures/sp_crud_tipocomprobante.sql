USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_tipocomprobante]    Script Date: 8/7/2025 20:06:26 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_tipocomprobante] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_tc_descripcion varchar(100) = null,
 @i_tc_id tinyint = null,
 @i_pago_id tinyint = null,
 @i_tc_codigo varchar(3) = null,
 @i_tc_estado tinyint = null
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_tipo_comprobante 
		(tc_descripcion, tc_codigo, tc_fecha_crea, tc_estado)
		values
		(@i_tc_descripcion, @i_tc_codigo, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_tipo_comprobante set 
			tc_codigo = @i_tc_codigo
		where tc_id = @i_tc_id
		and tc_descripcion = @i_tc_descripcion
	end

	if @i_operacion = 'D'
	begin
		update tm_tipo_comprobante set 
			tc_estado = @i_tc_estado
		where tc_id = @i_tc_id
		and tc_descripcion = @i_tc_descripcion
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			select * from tm_tipo_comprobante
			where tc_estado = 'A'			
		end
		if @i_tipo = 'S'
		begin
			select 
			tc_id,
			tc_descripcion,
			tc_codigo,
			CONVERT(varchar, tc_fecha_crea , 103) as tc_fecha_crea,
			tc_estado
			from tm_tipo_comprobante
			where tc_estado = 1
		end
		if @i_tipo = 'I'
		begin
			select * from tm_tipo_comprobante
			where tc_estado = 1
			and tc_id = @i_tc_id
		end

		if @i_tipo = 'P'
		begin
			select * from tm_tipo_pago
			where pago_estado = 1
			and pago_id = @i_pago_id
		end
	end

end
