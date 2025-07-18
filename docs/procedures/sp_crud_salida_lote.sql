USE [SistemaControl]
GO
/****** Object:  StoredProcedure [dbo].[sp_crud_salida_lote]    Script Date: 17/7/2025 17:22:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_crud_salida_lote] (
 @i_operacion char(1) ,
 @i_tipo char(1) = null,
 @i_suc_id int = null,
 @i_salida_id int = null,
 @i_lote_id int = null,
 @i_pago_id int = null,
 @i_salida_cantidad int = null,
 @i_salida_tipo varchar(5) = null,
 @i_salida_fecha varchar(75) = null, 
 @i_fecha_desde varchar(75) = null, 
 @i_fecha_hasta varchar(75) = null,
 @i_salida_peso decimal(12,2) = null,
 @i_salida_peso_neto decimal(12,2) = null,
 @i_salida_tara decimal(12,2) = null,
 @i_salida_precio decimal(14,2) = null,
 @i_salida_total decimal(14,2) = null,
 @i_usu_id tinyint = null,
 @i_year_report tinyint = null,
 @i_month_report tinyint = null,
 @i_cli_id tinyint = null
)
as
declare
@w_fecha varchar(75),
@w_cta_obs varchar(75),
@w_salida_id int,
@w_cta_cli int,
@w_lote_id int,
@w_cta_id int,
@w_cliente_id int,
@w_movc_id int,
@w_movc_id_ant int,
@w_cant_actual_upd decimal (14,2),
@w_nueva_cant_upd decimal (14,2),
@w_total_upd decimal (14,2),
@w_cantidad_sal int ,
@w_peso_total decimal (14,2),
@w_valor_total_act  decimal (14,2),
@w_movc_nuevo_val  decimal (14,2),
@w_monto_abonado  decimal (14,2),
@w_movc_val_actual  decimal (14,2),
@w_saldo_total_cta  decimal (14,2),
@w_exec tinyint,
@w_signo_ope char(1),
@w_salida_tipo char(2),
@w_total_registros tinyint,
@w_desc_forma_pago varchar(50),
@w_estado_pago char(1),
@w_cliente_camal tinyint ,
@w_cam_registro int,
@w_num_decimales int,
@w_val_pedido_abo decimal(16,2),
@w_val_pedido decimal(16,2)

begin
set nocount on

	set @w_num_decimales = 2

	if @i_operacion = 'C'
	begin
		select  @w_fecha = GETDATE()
		set @w_estado_pago = 'N'

		select @w_cliente_camal = par_int from tm_parametros where par_nemonico = 'CLICL'

		-- SE VALIDA LA FORMA DE PAGO
		select @w_desc_forma_pago = pago_nombre from tm_tipo_pago where pago_id = @i_pago_id
		if @w_desc_forma_pago <> 'CREDITO'
			set @w_estado_pago = 'C'

		insert into tm_salida_lote 
		(salida_fecha, 			lote_id, 			salida_cantidad, 		salida_peso, 		salida_tara,		
		salida_peso_neto, 		salida_precio,		salida_total,			salida_tipo, 		cli_id, 			
		usu_id,					salida_hora,		pago_id,				salida_vpagado)
		values
		(@i_salida_fecha,		@i_lote_id, 		@i_salida_cantidad, 	@i_salida_peso, 	@i_salida_tara, 	
		@i_salida_peso_neto, 	@i_salida_precio,	@i_salida_total, 		@i_salida_tipo,		@i_cli_id,			
		@i_usu_id,				@w_fecha,			@i_pago_id,				@w_estado_pago)

		select @w_salida_id = SCOPE_IDENTITY()

		-- SE VALIDA SI EL REGISTRO ES POR CAMAL Y SE LLENA LA TABLA 
		if @i_cli_id = @w_cliente_camal
		begin
			
			if not exists (select 1 from tm_registro_camal where cam_fecha = @i_salida_fecha and lote_id = @i_lote_id)
			begin			
				INSERT INTO tm_registro_camal
				(cam_cantidad,			cam_fecha,			lote_id,		salida_id,
				cam_registros,			cam_estado,			cam_hora)
				VALUES
				(@i_salida_cantidad,	@i_salida_fecha,	@i_lote_id,		@i_salida_id,
				0,						1,					@w_fecha)
			end
			else 
			begin 
				update tm_registro_camal
				set cam_cantidad = cam_cantidad + @i_salida_cantidad
				where cam_fecha = @i_salida_fecha
			end

		end

		if @i_salida_tipo = 'PV'
		begin
			update tm_lote 
			set lote_cant_actual = isnull(lote_cant_actual,0) - @i_salida_cantidad,
			lote_cant_vendidos = isnull(lote_cant_vendidos,0) + @i_salida_cantidad,
			lote_fecha_upd = @w_fecha
			where lote_id = @i_lote_id
		end

		if @i_salida_tipo = 'PF'
		begin
			update tm_registro_camal 
			set cam_registros = isnull(cam_registros,0) + @i_salida_cantidad
			where cam_fecha = @i_salida_fecha
		end
		-- SE VALIDA SI LA FORMA DE PAGO ES A CRÉDITO SE GUARDA EN AL CUENTA DEL CLIENTE
		if @w_estado_pago = 'N'
		begin
				-- OBSERVACION CUENTA CLIENE
			select @w_cta_obs = 'Pedido # ' + CONVERT(varchar, @w_salida_id)

			-- VALIDA SI EL CLIENTE TIENE CUENTA CREADA SINO SE CREA UNA NUEVA
			Select @w_cta_cli = cta_id from tm_cuenta_cliente where cli_id = @i_cli_id and suc_id = @i_suc_id and cta_estado = 1

			if ISNULL(@w_cta_cli,0) = 0
			begin
				exec sp_crud_cuenta_cli 
					@i_operacion = 'C',
					@i_cta_id	 = @w_cta_cli,
					@i_cli_id	 = @i_cli_id,
					@i_cta_monto = @i_salida_total,
					@i_cta_fecha = @w_fecha,
					@i_cta_obs	 = @w_cta_obs,
					@i_salida_id = @w_salida_id,
					@i_suc_id	 = @i_suc_id,
					@i_usu_id	 = @i_usu_id

			end 
			else if @w_cta_cli > 0
			begin
				exec sp_crud_cuenta_cli 
					@i_operacion = 'U', 
					@i_cta_id	 = @w_cta_cli,
					@i_cli_id	 = @i_cli_id,
					@i_cta_monto = @i_salida_total,
					@i_cta_fecha = @w_fecha,
					@i_cta_obs	 = @w_cta_obs,
					@i_salida_id = @w_salida_id,
					@i_suc_id	 = @i_suc_id,
					@i_usu_id	 = @i_usu_id,
					@i_movc_tipo = '+'
			end	
		end

	end

	if @i_operacion = 'U'
	begin
		select  @w_fecha = GETDATE()

		select 
			@w_cantidad_sal = salida_cantidad ,
		 	@w_valor_total_act = salida_total,
			@w_lote_id = lote_id,
			@w_cliente_id = cli_id,
			@w_salida_tipo = salida_tipo
		from tm_salida_lote
		where salida_id = @i_salida_id
		and salida_estado = 1

		update tm_salida_lote set
		salida_fecha = @i_salida_fecha, 
		lote_id = @i_lote_id, 			
		salida_cantidad = @i_salida_cantidad, 		
		salida_peso = @i_salida_peso, 		
		salida_tara = @i_salida_tara,		
		salida_peso_neto = @i_salida_peso_neto, 		
		salida_precio = @i_salida_precio,		
		salida_total = @i_salida_total,		
		salida_tipo = @i_salida_tipo, 			
		cli_id = @i_cli_id, 			
		usu_id = @i_usu_id,					
		salida_hora = @w_fecha
		where 
		salida_id = @i_salida_id
		and salida_estado = 1
		
		if @i_salida_tipo = 'PV'
		begin
			update tm_lote 
			set lote_cant_actual = (isnull(lote_cant_actual,0)+@w_cantidad_sal),
			lote_cant_vendidos = (isnull(lote_cant_vendidos,0)-@w_cantidad_sal),
			lote_fecha_upd = @w_fecha
			where lote_id = @w_lote_id

			update tm_lote 
			set lote_cant_actual = isnull(lote_cant_actual,0) - @i_salida_cantidad,
			lote_cant_vendidos = isnull(lote_cant_vendidos,0) + @i_salida_cantidad,
			lote_fecha_upd = @w_fecha
			where lote_id = @i_lote_id
		end


		if @i_salida_tipo = 'PF'
		begin
			update tm_registro_camal 
			set cam_cantidad = @i_salida_cantidad
			where salida_id = @i_salida_id and cam_estado = 1

		end
		-- OBSERVACION CUENTA CLIENTE
		select @w_cta_obs = 'Pedido modificado # ' + CONVERT(varchar, @i_salida_id)
		-- SE OBTIENE LA CUENTA DEL CLIENTE PARA LA ACTUALIZACIÓN
		Select @w_cta_cli = cta_id from tm_cuenta_cliente 
		where cli_id = @i_cli_id and suc_id = @i_suc_id and cta_estado = 1

		-- SE OBTIEN EL ID DE MOVIMIENTO DE LA CUENTA SEGÚN EL ID DE SALIDA Y CUENTA CLIENTE
		select @w_movc_id = movc_id from tm_movimiento_cuenta mc 
		where mc.salida_id = @i_salida_id and cta_id = @w_cta_cli and movc_estado = 1

		-- PARA LA MODIFICACIÓN SE VA A TOMAR EL ULTIMO SALDO REGISTRADO Y POSTERIOR CON ESE SALDO CALCULAR EL VALOR A MODIFICAR
		select top 1 @w_movc_val_actual = movc_nuevo_val from tm_movimiento_cuenta mc 
		where movc_id < @w_movc_id and cta_id = @w_cta_cli and movc_estado = 1 order by movc_id desc

		update tm_movimiento_cuenta 
		set movc_val_actual = @w_movc_val_actual,
		movc_valor = @i_salida_total,
		movc_nuevo_val = (@w_movc_val_actual + @i_salida_total),
		movc_obs = @w_cta_obs,
		movc_fecha = @w_fecha
		where salida_id = @i_salida_id
		and cta_id = @w_cta_cli
		and movc_estado = 1


		-- SE VALIDA SI EXISTEN MÁS REGISTROS DE SALIDA PARA MODIFICAR EL SALDO.
		select @w_total_registros = COUNT(1) from tm_movimiento_cuenta mc
		where cta_id = @w_cta_cli 
		and movc_estado = 1
		and mc.movc_id > @w_movc_id

		if @w_total_registros > 0
		begin 
			while(@w_total_registros > 0)
			begin
				-- se obtiene el nuevo monto del salida modificado
				select @w_monto_abonado = mc.movc_nuevo_val from tm_movimiento_cuenta mc 
				where movc_id = @w_movc_id and cta_id = @w_cta_cli and movc_estado = 1

				select top 1 @w_movc_id = movc_id, @w_signo_ope = movc_tipo ,@w_saldo_total_cta = movc_valor 
				from tm_movimiento_cuenta mc
				where cta_id = @w_cta_cli  
				and mc.movc_id > @w_movc_id
				and mc.movc_estado = 1
				order by movc_id, movc_fecha

				if @w_signo_ope = '+'
				begin
					set @w_movc_nuevo_val = @w_monto_abonado + @w_saldo_total_cta 
				end
				else if @w_signo_ope = '-'
				begin
					set @w_movc_nuevo_val = @w_monto_abonado - @w_saldo_total_cta 
				end

				update tm_movimiento_cuenta 
				set movc_val_actual = @w_monto_abonado,
				movc_nuevo_val = @w_movc_nuevo_val
				where movc_id = @w_movc_id
				and cta_id = @w_cta_cli
				and movc_estado = 1

				set @w_total_registros = @w_total_registros - 1
			end
		end

		update tm_cuenta_cliente
		set cta_monto = (cta_monto - @w_valor_total_act) + @i_salida_total,
		cta_fecha_upd = @w_fecha,
		cta_obs = @w_cta_obs
		where cta_id = @w_cta_cli
		and cta_estado = 1

	end

	if @i_operacion = 'D'
	begin

		select @w_fecha = GETDATE()
		select @w_cliente_camal = par_int from tm_parametros where par_nemonico = 'CLICL'

		-- SE OBTIENE DATOS PARA LA ACTUALIZACIÓN
		select 
			@w_cant_actual_upd = salida_cantidad,
			@w_total_upd = salida_total,
			@w_lote_id = lote_id,
			@w_salida_tipo = salida_tipo,
			@w_cliente_id = cli_id,
			@i_pago_id = pago_id
		from tm_salida_lote where salida_id =  @i_salida_id and salida_estado = 1

		select @w_desc_forma_pago = pago_nombre from tm_tipo_pago where pago_id = @i_pago_id

		-- SE ACTUALIZA LA CANTIDAD DISPONIBLE DEL LOTE
		if @w_salida_tipo = 'PV'
		begin
			update tm_lote 
			set lote_cant_actual = lote_cant_actual + @w_cant_actual_upd,
			lote_cant_vendidos = lote_cant_vendidos - @w_cant_actual_upd,
			lote_fecha_upd = @w_fecha
			where lote_id = @w_lote_id
		end

		if @w_salida_tipo = 'PF'
		begin
			update tm_registro_camal 
			set cam_estado = 0
			where salida_id = @i_salida_id
		end

		select @w_cta_obs = 'Se elimina pedido #' + CONVERT(varchar, @i_salida_id)

		update tm_salida_lote 
		set salida_estado = 0
		where salida_id = @i_salida_id

		if @w_cliente_id <> @w_cliente_camal and @w_desc_forma_pago = 'CREDITO'
		begin
			-- SE OBTIENE LA CTA POR MEDIO DEL MOVIEMIENTO REGISTRADO
			select @w_cta_id = cta_id from tm_movimiento_cuenta
			where salida_id = @i_salida_id
			and movc_estado = 1
			and movc_tipo = '+'

			-- SE ELIMINA REGISTRO DE MOVIMIENTO Y SALIDA LOTE
			update tm_movimiento_cuenta 
				set movc_obs = @w_cta_obs
			where salida_id = @i_salida_id
			and movc_tipo = '+'

			exec sp_crud_cuenta_cli  
			@i_operacion = 'U',
			@i_cta_id = @w_cta_id, 
			@i_suc_id = @i_suc_id,
			@i_movc_tipo = '-',
			@i_cta_monto = @w_total_upd,
			@i_cta_fecha = @w_fecha,
			@i_usu_id = @i_usu_id,
			@i_cta_obs = @w_cta_obs,
			@i_salida_id = @i_salida_id,
			@i_pagc_id = 0
		end

		select @w_exec = 0
	end

	if @i_operacion = 'R'
	begin
		if @i_salida_tipo = 'PV'
		begin
			select
				cl.cli_nombre, 
				l.lote_descripcion, 
				sl.salida_tipo, 
				salida_cantidad, 
				salida_peso, 
				salida_tara, 
				salida_peso_neto, 
				salida_precio, 
				salida_total, 
				CONVERT(varchar, salida_fecha , 103) as salida_fecha,
				salida_id,
				cl.cli_telefono,
				tp.pago_nombre,
				sl.pago_id
			from tm_salida_lote sl
			inner join tm_lote l on l.lote_id = sl.lote_id
			inner join tm_cliente cl on cl.cli_id = sl.cli_id
			inner join tm_tipo_pago tp on tp.pago_id = sl.pago_id
			where l.suc_id = @i_suc_id
			and sl.usu_id = @i_usu_id
			and sl.salida_estado = 1
			and CAST(sl.salida_hora as date) = @i_salida_fecha
			and salida_tipo = @i_salida_tipo
			order by sl.cli_id, sl.salida_fecha desc
		end
		else 
		begin
			select
				cl.cli_nombre, 
				sl.salida_tipo, 
				salida_cantidad, 
				salida_peso, 
				salida_tara, 
				salida_peso_neto, 
				salida_precio, 
				salida_total, 
				CONVERT(varchar, salida_fecha , 103) as salida_fecha,
				salida_id,
				cl.cli_telefono,
				tp.pago_nombre,
				sl.pago_id
			from tm_salida_lote sl
			inner join tm_cliente cl on cl.cli_id = sl.cli_id
			inner join tm_tipo_pago tp on tp.pago_id = sl.pago_id
			where sl.usu_id = @i_usu_id
			and sl.salida_estado = 1
			and CAST(sl.salida_hora as date) = @i_salida_fecha
			and salida_tipo = @i_salida_tipo
			order by sl.cli_id, sl.salida_fecha desc
		end

	end

	if @i_operacion = 'K'
	begin
		select
			sum(cam_cantidad) as cantidadCamal,
			sum(cam_registros) as registrado
		from tm_registro_camal c
		where c.cam_fecha = '2025-07-07'
		and cam_estado = 1

	end
	if @i_operacion = 'L'
	begin 
		if @i_tipo = 'L'
		begin 
			select 
				l.lote_descripcion,
				c.cli_nombre,
				sl.salida_tipo,
				CONVERT(varchar, sl.salida_fecha , 23) as salida_fecha,
				sl.salida_cantidad,
				sl.salida_peso_neto,
				sl.salida_precio,
				sl.salida_total,
				CONCAT(u.usu_nombre, ' ',u.usu_apellido) as usu_nombre,
				CONVERT(varchar, sl.salida_hora , 120) as salida_hora,
				sl.salida_id,
				sl.salida_vpagado
			from tm_salida_lote sl
			inner join tm_lote l on l.lote_id = sl.lote_id
			inner join tm_cliente c on c.cli_id = sl.cli_id
			inner join tm_usuario u on u.usu_id = sl.usu_id
			where l.lote_id = ISNULL(@i_lote_id, l.lote_id)
			and c.cli_id = ISNULL( @i_cli_id , c.cli_id)
			and sl.salida_tipo = ISNULL(@i_salida_tipo,sl.salida_tipo )
			and CAST(sl.salida_fecha as date) between @i_fecha_desde and @i_fecha_hasta
			and l.suc_id = @i_suc_id
			and sl.salida_estado = 1
			order by sl.cli_id, sl.salida_fecha desc
		end 

		if @i_tipo = 'T'
		begin 

			select 
				@w_cantidad_sal = 0,
				@w_peso_total = 0,
				@w_valor_total_act = 0,
				@w_monto_abonado = 0,
				@w_saldo_total_cta = 0

			select 
				@w_cantidad_sal = sum(sl.salida_cantidad),
				@w_peso_total = sum(sl.salida_peso_neto),
				@w_valor_total_act = sum(sl.salida_total)
			from tm_salida_lote sl
			inner join tm_lote l on l.lote_id = sl.lote_id
			inner join tm_cliente c on c.cli_id = sl.cli_id
			inner join tm_usuario u on u.usu_id = sl.usu_id
			where l.lote_id = ISNULL(@i_lote_id, l.lote_id)
			and c.cli_id = ISNULL( @i_cli_id , c.cli_id)
			and sl.salida_tipo = ISNULL(@i_salida_tipo,sl.salida_tipo )
			and CAST(sl.salida_fecha as date) between @i_fecha_desde and @i_fecha_hasta
			and l.suc_id = @i_suc_id
			and sl.salida_estado = 1

			if @i_cli_id is not null
			begin
				-- SE OBTIENE LOS VALORES ABONADOS DE LA CUENTA
				select @w_monto_abonado = SUM(pagc_monto)
				from tm_pago_cuenta pc 
				inner join tm_salida_lote sl on sl.salida_id = pc.salida_id
				where sl.cli_id = @i_cli_id
				and sl.salida_vpagado in ('A','C')
				and pagc_estado = 1
				and sl.salida_estado = 1

				-- SE OBTIENEN VALORES DE PEDIDOS Y VENTAS
				select  @w_val_pedido_abo = sum(movc_valor) from tm_salida_lote s 
				inner join tm_movimiento_cuenta mc on mc.salida_id = s.salida_id
				where s.cli_id = @i_cli_id
				and s.salida_estado = 1 
				and mc.movc_estado = 1
				and salida_vpagado not in ('C')
				and movc_tipo = '-'

				select  @w_val_pedido = sum(movc_valor) from tm_salida_lote s 
				inner join tm_movimiento_cuenta mc on mc.salida_id = s.salida_id
				where s.cli_id = @i_cli_id
				and s.salida_estado = 1 
				and mc.movc_estado = 1
				and salida_vpagado not in ('C')
				and movc_tipo = '+'

				set @w_saldo_total_cta = ISNULL(@w_val_pedido, 0) - isnull(@w_val_pedido_abo ,0)

			end
			-- RETORNA LOS VALORES OBTENIDOS
			select 
				@w_cantidad_sal as 'cantidad',
				@w_peso_total as 'peso_neto',
				@w_valor_total_act as 'total',
				@w_monto_abonado as 'monto_abonado',
				@w_saldo_total_cta as 'saldo_total_cta'
		end 

		if @i_tipo = 'I'
		begin
			select
				cl.cli_nombre, 
				cl.cli_ruc,
				cl.cli_telefono,
				cl.cli_direccion,
				cl.cli_correo,
				l.lote_descripcion, 
				sl.salida_tipo, 
				salida_cantidad, 
				salida_peso, 
				salida_tara, 
				salida_peso_neto, 
				salida_precio, 
				salida_total, 
				CONVERT(varchar, salida_fecha , 20) as salida_fecha,
				salida_id,
				cl.cli_id,
				l.lote_id,
				l.lote_cant_actual,
				ISNULL((select SUM(pagc_monto) from tm_pago_cuenta pc where pc.salida_id = sl.salida_id and pagc_estado = 1 ),0) as saldo,
				salida_vpagado,
				pago_id
			from tm_salida_lote sl
			inner join tm_lote l on l.lote_id = sl.lote_id
			inner join tm_cliente cl on cl.cli_id = sl.cli_id
			where sl.salida_id = @i_salida_id
			and sl.salida_estado = 1

		end
	
		if @i_tipo = 'A'
		begin
			select 
				sl.lote_id , 
				l.lote_descripcion , 
				sum(salida_cantidad) as cantidad, 
				sum(salida_peso_neto) as peso_neto, 
				sum(salida_total) as total
			from tm_salida_lote sl
			inner join tm_lote l on l.lote_id = sl.lote_id 
			where  CAST(sl.salida_hora as date) between @i_fecha_desde and @i_fecha_hasta
			and  sl.salida_estado = 1
			group by sl.lote_id, l.lote_descripcion
		end
	
		if @i_tipo = 'D'
		begin
			select 
				SUM(salida_total) as total_ventas , 
				sum(salida_peso_neto) as total_peso, 
				sum(salida_cantidad) as total_cantidad, 
				COUNT(salida_id) as total_pedidos
			from tm_salida_lote sl
			inner join tm_lote l on l.lote_id = sl.lote_id
			where YEAR(salida_fecha) = @i_year_report and 
			month(salida_fecha) = @i_month_report
			and l.suc_id = @i_suc_id
			and sl.salida_estado = 1
		end

		if @i_tipo = 'C'
		begin
			select 
				salida_id, 
				salida_total,
				salida_vpagado,
				ISNULL((select SUM(pagc_monto) from tm_pago_cuenta pc where pc.salida_id = sl.salida_id and pagc_estado = 1),0) as saldo
			from tm_salida_lote sl
			where salida_vpagado not in ('C')
			and cli_id = @i_cli_id
			and salida_estado = 1
			order by salida_id
		end
	end

set nocount off
	return 0
end
