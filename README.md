# Hielos del Maipo - Backend de solicitudes (U1)

Primera API REST de registro de **solicitudes de pedido**, desarrollada como prototipo académico para Desarrollo Backend (IF201IINF). Se utiliza una sola entidad, Laravel 12, Eloquent ORM y SQLite. No es una tienda en línea y no implementa reglas empresariales todavía no validadas.

**Estado de verificación:** el código PHP incluido fue comprobado sintácticamente. El funcionamiento completo de Laravel, sus dependencias y la base de datos **debe ejecutarse y comprobarse en el equipo de los estudiantes** antes de entregar la evaluación. El repositorio no incluye `vendor` ni una instalación completa del framework.

## Requisitos

- PHP compatible con Laravel 12 (8.2 a 8.5), extensiones habituales de Laravel y `pdo_sqlite`/`sqlite3` habilitadas.
- Composer, conexión para instalar las dependencias inicialmente y una terminal (PowerShell o terminal integrada de VS Code).
- Postman, Insomnia o curl para las pruebas HTTP.
- Git y una cuenta de GitHub para subir el repositorio privado.

Documentación oficial: https://laravel.com/docs/12.x/installation

## Instalación (Windows, PowerShell)

1. Crear una instalación limpia del framework en una carpeta de trabajo:

   ```powershell
   composer create-project laravel/laravel hielos-backend "^12.0"
   cd hielos-backend
   ```

2. **Copiar el contenido de este paquete a la raíz `hielos-backend`**, reemplazando cuando corresponda `bootstrap/app.php`; se conservan los otros archivos creados por Laravel (`artisan`, `composer.json`, `config/`, `vendor/`, `public/`, `tests/TestCase.php`, etc.). No copiar una carpeta contenedora dentro de otra.

3. Revisar que la extensión `pdo_sqlite` esté disponible (`php -m`). Crear el archivo de base de datos:

   ```powershell
   New-Item -ItemType File -Force database/database.sqlite
   ```

4. Revisar el `.env` que generó Laravel, configurar `DB_CONNECTION=sqlite` y `DB_DATABASE` con la ruta absoluta de `database.sqlite` si tu instalación lo necesita. El archivo `.env.evaluacion.example` aporta valores de referencia. **No reemplazar accidentalmente el `APP_KEY` generado por Laravel**. En caso de no existir:

   ```powershell
   php artisan key:generate
   ```

5. Crear las tablas y arrancar la API:

   ```powershell
   php artisan migrate
   php artisan route:list --path=api
   php artisan serve
   ```

6. En Postman abrir `docs/Hielos_del_Maipo_U1.postman_collection.json`, ejecutar peticiones con `http://127.0.0.1:8000`. Los mensajes deben enviarse como JSON e incluir `Accept: application/json`.

7. Para ejecutar pruebas automáticas en un **segundo terminal**:

   ```powershell
   php artisan test --filter=SolicitudPedidoApiTest
   ```

   Los tests usan `RefreshDatabase`; en entornos de prueba Laravel puede trabajar con una base de datos separada. No ejecutar `migrate:fresh` contra datos importantes.

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

## Evidencias mínimas para entregar

Consultar `docs/EVIDENCIAS_Y_COMPROBACIONES.md` y completar el informe técnico con resultados reales. Adjuntar código fuente completo del proyecto Laravel junto a su `composer.json` y `composer.lock` generados, pero sin `/vendor`, `.env` ni base de datos con datos sensibles.

## Uso de IA

Se empleó asistencia de IA (ChatGPT) para apoyo en análisis de pauta, propuesta inicial de arquitectura, redacción técnica y preparación de código y casos de prueba. Los estudiantes son responsables de comprender, adaptar, ejecutar, verificar y explicar todo lo presentado. Esta declaración debe mantenerse en el trabajo final, según la instrucción del docente.
