USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_lote]    Script Date: 8/2/2025 10:50:20 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_lote] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_suc_id int = null,
 @i_lote_id tinyint = null,
 @i_lote_descripcion varchar(75) = null,
 @i_lote_capacidad_max int = null,
 @i_lote_consumo int = null,
 @i_lote_cant_actual int = null,
 @i_lote_costo decimal(14,2) = null,
 @i_lote_cant_vendidos int = null,
 @i_lote_lib_vendidos decimal(12,2) = null,
 @i_lote_cant_perdida int = null
)
as
declare 
@w_commit char(1),
@w_error int
begin

set @w_commit = 'N'
set @w_error = 0

if @w_commit = 'N' and  @@TRANCOUNT = 0
begin
	select @w_commit = 'S'
	begin tran
end

	if @i_operacion = 'C'
	begin
		insert into tm_lote 
		( lote_descripcion, lote_capacidad_max, lote_consumo,lote_cant_actual,lote_estado,lote_costo,lote_cant_vendidos, lote_lib_vendidos,lote_cant_perdida, lote_fecha_upd, suc_id)
		values
		(@i_lote_descripcion, @i_lote_capacidad_max, 0, 0, 1, 0, 0, 0, 0, getdate(), @i_suc_id)

		if @@ERROR <> 0
		begin
			select @w_error = 100
			goto ERRORFIN
		end
		select @w_error as ejecucion
	end

	if @i_operacion = 'U'
	begin
		update tm_lote set 
			lote_descripcion = @i_lote_descripcion,
			lote_capacidad_max = @i_lote_capacidad_max
		where lote_id = @i_lote_id
		and suc_id = @i_suc_id

		if @@ERROR <> 0
		begin
			select @w_error = 100
			goto ERRORFIN
		end
		select @w_error as ejecucion
	end

	if @i_operacion = 'D'
	begin
		update tm_lote set 
			lote_estado = 0
		where lote_id = @i_lote_id
		and suc_id = @i_suc_id
	end

	if @i_operacion = 'R'
	begin
		if @i_tipo = 'T'
		begin
			select * from tm_lote
			where lote_estado = 1
			and suc_id = @i_suc_id
		end
		if @i_tipo = 'S'
		begin
			select 
			lote_id,
			suc_id,
			lote_descripcion,
			lote_capacidad_max,
			lote_cant_actual,
			lote_cant_perdida,
			lote_consumo,
			CONVERT(varchar, lote_fecha_upd , 22) as lote_fecha_upd,
			lote_estado,
			(select top 1 mov_fecha from tm_movimiento_lote where lote_id = l.lote_id 
			and mov_tipo = '+' order by mov_fecha desc) as ult_fecha_ingre
			from tm_lote l
			where lote_estado = 1
			and suc_id = @i_suc_id
			order by lote_descripcion asc
		end
		if @i_tipo = 'I'
		begin
			select * from tm_lote
			where lote_estado = 1
			and lote_id = @i_lote_id
		end
	end

	if @w_commit = 'S' and @@TRANCOUNT > 0
	begin
		select @w_commit = 'N'
		commit tran
	end

	ERRORFIN:
	if @w_error > 0
	begin
		if @w_commit = 'S' and @@TRANCOUNT > 0
		begin
			select @w_commit = 'N'
			rollback tran
		end
		select @w_error as ejecucion
	end
end
