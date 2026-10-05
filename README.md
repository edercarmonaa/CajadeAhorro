# Caja de Ahorro

Sistema web para administrar una caja de ahorro interna: empleados, aportaciones, prestamos, abonos, movimientos de entrada/salida, cortes semanales, devoluciones y reportes financieros en Excel y PDF.

## El problema

Una caja de ahorro operada manualmente necesita registrar movimientos frecuentes de dinero, calcular saldos por empleado, controlar prestamos y generar reportes periodicos. Si ese proceso se lleva en hojas sueltas o capturas manuales, aumenta el riesgo de errores en saldos, duplicidad de registros, reportes incompletos y poca trazabilidad al cerrar una semana o ejercicio.

## La solucion

El proyecto centraliza la captura y consulta de la informacion de la caja de ahorro en una aplicacion PHP con interfaz AngularJS. El flujo principal permite iniciar sesion, administrar empleados, registrar ahorros, prestamos, abonos y movimientos de caja, cerrar semanas de trabajo y generar reportes descargables.

## Funcionalidades principales

- Autenticacion de usuarios con contrasenas hasheadas y token JWT.
- Administracion de empleados y usuarios.
- Registro de ahorros, prestamos, abonos y abonos en efectivo.
- Registro de entradas y salidas de caja.
- Configuracion de ejercicios y semanas.
- Corte semanal.
- Calculo de devoluciones.
- Reportes y concentrados semanales/anuales.
- Exportacion a Excel mediante PHPExcel.
- Generacion de PDF mediante TCPDF.
- Importacion de datos desde archivos de Excel cargados localmente.

## Que mejora este proyecto

Reduce el trabajo manual necesario para consolidar movimientos por empleado y por semana. Tambien ayuda a obtener reportes consistentes de saldos, deudores, devoluciones, concentrados y tarjetas, usando los datos guardados en la base de datos.

## Para quien esta pensado

Esta pensado para administradores o responsables de una caja de ahorro interna que necesitan registrar operaciones, revisar saldos y generar reportes periodicos para empleados o socios.

## Tecnologias utilizadas

- PHP con PDO para conexion a MySQL.
- MySQL o MariaDB como base de datos.
- AngularJS 1.x.
- jQuery.
- Materialize CSS.
- angular-ui-router.
- angular-storage y angular-jwt.
- PHPExcel para importacion/exportacion de hojas de calculo.
- TCPDF para reportes PDF.

## Requisitos

El repositorio no incluye archivos de definicion de dependencias como `composer.json` o `package.json` en la raiz, por lo que las librerias necesarias estan vendorizadas en `libs/`.

Necesitas:

- PHP con extension PDO MySQL.
- MySQL o MariaDB.
- Un servidor web capaz de ejecutar PHP, por ejemplo Apache, Nginx con PHP-FPM o el servidor integrado de PHP para desarrollo.
- Una base de datos con las tablas esperadas por los modelos del directorio `objects/`.

No se encontro un archivo SQL de esquema o migraciones en el proyecto. Para ejecutar la aplicacion completa es necesario contar con la estructura de base de datos correspondiente.

## Instalacion

```bash
git clone <url-del-repositorio>
cd ahorro
```

Copia el archivo de ejemplo de entorno:

```bash
cp .env.example .env
```

En Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Edita `.env` con los datos de tu base de datos y un secreto JWT propio. No subas `.env` al repositorio.

## Configuracion

Variables usadas por la aplicacion:

| Variable | Descripcion |
| --- | --- |
| `DB_HOST` | Host de MySQL/MariaDB. |
| `DB_NAME` | Nombre de la base de datos. |
| `DB_USERNAME` | Usuario de base de datos. |
| `DB_PASSWORD` | Contrasena de base de datos. |
| `DB_CHARSET` | Charset de conexion, por defecto `utf8`. |
| `JWT_SECRET` | Secreto privado para firmar tokens JWT. |

## Base de datos

El codigo espera una base de datos MySQL/MariaDB con tablas como `empleado`, `usuarios`, `niveles`, `ahorro`, `prestamo`, `abonos`, `abonos_efec`, `ent_sal`, `tipo_operacion`, `ejercicio`, `semana` y `devoluciones`.

El archivo `database/schema.sql` contiene la estructura extraida de scripts heredados. Las filas con datos reales fueron omitidas intencionalmente.

El archivo `database/seed.sql` conserva solamente datos de catalogo no sensibles: categorias, niveles, tasas de interes y tipos de operacion.

Para preparar una base local:

```bash
mysql -u your_database_user -p your_database < database/schema.sql
mysql -u your_database_user -p your_database < database/seed.sql
```

Despues crea un ejercicio, semanas, usuarios y empleados con datos ficticios o datos propios del entorno donde se vaya a usar el sistema.

## Ejecutar el proyecto

Para desarrollo, si PHP esta disponible en tu equipo:

```bash
php -S localhost:8000
```

Despues abre:

```text
http://localhost:8000
```

En un entorno con Apache/Nginx, apunta el document root al directorio del proyecto y configura PHP para leer las variables de entorno o el archivo `.env`.

## Uso

1. Inicia sesion desde la pantalla principal.
2. Configura o selecciona el ejercicio activo.
3. Registra empleados y usuarios.
4. Captura ahorros, prestamos, abonos, abonos en efectivo y movimientos de caja.
5. Realiza cortes semanales desde la seccion de configuracion.
6. Consulta reportes y descarga archivos Excel o PDF cuando sea necesario.

## Estructura del proyecto

```text
.
├── config/       # Conexion y carga de variables de entorno
├── objects/      # Modelos y consultas de negocio
├── views/        # Vistas PHP usadas por AngularJS
├── libs/         # Librerias frontend y PHP vendorizadas
├── templates/    # Plantillas XLSX para reportes
├── images/       # Imagenes usadas por la interfaz
├── *.php         # Endpoints de operaciones, importacion y reportes
└── README.md
```

## Capturas

El proyecto incluye imagenes para la interfaz y reportes en `images/`, pero no contiene capturas de pantalla documentales. Pueden agregarse posteriormente en una carpeta dedicada, por ejemplo `docs/screenshots/`.

## Seguridad

- Los secretos se configuran mediante variables de entorno.
- `.env` esta ignorado por Git y no debe publicarse.
- `.env.example` contiene solo placeholders.
- No almacenes credenciales, tokens, claves privadas, dumps de base de datos ni datos personales sensibles en el repositorio.
- Si una credencial ya estuvo en Git, debe rotarse o revocarse aunque el archivo actual haya sido corregido.

## Pruebas

No se encontraron pruebas automatizadas ni scripts de lint/build en el proyecto.

Validaciones manuales recomendadas antes de publicar:

```bash
php -l config/env.php
php -l config/database.php
php -l consulta_usuario.php
```

Tambien conviene probar el login, la conexion a base de datos, la captura de movimientos y la generacion de Excel/PDF en un entorno local con datos ficticios.

## Estado del proyecto

Version inicial funcional heredada. El codigo muestra funcionalidades completas de administracion y reportes, pero faltan piezas para reproducibilidad total, especialmente el esquema de base de datos y pruebas automatizadas.

## Limitaciones actuales

- Incluye estructura SQL y catalogos basicos, pero no datos operativos.
- No incluye pruebas automatizadas.
- No incluye gestor de dependencias en la raiz.
- Las librerias de terceros estan vendorizadas en `libs/`.
- La aplicacion requiere configurar manualmente una base de datos compatible.

## Proximas mejoras

- Agregar migraciones o un esquema SQL inicial sin datos privados.
- Incorporar datos semilla ficticios para desarrollo.
- Agregar pruebas automatizadas para operaciones criticas.
- Migrar dependencias PHP y frontend a gestores actuales.
- Revisar autorizacion del lado servidor para cada endpoint.
- Documentar roles y permisos con mayor detalle.

## Contribuciones

1. Haz un fork del repositorio.
2. Crea una rama para tu cambio.
3. Mantiene los cambios acotados y sin secretos.
4. Prueba el flujo afectado.
5. Abre un pull request explicando el problema resuelto y la validacion realizada.

## Licencia

Este proyecto todavia no incluye un archivo de licencia.
