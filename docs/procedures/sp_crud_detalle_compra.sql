USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_detalle_compra]    Script Date: 28/5/2025 18:46:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_detalle_compra] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_comp_id int = null,
 @i_prod_id int = null,
 @i_detc_id int = null,
 @i_prod_pcompra numeric(18,4) = null,
 @i_detc_cant decimal(5,0) = null,
 @i_detc_total decimal(18,2) = null,
 @i_detc_estado tinyint = null
)
as
begin
set nocount on
	-- CREA EL NUEVO DETALLE DE LA COMPRA
	if @i_operacion = 'C'
	begin
		insert into tm_detalle_compra 
		(prod_id,	prod_pcompra,	detc_cant,	detc_total,	detc_fecha_crea,	detc_estado,	comp_id)
		values
		(@i_prod_id, @i_prod_pcompra, @i_detc_cant, @i_detc_total, GETDATE(), 1 , @i_comp_id)

		exec sp_crud_compra @i_operacion = 'T', @i_comp_id= @i_comp_id

	end
	-- LISTA TODO EL DETALLE DE LA COMPRA POR ID
	if @i_operacion = 'L'
	begin
		SELECT       
			tm_detalle_compra.detc_id, 
			tm_detalle_compra.prod_id, 
			tm_detalle_compra.detc_cant, 
			tm_detalle_compra.detc_total, 
			tm_detalle_compra.comp_id, 
			tm_producto.prod_nombre, 
			tm_producto.prod_descripcion, 
			tm_categoria.cat_id, 
			tm_categoria.cat_nombre, 
			tm_unidad.unm_nombre, 
			tm_producto.prod_stock, 
			tm_detalle_compra.detc_fecha_crea, 
			tm_detalle_compra.detc_estado, 
			tm_detalle_compra.prod_pcompra
		FROM    tm_detalle_compra INNER JOIN
				tm_producto ON tm_detalle_compra.prod_id = tm_producto.prod_id INNER JOIN
				tm_categoria ON tm_producto.cat_id = tm_categoria.cat_id INNER JOIN
				tm_unidad ON tm_producto.unm_id = tm_unidad.unm_id
		WHERE	tm_detalle_compra.comp_id =  @i_comp_id
		and		tm_detalle_compra.detc_estado = 1

	end
	-- ELIMINA UN ITEM DE LA COMPRA
	if @i_operacion = 'D'
	begin
		update tm_detalle_compra 
		set detc_estado = 0
		where detc_id = @i_detc_id

		exec sp_crud_compra @i_operacion = 'T', @i_comp_id= @i_comp_id
	end

set nocount off
end
