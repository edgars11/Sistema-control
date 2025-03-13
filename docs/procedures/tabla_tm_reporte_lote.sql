USE [SistemaControl]
GO

/****** Object:  Table [dbo].[tm_reporte_lote]    Script Date: 4/3/2025 20:46:34 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[tm_reporte_lote](
	[repor_id] [int] IDENTITY(1,1) NOT NULL,
	[lote_id] [int] NULL,
	[suc_id] [int] NULL,
	[repor_tipo_pro] [char](2) NULL,
	[repor_cantidad] [int] NULL,
	[repor_peso_neto] [decimal](18, 2) NULL,
	[repor_total] [decimal](18, 2) NULL,
	[repor_consumo] [int] NULL,
	[repor_perdida] [int] NULL,
	[repor_fecha_reg] [datetime] NULL,
	[repor_fecha_ing] [datetime] NULL,
 CONSTRAINT [PK_tm_reporte_lote] PRIMARY KEY CLUSTERED 
(
	[repor_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
