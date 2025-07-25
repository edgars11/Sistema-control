USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_update_stock]    Script Date: 28/5/2025 18:53:47 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER procedure [dbo].[sp_update_stock](
	@i_operacion char(2) ,
	@i_comp_id int = null,
	@i_ven_id int = null
)
as
begin

declare @w_total_detalle int
declare @w_contador int
declare @w_id_prod int
declare @w_cantidad int

set nocount on

	if @i_operacion = 'SC'
	begin
		select 
			@w_total_detalle = MAX(detc_id),
			@w_contador = MIN(detc_id)
		from tm_detalle_compra where comp_id = @i_comp_id

		while( @w_contador<=@w_total_detalle)
		begin
			select @w_id_prod = prod_id,
					@w_cantidad = detc_cant
			from tm_detalle_compra
			where comp_id= @i_comp_id
			and detc_id = @w_contador

			-- print 'id producto: ' + convert(varchar(10), @w_id_prod) + ' - Cantidad: ' + convert(varchar(10), @w_cantidad)

			update tm_producto
			set prod_stock = prod_stock + @w_cantidad
			where prod_id = @w_id_prod

			set @w_contador= @w_contador+1
		end
	end

	if @i_operacion = 'SV'
	begin
		select 
			@w_total_detalle = MAX(detv_id),
			@w_contador = MIN(detv_id)
		from tm_detalle_venta where ven_id = @i_ven_id

		while( @w_contador<=@w_total_detalle)
		begin
			select @w_id_prod = prod_id,
					@w_cantidad = detv_cantidad
			from tm_detalle_venta
			where ven_id= @i_ven_id
			and detv_id = @w_contador

			-- print 'id producto: ' + convert(varchar(10), @w_id_prod) + ' - Cantidad: ' + convert(varchar(10), @w_cantidad)

			update tm_producto
			set prod_stock = prod_stock - @w_cantidad
			where prod_id = @w_id_prod

			set @w_contador = @w_contador + 1
		end
	end

end
