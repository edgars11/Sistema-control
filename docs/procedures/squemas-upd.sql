use SistemaControl
-- Se crea la tabla para agrgar el alimento del lote
-- Creación proveedor 
INSERT INTO tm_proveedor
([emp_id],[prov_nombre],[prov_ruc],[prov_telefono],[prov_direccion],[prov_correo],[prov_fecha_crea],[prov_estado])VALUES
(1,'CONSUMIDOR FINAL', '9999999999999', '2222-222', 'SIN DIRECCIÓN','EMAIL@GMAIL.COM', GETDATE(), 1);

-- CREACIÓN MENUS NUEVOS
insert into tm_menu(men_nombre, men_ruta, men_identi, men_fecha_crea, men_estado, men_grupo)
values
('Nueva Venta',	 '../MntVenta/',	    'mntVenta',	        GETDATE(),	1,  'Venta'),
('List. Venta',	 '../ListVenta/',	    'listVenta',	    GETDATE(),	1,  'Venta'),
('Nueva Compra', '../MntCompra/',	    'mntCompra',	    GETDATE(),	1,  'Compra'),
('List. Compra', '../ListCompra/',	    'listCompra',	    GETDATE(),	1,  'Compra'),
('Proveedor',	 '../MntProveedor/',    'mntProveedor',		GETDATE(),	1,  'Mantenimiento');
-- VINCULAR LOS MENUS CON LOS
## Hacer la vinculacion desde la pantalla de roles

-- Se crea nuevo cliente consumidor final con id 0
INSERT INTO tm_cliente
( emp_id, cli_nombre, cli_ruc, cli_telefono, cli_direccion, cli_correo,cli_fecha_crea, cli_estado)
VALUES
(1,'Consumidor Final','9999999999999','0999999999','Sin dirección','micorreo@gmail.com',GETDATE(),1);

-- SE AGREGA FORMA DE PAGO DEPÓSITO
INSERT INTO tm_tipo_pago
(pago_nombre,pago_fecha_crea,pago_estado)
VALUES
('DEPÓSITO', GETDATE(), 1);

-- INGRESO NUEVO PARAMETRO CLICL
INSERT INTO tm_parametros
(par_descripcion,par_nemonico,par_tipo,par_string,par_int,par_double,par_estado,par_fecha)
VALUES
('CLIENTE CAMAL LITE','CLICL','I',NULL,13,NULL,NULL,NULL)

-- INGRESO PARAM CLIENTE CONSUMIDOR FINAL(BUSCAR ID CLIENTE CONSUMIDOR FINAL)
INSERT INTO tm_parametros
(par_descripcion,par_nemonico,par_tipo,par_string,par_int,par_double,par_estado,par_fecha)
VALUES
('CLIENTE CONSUMIDOR FINAL','CLICF','I',NULL,12,NULL,NULL,NULL)