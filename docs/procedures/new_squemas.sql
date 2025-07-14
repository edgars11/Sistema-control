use SistemaControl
-- se crea tabla para almacenar el alimento por lote
CREATE TABLE tm_alimento_lote
(
ali_id int identity,
lote_id int not null,
ali_cantidad int not null,
ali_fecha date not null,
ali_hora datetime,
user_id int,
ali_desc varchar(100),
ali_estado tinyint
);

-- Sentencia para eliminar columna de una tabla
ALTER TABLE nombreTabla
DROP COLUMN nombreColumna;

-- se crea primary key en la tabla nueva creada

alter table tm_alimento_lote
  add constraint PK_alimentos_id
  primary key nonclustered (ali_id);


-- se agrega nuevo campo a la tabla tm_movimiento_lote
alter table tm_movimiento_lote
add mov_hora datetime
-- se cambia el tipo de dato del campo mov_fecha a date
alter table tm_movimiento_lote
alter column mov_fecha date
-- se agrega campo en el registro de salida para validar el pago del mismo
ALTER TABLE tm_salida_lote
ADD salida_vpagado char(1)
CONSTRAINT cnstrt_not_null_pagado NOT NULL
CONSTRAINT cnstrt_default_pagado DEFAULT 'N';

-- se agrega nuevo campo en la tabla tm_pago_cuenta para guardar el id de salida que se esta pagando
ALTER TABLE tm_pago_cuenta
ADD salida_id int
CONSTRAINT cnstrt_not_null_pagado NOT NULL
CONSTRAINT cnstrt_default_pagado DEFAULT 0;

-- 26/05/2025
-- se agrega el campo pagc_id para registrar el id del pago
ALTER TABLE tm_movimiento_cuenta
ADD pagc_id int NULL;

-- se agrega el campo salida_estado para una eliminación lógica
ALTER TABLE tm_salida_lote
ADD salida_estado tinyint
CONSTRAINT cnst_salida_estado NOT NULL
CONSTRAINT cnst_salida_estado_def DEFAULT 1;

-- se agrega el campo movc_estado para una eliminación lógica
ALTER TABLE tm_movimiento_cuenta
ADD movc_estado tinyint
CONSTRAINT cnst_movc_estado NOT NULL
CONSTRAINT cnst_movc_estado_def DEFAULT 1;

---------------------------------------------------------------------
-- 03/06/2025 --
-- SE CREA NUEVO CAMPO PARA VINCULAR EL ID DE VENTA
ALTER TABLE tm_movimiento_cuenta
ADD ven_id int NULL;

-- Se crea un nuevo campo para registrar los pagos con pedido id 0. PENDIENTE
ALTER TABLE tm_movimiento_cuenta
ADD movc_est_saldcero char(1) NULL;

-- SE CREA TABLA PARA REGISTRO VENTAS A CREDITO
CREATE TABLE [dbo].[tm_registro_vencred](
	[rvc_id] [int] IDENTITY(1,1) NOT NULL,
	[ven_id] [int] NULL,
	[rvc_monto] [decimal](18, 2) NULL,
	[rvc_abonado] [decimal](18, 2) NULL,
	[rvc_est_cta] [char](1) NULL,
	[rvc_fecha_upd] [datetime] NULL,
	[rvc_estado] [tinyint] NULL,
	[rvc_observacion] [varchar](80) NULL
) ON [PRIMARY]

-- 18/06/2025
-- SE AGREGA CAMPO VEN_ID A LA TABLA TM_PAGO_CUENTA
ALTER TABLE tm_pago_cuenta
ADD ven_id int NULL;
-- 10/07/2025
-- Se crea un nuevo campo para registrar los pagos con pedido id 0. PENDIENTE
ALTER TABLE tm_salida_lote
ADD pago_id tinyint
CONSTRAINT cnst_pago_id NOT NULL
CONSTRAINT cnst_pago_id_def DEFAULT 1;

-- NUEVA TABLA PARA PARÁMETROS
CREATE TABLE tm_parametros(
	[par_id] [int] IDENTITY(1,1) NOT NULL,
	[par_descripcion] [varchar](100) NULL,
	[par_nemonico] [varchar](10) NOT NULL,
	[par_tipo] [char](1) NULL,
	[par_string] [varchar](20) NULL,
	[par_int] [int] NULL,
	[par_double] [float] NULL,
	[par_estado] [tinyint] NULL,
	[par_fecha] [datetime] NULL
) ON [PRIMARY]

-- INGRESO NUEVO PARAMETRO CLICL
INSERT INTO tm_parametros
(par_descripcion,par_nemonico,par_tipo,par_string,par_int,par_double,par_estado,par_fecha)
VALUES
('CLIENTE CAMAL LITE','CLICL','I',NULL,13,NULL,NULL,NULL)
-- TABLA PARA REGISTRO DE CAMAL

CREATE TABLE [dbo].[tm_registro_camal](
	[cam_id] [int] IDENTITY(1,1) NOT NULL,
	[cam_cantidad] [tinyint] NULL,
	[cam_fecha] [date] NULL,
	[salida_id] [int] NULL,
	[cam_registros] [tinyint] NULL,
	[cam_estado] [tinyint] NULL,
	[cam_hora] [datetime] NULL
) ON [PRIMARY]



