USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_alimento_lote]    Script Date: 7/2/2025 19:56:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_alimento_lote] (
 @i_operacion char(1) ,
 @i_tipo char(2) = null,
 @i_ali_id int = null,
 @i_lote_id int = null,
 @i_user_id tinyint = null,
 @i_suc_id int = null,
 @i_ali_cantidad varchar(75) = null,
 @i_ali_desc varchar(100) = null,
 @i_ali_fecha varchar(75) = null,
 @i_ali_hora varchar(75) = null,
 @i_fecha_desde varchar(75) = null,
 @i_fecha_hasta varchar(75) = null
)
as
declare 
@w_hora  varchar(75),
@w_exec varchar(10),
@w_cantidad_ant int,
@w_lote_id int
begin
	if @i_operacion = 'C'
	begin
		select @w_hora = getdate()

		insert into tm_alimento_lote 
		(lote_id,	ali_cantidad,	ali_fecha,	ali_hora,	user_id,	ali_desc,	ali_estado)
		values
		(@i_lote_id, @i_ali_cantidad,  @i_ali_fecha, @w_hora, @i_user_id, @i_ali_desc, 1 )

		update tm_lote 
		set lote_consumo = isnull(lote_consumo, 0) + @i_ali_cantidad,
		lote_fecha_upd = @w_hora
		where lote_id = @i_lote_id
	end

	if @i_operacion = 'U'
	begin
		select @w_hora = getdate()

		select @w_cantidad_ant = ali_cantidad
		from tm_alimento_lote where ali_id = @i_ali_id

		update tm_alimento_lote 
		set lote_id = @i_lote_id ,	
		ali_cantidad = @i_ali_cantidad,	ali_fecha = @i_ali_fecha ,	ali_hora = @w_hora,
		user_id = @i_user_id ,	ali_desc = @i_ali_desc
		where ali_id = @i_ali_id

		update tm_lote 
		set lote_consumo = (isnull(lote_consumo, 0) - @w_cantidad_ant) + @i_ali_cantidad,
		lote_fecha_upd = @w_hora
		where lote_id = @i_lote_id
	end

	if @i_operacion = 'D'
	begin
		select @w_hora = getdate()

		select @w_cantidad_ant = ali_cantidad
			,@w_lote_id = lote_id	
		from tm_alimento_lote where ali_id = @i_ali_id

		update tm_alimento_lote 
		set ali_estado = 0
		where ali_id = @i_ali_id

		update tm_lote 
		set lote_consumo = (isnull(lote_consumo, 0) - @w_cantidad_ant),
		lote_fecha_upd = @w_hora
		where lote_id = @w_lote_id
	end

	if @i_operacion = 'R'
	begin
		
		if @i_tipo = 'TH'
		begin
			select 
				al.ali_id,
				l.lote_descripcion,
				al.ali_fecha,
				al.ali_cantidad,
				concat(u.usu_nombre, ' ', u.usu_apellido) as usu_nombre,
				al.ali_desc,
				al.ali_hora
			from
			tm_alimento_lote al
			inner join tm_lote l on l.lote_id = al.lote_id
			inner join tm_usuario u on u.usu_id = al.user_id
			where l.suc_id = @i_suc_id
			and ali_estado = 1
			and CAST(al.ali_fecha as date) = @i_fecha_desde
		end
		if @i_tipo = 'TF'
		begin
			select 
				al.ali_id,
				l.lote_descripcion,
				al.ali_fecha,
				al.ali_cantidad,
				concat(u.usu_nombre, ' ', u.usu_apellido) as usu_nombre,
				al.ali_desc,
				al.ali_hora
			from
			tm_alimento_lote al
			inner join tm_lote l on l.lote_id = al.lote_id
			inner join tm_usuario u on u.usu_id = al.user_id
			where l.suc_id = @i_suc_id
			and ali_estado = 1
			and al.lote_id = isnull(@i_lote_id, al.lote_id)
			and CAST(al.ali_fecha as date) between @i_fecha_desde and @i_fecha_hasta
			order by al.ali_fecha desc
		end
		if @i_tipo = 'TI'
		begin
			select 
				ali_id,
				lote_id,
				ali_cantidad,
				ali_fecha,
				ali_desc,
				ali_hora,
				(select lote_consumo from tm_lote where lote_id = al.lote_id) as total_consumo_lote
			from tm_alimento_lote al
			where al.ali_id = @i_ali_id
		end

	end

	return 0
end
