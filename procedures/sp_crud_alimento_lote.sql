USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_categoria]    Script Date: 4/2/2025 19:39:39 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sp_crud_alimento_lote] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_ali_id int = null,
 @i_lote_id tinyint = null,
 @i_user_id tinyint = null,
 @i_ali_cantidad varchar(75) = null,
 @i_ali_desc varchar(100) = null,
 @i_ali_fecha varchar(75) = null,
 @i_ali_hora varchar(75) = null
)
as
declare 
@w_hora  varchar(75)
begin
	if @i_operacion = 'C'
	begin
		select @w_hora = getdate()

		insert into tm_alimento_lote 
		(lote_id,	ali_cantidad,	ali_fecha,	ali_hora,	user_id,	ali_desc,	ali_estado)
		values
		(@i_lote_id, @i_ali_cantidad,  @i_ali_fecha, @w_hora, @i_user_id, @i_ali_desc, 1 )

		update tm_lote 
		set lote_consumo = isnull(lote_consumo, 0) + @i_ali_cantidad
		where lote_id = @i_lote_id

	end

	return 0
end
