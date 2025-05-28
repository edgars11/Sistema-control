USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_detalle_venta]    Script Date: 28/5/2025 18:47:40 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_detalle_venta] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_ven_id int = null,
 @i_prod_id int = null,
 @i_detv_id int = null,
 @i_detv_precio numeric(18,4) = null,
 @i_detv_cant decimal(5,0) = null,
 @i_detv_total decimal(18,2) = null,
 @i_detv_estado tinyint = null
)
as
begin
set nocount on
	-- CREA EL NUEVO DETALLE DE LA COMPRA
	if @i_operacion = 'C'
	begin
		insert into tm_detalle_venta 
		(prod_id,	detv_precio,	detv_cantidad,	detv_total,	detv_fecha_crea,	detv_estado,	ven_id)
		values
		(@i_prod_id, @i_detv_precio, @i_detv_cant, @i_detv_total, GETDATE(), 1 , @i_ven_id)

		exec sp_crud_venta @i_operacion = 'T', @i_ven_id= @i_ven_id

	end
	-- LISTA TODO EL DETALLE DE LA COMPRA POR ID
	if @i_operacion = 'L'
	begin
		SELECT       
			tm_detalle_venta.detv_id, 
			tm_detalle_venta.prod_id, 
			tm_detalle_venta.detv_cantidad, 
			tm_detalle_venta.detv_total, 
			tm_detalle_venta.ven_id, 
			tm_producto.prod_nombre, 
			tm_producto.prod_descripcion, 
			tm_categoria.cat_id, 
			tm_categoria.cat_nombre, 
			tm_unidad.unm_nombre, 
			tm_producto.prod_stock, 
			tm_detalle_venta.detv_fecha_crea, 
			tm_detalle_venta.detv_estado, 
			tm_detalle_venta.detv_precio
		FROM    tm_detalle_venta INNER JOIN
				tm_producto ON tm_detalle_venta.prod_id = tm_producto.prod_id INNER JOIN
				tm_categoria ON tm_producto.cat_id = tm_categoria.cat_id INNER JOIN
				tm_unidad ON tm_producto.unm_id = tm_unidad.unm_id
		WHERE	tm_detalle_venta.ven_id =  @i_ven_id
		and		tm_detalle_venta.detv_estado = 1

	end
	-- ELIMINA UN ITEM DE LA COMPRA
	if @i_operacion = 'D'
	begin
		update tm_detalle_venta 
		set detv_estado = 0
		where detv_id = @i_detv_id

		exec sp_crud_venta @i_operacion = 'T', @i_ven_id= @i_ven_id
	end

set nocount off
end
