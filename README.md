# Hielos del Maipo - Backend de solicitudes (U1)

Primera API REST de registro de **solicitudes de pedido**, desarrollada como prototipo académico para Desarrollo Backend (IF201IINF). Se utiliza una sola entidad, Laravel 12, Eloquent ORM y SQLite. No es una tienda en línea y no implementa reglas empresariales todavía no validadas.

**Estado de verificación (pruebas locales de la evaluación):** La API se ejecutó con Laravel 12 y SQLite. En Postman se verificaron POST 201, GET 200, PUT 200, DELETE 204 y validación 422. El registro conservó la cantidad actualizada después de reiniciar `php artisan serve`. En PHPUnit se obtuvieron **6 pruebas aprobadas, 30 aserciones**. La comparación local con 60 registros arrojó 12,47 ms sin paginación (60 recuperados) y 0,87 ms al recuperar 10 registros paginados; estas cifras son orientativas y no representan una prueba de carga.

La base de datos local y `vendor` se excluyeron del ZIP por seguridad y tamaño. Las evidencias se encuentran en `Evidencias/` y el informe definitivo en `docs/EVU1_RAMIREZ_FRANCO.pdf`.

## Requisitos

- PHP compatible con Laravel 12 (8.2 a 8.5), extensiones habituales de Laravel y `pdo_sqlite`/`sqlite3` habilitadas.
- Composer, conexión para instalar las dependencias inicialmente y una terminal (PowerShell o terminal integrada de VS Code).
- Postman, Insomnia o curl para las pruebas HTTP.
- Git y una cuenta de GitHub para subir el repositorio privado.

Documentación oficial: https://laravel.com/docs/12.x/installation

## Instalación (Windows, PowerShell)

El proyecto ya incluye los archivos fuente de Laravel. Para ejecutarlo desde este ZIP o desde GitHub:

1. Descomprimir los archivos en una carpeta local con ruta corta (por ejemplo `C:\BackendU1\hielos-backend`) y abrir PowerShell desde la carpeta que contiene `artisan` y `composer.json`.
2. Instalar dependencias con Composer (requiere PHP 8.2+ y extensiones habituales, en particular `pdo_sqlite`):

   ```powershell
   composer install
   ```

3. Crear la configuración local, clave de aplicación y archivo SQLite:

   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   New-Item -ItemType File -Force database/database.sqlite
   ```

4. Revisar que `.env` tenga `DB_CONNECTION=sqlite`. Ejecutar las migraciones y el servidor:

   ```powershell
   php artisan migrate
   php artisan route:list --path=api
   php artisan serve
   ```

5. Importar `docs/Hielos_del_Maipo_U1.postman_collection.json` en Postman y enviar solicitudes JSON a `http://127.0.0.1:8000`, incluyendo `Accept: application/json`.
6. En otra terminal, ejecutar:

   ```powershell
   php artisan test --filter=SolicitudPedidoApiTest
   ```

Las pruebas automatizadas usan `RefreshDatabase`; no ejecutar `migrate:fresh` sobre bases de datos con información importante. No exponer la API a Internet: no incluye autenticación para producción.

## Comprobar persistencia tras reiniciar

1. Con el servidor iniciado, realizar **POST** y guardar el `id` real devuelto.
2. Consultar el `id` con **GET** y obtener respuesta 200.
3. Detener el servidor con `Ctrl+C` (sin borrar `database/database.sqlite`).
4. Reiniciar `php artisan serve` y repetir el **GET** del mismo `id`.
5. Guardar capturas de ambas respuestas. Debe conservarse el mismo registro.

## Paginación y evidencia antes/después

```powershell
php artisan db:seed --class=SolicitudPedidoSeeder
php scripts/comparar_paginacion.php
```

El seeder agrega 60 registros **ficticios** cada vez que se ejecuta. El script compara una consulta Eloquent que obtiene todas las filas con otra que trae solo 10 para la primera página. Luego probar `GET /api/solicitudes`: valor por defecto 10; `?per_page=500` está limitado a 50. Conservar la salida de consola y registrar los valores reales en el informe. Los milisegundos son orientativos y la métrica más clara es el número de filas retornadas.

## Endpoints

| Método | Ruta | Acción | HTTP correcto |
|---|---|---|---|
| GET | `/api/solicitudes` | Lista paginada | 200 |
| GET | `/api/solicitudes/{id}` | Consultar una solicitud | 200 / 404 |
| POST | `/api/solicitudes` | Crear | 201 / 422 |
| PUT | `/api/solicitudes/{id}` | Reemplazar datos | 200 / 404 / 422 |
| PATCH | `/api/solicitudes/{id}` | Actualización parcial | 200 / 404 / 422 |
| DELETE | `/api/solicitudes/{id}` | Eliminar | 204 / 404 |

Ejemplo de cuerpo de POST:

```json
{
  "cliente_referencia": "Cliente de prueba 01",
  "cantidad": 8,
  "fecha_requerida": "2026-11-15",
  "estado": "pendiente"
}
```

Estados de prueba: `pendiente`, `en_revision`, `finalizada`. Son valores **definidos solo para la evaluación**, sujetos a validación con la organización. `cantidad` representa unidades de demostración, no bolsas, kilogramos o formatos comerciales reales.

## Arquitectura breve

Cliente HTTP -> rutas Laravel -> controlador -> FormRequest (validación) -> modelo Eloquent -> SQLite -> respuesta JSON/HTTP. La separación permite revisar cada responsabilidad sin agregar módulos fuera de alcance.

## Seguridad

Las credenciales y la clave APP_KEY permanecen en `.env` fuera de Git; el acceso a datos se realiza mediante Eloquent, sin concatenar SQL. **No hay autenticación ni autorización implementadas**: esta API debe permanecer restringida a desarrollo local, sin exposición pública. En futuras unidades se evaluará autenticación, permisos de roles y HTTPS/TLS según requerimientos reales.

## Límites y dificultades conocidas

- No contempla inventario, distribución, pagos, facturas ni reservas: estos procesos no han sido confirmados para este módulo.
- No tiene frontend productivo, roles, acceso con usuario, tokens, despliegue ni protección HTTPS incorporada.
- Los nombres de campos, los tres estados, la cantidad como entero y el formato de fecha son decisiones académicas.
- La creación del proyecto base y la ejecución real de las pruebas requieren instalar Laravel con Composer y tener `pdo_sqlite` activo.
- No se ha realizado prueba de carga ni evaluación de rendimiento productivo.
- No debe usarse con datos personales de clientes reales.

## Preguntas pendientes para Hielos del Maipo

1. ¿Qué dato utilizarán para identificar a cada cliente sin duplicar registros?
2. ¿Qué unidad y formatos de producto se manejan en los pedidos (bolsas, kilos, otros)?
3. ¿Cuáles son los estados reales de una solicitud y quién autoriza cambiarlos?
4. ¿Qué fecha es importante registrar: solicitud, despacho previsto u otra?
5. ¿Quién puede consultar, registrar, cambiar o anular las solicitudes?
6. ¿Qué relación deberá tener un pedido con producción, stock y reparto en una fase posterior?

## Evidencias incluidas

- `Evidencias/`: capturas de consultas HTTP, validaciones, persistencia, rendimiento y PHPUnit.
- `docs/EVU1_RAMIREZ_FRANCO.pdf`: informe técnico definitivo de la evaluación, con las principales capturas y resultados observados.
- `docs/Hielos_del_Maipo_U1.postman_collection.json`: colección para repetir las solicitudes HTTP.
- `database/migrations/` y `tests/Feature/SolicitudPedidoApiTest.php`: migración y pruebas automáticas.

Se omitieron deliberadamente `.env`, `vendor` y `database/database.sqlite`, dado que el código y las migraciones permiten recrear el proyecto de forma local.

## Uso de IA

Se empleó asistencia de IA (ChatGPT) para apoyo en análisis de pauta, propuesta inicial de arquitectura, redacción técnica y preparación de código y casos de prueba. Los estudiantes son responsables de comprender, adaptar, ejecutar, verificar y explicar todo lo presentado. Esta declaración debe mantenerse en el trabajo final, según la instrucción del docente.
