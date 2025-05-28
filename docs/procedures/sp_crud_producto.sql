USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_producto]    Script Date: 28/5/2025 18:50:36 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_producto] (
 @i_operacion char(1),
 @i_tipo char(1) = null,
 @i_prod_id tinyint = null,
 @i_suc_id tinyint = null,
 @i_cat_id tinyint = null,
 @i_prod_nombre varchar(60) = null,
 @i_prod_descripcion varchar(60) = null,
 @i_unm_id tinyint = 1,
 @i_mon_id tinyint = 1,
 @i_prod_pcompra decimal(12,4) = null,
 @i_prod_pventa decimal(12,2) = null,
 @i_prod_stock int = null,
 @i_prod_fechaven datetime = null,
 @i_prod_img varchar(200) = null,
 @i_prod_cod_barra varchar(70) = null,
 @i_prod_tipo char(1) = null,
 @i_prod_estado tinyint = 1
)
as
begin
	if @i_operacion = 'C'
	begin
		insert into tm_producto
		(suc_id, cat_id, prod_nombre, prod_descripcion, unm_id, mon_id, prod_pcompra, prod_pventa, prod_stock, 
		prod_fechaven, prod_img,	prod_cod_barra, prod_tipo_producto, prod_fechacrea, prod_estado)
		values
		(@i_suc_id, @i_cat_id, @i_prod_nombre, @i_prod_descripcion, @i_unm_id, @i_mon_id, @i_prod_pcompra, @i_prod_pventa, @i_prod_stock, 
		@i_prod_fechaven, @i_prod_img, @i_prod_cod_barra, @i_prod_tipo, GETDATE(), 1)
	end

	if @i_operacion = 'U'
	begin
		update tm_producto set 
			cat_id = @i_cat_id,
			prod_nombre = @i_prod_nombre,
			prod_descripcion = @i_prod_descripcion,
			unm_id = @i_unm_id,
			mon_id = @i_mon_id,
			prod_pcompra = @i_prod_pcompra,
			prod_pventa = @i_prod_pventa,
			prod_stock = @i_prod_stock,
			prod_fechaven = @i_prod_fechaven,
			prod_img = @i_prod_img,
			prod_cod_barra = @i_prod_cod_barra,
			prod_tipo_producto = @i_prod_tipo
		where prod_id = @i_prod_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'D'
	begin
		update tm_producto set 
			prod_estado = @i_prod_estado
		where prod_id = @i_prod_id 
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			SELECT 
			tm_producto.prod_id, tm_producto.suc_id, tm_producto.cat_id, tm_producto.prod_nombre, tm_producto.prod_descripcion, tm_producto.unm_id, tm_producto.mon_id, tm_producto.prod_pcompra, tm_producto.prod_pventa, 
			tm_producto.prod_stock, tm_producto.prod_fechaven, tm_producto.prod_img, tm_producto.prod_fechacrea, tm_producto.prod_estado, tm_producto.prod_cod_barra, tm_producto.prod_tipo_producto, tm_categoria.cat_nombre, 
			tm_categoria.cat_id AS Expr1, tm_unidad.unm_id AS Expr2, tm_unidad.unm_nombre, tm_moneda.mon_id AS Expr3, tm_moneda.mon_nombre
			FROM tm_producto INNER JOIN
			tm_categoria ON tm_producto.cat_id = tm_categoria.cat_id INNER JOIN
			tm_unidad ON tm_producto.unm_id = tm_unidad.unm_id INNER JOIN
			tm_moneda ON tm_producto.mon_id = tm_moneda.mon_id
			where prod_estado = @i_prod_estado
		end
		if @i_tipo = 'I'
		begin
			SELECT 
			tm_producto.prod_id, tm_producto.suc_id, tm_producto.cat_id, tm_producto.prod_nombre, tm_producto.prod_descripcion, tm_producto.unm_id, tm_producto.mon_id, tm_producto.prod_pcompra, tm_producto.prod_pventa, 
			tm_producto.prod_stock, tm_producto.prod_fechaven, tm_producto.prod_img, tm_producto.prod_fechacrea, tm_producto.prod_estado, tm_producto.prod_cod_barra, tm_producto.prod_tipo_producto, tm_categoria.cat_nombre, 
			tm_categoria.cat_id AS Expr1, tm_unidad.unm_id AS Expr2, tm_unidad.unm_nombre, tm_moneda.mon_id AS Expr3, tm_moneda.mon_nombre
			FROM tm_producto INNER JOIN
			tm_categoria ON tm_producto.cat_id = tm_categoria.cat_id INNER JOIN
			tm_unidad ON tm_producto.unm_id = tm_unidad.unm_id INNER JOIN
			tm_moneda ON tm_producto.mon_id = tm_moneda.mon_id
			where prod_estado = @i_prod_estado
			and prod_id = @i_prod_id
			and tm_producto.suc_id = @i_suc_id
		end
		if @i_tipo = 'N'
		begin
			SELECT 
			tm_producto.prod_id, tm_producto.suc_id, tm_producto.cat_id, tm_producto.prod_nombre, tm_producto.prod_descripcion, tm_producto.unm_id, tm_producto.mon_id, tm_producto.prod_pcompra, tm_producto.prod_pventa, 
			tm_producto.prod_stock, tm_producto.prod_fechaven, tm_producto.prod_img, tm_producto.prod_fechacrea, tm_producto.prod_estado, tm_producto.prod_cod_barra, tm_producto.prod_tipo_producto, tm_categoria.cat_nombre, 
			tm_categoria.cat_id AS Expr1, tm_unidad.unm_id AS Expr2, tm_unidad.unm_nombre, tm_moneda.mon_id AS Expr3, tm_moneda.mon_nombre
			FROM tm_producto INNER JOIN
			tm_categoria ON tm_producto.cat_id = tm_categoria.cat_id INNER JOIN
			tm_unidad ON tm_producto.unm_id = tm_unidad.unm_id INNER JOIN
			tm_moneda ON tm_producto.mon_id = tm_moneda.mon_id
			where prod_estado = @i_prod_estado
			and CONCAT (prod_nombre,' ',prod_descripcion) like '%'+@i_prod_nombre+'%'
			and tm_producto.suc_id = @i_suc_id
		end
		if @i_tipo = 'C'
		begin
			SELECT 
			tm_producto.prod_id, tm_producto.suc_id, tm_producto.cat_id, tm_producto.prod_nombre, tm_producto.prod_descripcion, tm_producto.unm_id, tm_producto.mon_id, tm_producto.prod_pcompra, tm_producto.prod_pventa, 
			tm_producto.prod_stock, tm_producto.prod_fechaven, tm_producto.prod_img, tm_producto.prod_fechacrea, tm_producto.prod_estado, tm_producto.prod_cod_barra, tm_producto.prod_tipo_producto, tm_categoria.cat_nombre, 
			tm_categoria.cat_id AS Expr1, tm_unidad.unm_id AS Expr2, tm_unidad.unm_nombre, tm_moneda.mon_id AS Expr3, tm_moneda.mon_nombre
			FROM tm_producto INNER JOIN
			tm_categoria ON tm_producto.cat_id = tm_categoria.cat_id INNER JOIN
			tm_unidad ON tm_producto.unm_id = tm_unidad.unm_id INNER JOIN
			tm_moneda ON tm_producto.mon_id = tm_moneda.mon_id
			where prod_estado = @i_prod_estado
			and prod_cod_barra = @i_prod_cod_barra
			and tm_producto.suc_id = @i_suc_id
		end
		if @i_tipo = 'S'
		begin
			SELECT 
			tm_producto.prod_id, 
			tm_producto.suc_id, 
			tm_producto.cat_id, 
			tm_producto.prod_nombre, 
			tm_producto.prod_descripcion, 
			tm_producto.unm_id, 
			tm_producto.mon_id, 
			tm_producto.prod_pcompra, 
			tm_producto.prod_pventa, 
			tm_producto.prod_stock, 
			tm_producto.prod_fechaven, 
			isnull(tm_producto.prod_img , 'no_image.png') as prod_img, 
			tm_producto.prod_fechacrea, 
			tm_producto.prod_estado, 
			tm_producto.prod_cod_barra, 
			tm_producto.prod_tipo_producto, 
			tm_categoria.cat_nombre, 
			tm_categoria.cat_id AS Expr1, 
			tm_unidad.unm_id AS Expr2, 
			tm_unidad.unm_nombre, 
			tm_moneda.mon_id AS Expr3, 
			tm_moneda.mon_nombre
			FROM tm_producto INNER JOIN
			tm_categoria ON tm_producto.cat_id = tm_categoria.cat_id INNER JOIN
			tm_unidad ON tm_producto.unm_id = tm_unidad.unm_id INNER JOIN
			tm_moneda ON tm_producto.mon_id = tm_moneda.mon_id
			where prod_estado = @i_prod_estado
			and tm_producto.suc_id = @i_suc_id
		end
	end

	if @i_operacion = 'S'
	begin
		SELECT
		tm_producto.prod_id, tm_producto.suc_id, tm_producto.cat_id, tm_producto.prod_nombre, tm_producto.prod_descripcion, tm_producto.unm_id, tm_producto.mon_id, tm_producto.prod_pcompra, tm_producto.prod_pventa, 
		tm_producto.prod_stock, tm_producto.prod_fechaven, tm_producto.prod_img, tm_producto.prod_fechacrea, tm_producto.prod_estado, tm_producto.prod_cod_barra, tm_producto.prod_tipo_producto, tm_categoria.cat_nombre, 
		tm_categoria.cat_id AS Expr1, tm_unidad.unm_id AS Expr2, tm_unidad.unm_nombre, tm_moneda.mon_id AS Expr3, tm_moneda.mon_nombre
		FROM tm_producto INNER JOIN
		tm_categoria ON tm_producto.cat_id = tm_categoria.cat_id INNER JOIN
		tm_unidad ON tm_producto.unm_id = tm_unidad.unm_id INNER JOIN
		tm_moneda ON tm_producto.mon_id = tm_moneda.mon_id
		where prod_estado = @i_prod_estado
		and tm_producto.suc_id = @i_suc_id
		and tm_producto.cat_id = @i_cat_id
	end

end
