-- Safe initialization data for catalog tables.
-- Operational data such as employees, users, loans, savings, payments,
-- weekly balances and cash movements is intentionally omitted.

INSERT INTO `categoria` (`id_categoria`, `tpo_categoria`) VALUES
(1, 'EVENTUAL'),
(2, 'PLANTA');

INSERT INTO `interes` (`id_interes`, `valor_interes`) VALUES
(1, 3),
(2, 5);

INSERT INTO `niveles` (`id_nivel`, `nivel`) VALUES
(1, 'Consulta'),
(2, 'Administrador');

INSERT INTO `tipo_operacion` (`id_tipo`, `descripcion`) VALUES
(1, 'Otras Entradas'),
(2, 'Otras Salidas'),
(3, 'Abonos Recibidos por Tesoreria'),
(4, 'Ahorros Recibidos por Tesoreria'),
(5, 'Abonos en Efectivos'),
(6, 'Gastos');
