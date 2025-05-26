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
