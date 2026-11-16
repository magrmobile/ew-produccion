# Invalidacion de DTE de Infile

El proveedor se obtiene del registro del DTE. Los documentos `infile` usan el
JSON simplificado y la API de Infile; los documentos `rrd` conservan la firma y
la API de RRD. No se requiere una migracion.

## Configuracion

```dotenv
INFILE_ENVIRONMENT=test
INFILE_API_KEY=...
INFILE_CA_BUNDLE=
INFILE_INVALIDATE_URL=
INFILE_INVALIDATION_RESPONSE_CONFIRMED=true
```

Sin URL explicita se utiliza:

- Sandbox: `https://certificador.infile.com.sv/api/v1/certificacion/test/documento/invalidacion`
- Produccion: `https://certificador.infile.com.sv/api/v1/certificacion/prod/documento/invalidacion`

La URL configurada debe corresponder al host, ruta y ambiente anteriores.
El ambiente del DTE certificado debe coincidir con `INFILE_ENVIRONMENT`.
Si la instalacion usa configuracion cacheada, regenerarla con `php72 artisan config:cache`
despues de cambiar estas variables.

## Certificados HTTPS y diagnostico

La invalidacion verifica HTTPS usando `resources/certificates/cacert.pem`,
un paquete publico de certificados raiz de Mozilla distribuido por
`https://curl.se/ca/cacert.pem`. Esto evita depender de un `curl.cainfo`
ausente en instalaciones PHP 7.2 para Windows. La verificacion TLS permanece activa.
Para usar un paquete CA administrado por la instalacion, configurar
`INFILE_CA_BUNDLE` con su ruta absoluta. Mantener actualizado el paquete al desplegar.

El 2026-11-14 se reprodujo con PHP 7.2 el error cURL 60 por falta de un emisor
de confianza. Una peticion HEAD publica, sin credenciales, paso a HTTP 200
al usar este paquete. No se envio ninguna invalidacion durante ese diagnostico.

Los fallos inesperados muestran una referencia y su etapa, y registran en
`storage/logs/laravel.log` el mensaje `Fallo en invalidacion Infile`, con el
ID del DTE, clase/codigo de excepcion, codigo cURL, estado HTTP y ruta de la
respuesta cuando se pudo guardar. No se registran encabezados, llaves,
payloads, excepciones sin filtrar ni consultas SQL con sus parametros.
Se distinguen los errores de TLS, DNS, conexion, timeout, almacenamiento y
actualizacion local posterior a una confirmacion de Infile.

## Respuesta confirmada en Sandbox

El 2026-11-14 se verifico la respuesta real de exito proporcionada mediante
`Invalidacion_Infile_Test_Response.json`. La fixture de regresion conserva
su estructura con datos de prueba y sin la firma original.

La confirmacion esta habilitada por defecto. Si una instalacion ya tenia
`INFILE_INVALIDATION_RESPONSE_CONFIRMED=false`, cambiarla explicitamente a
`true` para habilitar la persistencia y el endpoint de produccion.
No se modifica automaticamente el ambiente ni la configuracion de la instalacion.

Se exige HTTP 2xx, `ok: true`, `respuesta_dgi.estado: PROCESADO`,
`respuesta_dgi.codigoMsg: 001`, sello no vacio y fecha valida. Se admiten
fechas `d/m/Y H:i:s`, SQL `Y-m-d H:i:s` e ISO 8601 con fracciones y zona
horaria. Las fechas con zona se convierten a `config('app.timezone')`.
Ademas se comprueban:

- `json.documento.codigoGeneracion`: UUID del DTE original, igual al registro.
- `json.emisor.nit`: NIT del emisor original.
- El ambiente de `json.identificacion` y `respuesta_dgi`, igual al DTE.
- La version 3 y el UUID del evento en `json.identificacion`, `respuesta` y
  `respuesta_dgi`, que deben coincidir entre si.
- El sello de `respuesta.selloRecepcion` y `respuesta_dgi.selloRecibido`, que
  deben coincidir. No usar `json.documento.selloRecibido`: es el sello original.

Se actualizan solo estado, sello, fecha de procesamiento y usuario de
invalidacion. El UUID, numero de control y JSON certificado del DTE se conservan.
No se sobrescriben con la identificacion del evento de invalidacion.

Las respuestas se conservan de forma privada en
`storage/app/infile/invalidations/{id}/{referencia}.json`, sin encabezados de
autenticacion. Las respuestas de rechazo siguen cubiertas por pruebas simuladas;
todavia no se dispone de un ejemplo real de rechazo.

La bandera puede desactivarse para volver al modo de diagnostico: produccion
queda bloqueada y Sandbox no actualiza el registro local. Ante una respuesta
incierta o timeout, consultar el documento en Infile antes de repetir la solicitud.

Un DTE invalidado por Postman ya fue anulado externamente: no reenviar la
invalidacion para intentar actualizar el registro local. Este cambio no importa
ni modifica automaticamente registros de pruebas anteriores.

La certificacion utiliza el mismo conversor de fechas para las variantes de
Sandbox. Si no reconoce `respuesta_dgi.fhProcesamiento`, intenta
`respuesta.fechaEmision`; si tampoco es valida, registra una advertencia y
guarda la fecha local de recepcion para no perder una certificacion exitosa.
La invalidacion mantiene el criterio estricto: no confirma una respuesta cuya
fecha de procesamiento no se pueda interpretar.

## Datos enviados

El evento usa el UUID certificado del registro, el establecimiento y el NIT del
emisor original. El responsable conserva los datos del emisor y el solicitante
los del receptor, incluido su correo. No se envian `fecha_evento` ni `hora_evento`.

Los tipos 1 y 3 exigen un sustituto certificado y no invalidado del mismo
cliente, emisor y proveedor. No se permite sustituir el documento por si mismo.
