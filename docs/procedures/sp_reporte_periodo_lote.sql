USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_reporte_periodo_lote]    Script Date: 18/3/2025 21:39:47 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_reporte_periodo_lote] (
 @i_operacion char(1) ,
 @i_tipo char(2) = null,
 @i_suc_id int = null,
 @i_lote_id tinyint = null,
 @i_fecha_periodo varchar(75) = null,
 @i_fecha_fin varchar(75) = null,
 @i_anio_periodo int = null
)
as
declare 
@w_fecha_fin_ult varchar(75) 
begin
	set nocount on

	if @i_operacion = 'L'
	begin
		if @i_tipo = 'U'
		begin
			select 
				ml.lote_id,
				l.lote_descripcion,
				max(mov_fecha) as periodo
			from tm_movimiento_lote ml
			inner join tm_lote l
			on l.lote_id = ml.lote_id
			where l.lote_id = isnull(@i_lote_id, l.lote_id)
			and l.suc_id = @i_suc_id
			group by ml.lote_id, l.lote_descripcion, l.lote_cant_actual
			order by l.lote_descripcion
		end

		if @i_tipo = 'L'
		begin
			select 
				ml.lote_id,
				l.lote_descripcion,
				mov_fecha as periodo
			from tm_movimiento_lote ml
			inner join tm_lote l
			on l.lote_id = ml.lote_id
			where l.lote_id = isnull(@i_lote_id, l.lote_id)
			and YEAR(mov_fecha)= @i_anio_periodo
			and l.suc_id = @i_suc_id
			and mov_tipo = '+'
			group by ml.lote_id, l.lote_descripcion,mov_fecha
			order by l.lote_descripcion
		end

		if @i_tipo = 'F'
		begin
			select top 1 
			@i_fecha_periodo as fecha_ini,
			mov_fecha as fecha_fin
			from tm_movimiento_lote 
			where lote_id = @i_lote_id
			and mov_fecha > @i_fecha_periodo
			and mov_tipo = '+'
			order by mov_fecha 
		end
	end

	if @i_operacion = 'R'
	begin
		truncate table tm_reporte_lote

		print 'Se inserta registros de salida'
		-- Se insertan los registros de salida en la tabla de reporte
		insert into tm_reporte_lote
		(lote_id , repor_tipo_pro , repor_cantidad, repor_peso_neto, repor_total, repor_fecha_reg ,repor_fecha_ing,repor_consumo, suc_id ,repor_perdida )
		select 
		 lote_id, salida_tipo, salida_cantidad, salida_peso_neto, salida_total, salida_fecha , GETDATE(),0 ,@i_suc_id ,0
		from tm_salida_lote
		where lote_id = @i_lote_id
		and salida_fecha between @i_fecha_periodo and @i_fecha_fin
		order by salida_fecha

		-- Se insertan los registros de consumo en la tabla de reporte
		print 'Se inserta registros de consumo lote'
		insert into tm_reporte_lote
			(lote_id , repor_consumo, repor_fecha_reg ,repor_fecha_ing , repor_cantidad, repor_peso_neto, repor_total, suc_id, repor_perdida )
		select 
			lote_id , ali_cantidad, ali_fecha , GETDATE(), 0, 0, 0, @i_suc_id, 0
		from tm_alimento_lote 
		where lote_id = @i_lote_id
		and ali_fecha between @i_fecha_periodo and @i_fecha_fin
		and ali_estado = 1
		order by ali_fecha

		-- Se ingresa la cantidad de perdida de lote
		insert into tm_reporte_lote
			(lote_id , repor_perdida, repor_fecha_reg ,repor_fecha_ing , repor_cantidad, repor_peso_neto, repor_total, suc_id, repor_consumo )
		select 
			lote_id, mov_cantidad, mov_fecha , GETDATE(),0 ,0 ,0 ,@i_suc_id ,0
		from tm_movimiento_lote
		where lote_id = @i_lote_id
		and mov_fecha between @i_fecha_periodo and @i_fecha_fin
		and suc_id = @i_suc_id
		and mov_tipo = '-'

		print 'Se retorna la lista de reporte'
		-- Retorna la lista de reporte generado para el Lote
		select 
			l.lote_descripcion as lote,
			CONVERT(varchar, repor_fecha_reg , 23)  as fecha, 
			sum(repor_cantidad) as cantidad , 
			sum(repor_peso_neto) as peso_neto, 
			sum(repor_total) as totalMonto , 
			sum(repor_consumo) as consumo,
			sum(repor_perdida) as perdida
		from tm_reporte_lote rl 
		inner join tm_lote l on l.lote_id = rl.lote_id
		group by repor_fecha_reg ,l.lote_descripcion
		order by repor_fecha_reg asc
	end

	if @i_operacion = 'T'
	begin
		select  
			sum(repor_cantidad) as cantidad , 
			sum(repor_peso_neto) as peso_neto, 
			sum(repor_total) as totalMonto , 
			sum(repor_consumo) as consumo,
			sum(repor_perdida) as perdida
		from tm_reporte_lote 
		where lote_id = @i_lote_id
		and suc_id = @i_suc_id
	end

	set nocount off
end
