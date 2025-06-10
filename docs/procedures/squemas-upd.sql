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