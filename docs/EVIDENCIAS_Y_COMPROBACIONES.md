# Evidencias para acreditar nivel sobresaliente

**Importante:** esta plantilla NO equivale a resultados de pruebas ya ejecutadas. Reemplazar cada casilla pendiente con una captura, salida de terminal, fecha y resultado observados en el equipo del estudiante.

## Preparación de la evaluación

- [ ] Proyecto Laravel 12 completo y dependencias instaladas mediante Composer.
- [ ] `php artisan migrate` finaliza sin errores; se observa tabla `solicitud_pedidos` en SQLite.
- [ ] `php artisan route:list --path=api` muestra los seis métodos/rutas preparados.
- [ ] `php artisan test --filter=SolicitudPedidoApiTest` ejecutado; registrar número real de tests correctos y errores si hubiera.
- [ ] Crear repositorio de GitHub **privado** e invitar al docente mediante el procedimiento institucional.
- [ ] Subir código completo, `composer.json`, `composer.lock`, migraciones, tests, Postman, README e informe, pero **nunca** `.env`, `vendor` o contraseñas.

## Capturas de Postman / Insomnia y códigos HTTP

1. **POST 201**: crear solicitud ficticia; capturar cuerpo JSON e `id` recibido.
2. **GET 200**: consultar esa solicitud, misma referencia y cantidad.
3. **GET 200 listado**: capturar `data`, `current_page`, `total`, `per_page`.
4. **PUT 200**: enviar los cuatro campos y verificar cambios con GET.
5. **PATCH 200**: modificar solo `estado` y verificar datos anteriores intactos.
6. **POST 422**: enviar `cantidad: -2`, `estado: "despachado"`; capturar `errors.cantidad` y `errors.estado`.
7. **PUT 422**: enviar únicamente `estado` y capturar campos obligatorios ausentes.
8. **DELETE 204**: borrar solicitud. El cuerpo debe estar vacío.
9. **GET 404**: solicitar el identificador eliminado.
10. **Persistencia**: repetir GET del registro creado **antes y después** de reiniciar `php artisan serve`; no borrar el archivo SQLite entre ambos pasos.

## Revisión de calidad

| Criterio observable | Dónde se revisa | Resultado / evidencia a completar |
|---|---|---|
| Separación de responsabilidades | `routes/`, `Controllers/`, `Requests/`, `Models/` | Código organizado; revisar funcionamiento |
| Códigos y respuestas HTTP | Postman 201 / 200 / 204 / 404 / 422 | Pendiente de captura |
| Validación de datos | FormRequest y solicitud negativa | Pendiente de captura |
| Persistencia | Migración, ORM, reinicio de servidor | Pendiente de captura |
| Consulta limitada | Paginador, listado de 60 registros ficticios | Pendiente de captura |

**Mejora aplicada al código:** el listado usa `paginate(10)` con límite máximo de 50. Se evita enviar todas las solicitudes en una única respuesta. Registrar la comparación real que entregue `php scripts/comparar_paginacion.php` después de la carga de datos de prueba.

## Comparación antes / después de paginación

| Medición | Sin paginación (consulta de comparación) | Con paginación (solución) |
|---|---|---|
| Registros existentes | Anotar total real | Mismo total |
| Filas recuperadas | Anotar según script | Anotar según script |
| Tiempo local (ms, referencial) | Anotar según script | Anotar según script |
| Descripción | Consulta sin límite de resultados | Primera página con 10 registros |

El **antes** es una consulta Eloquent de comparación, no un endpoint público incluido en el prototipo. No atribuir una mejora temporal sin haberla medido, y no confundir menos filas con un test de carga.

## Revisión final de entrega

- [ ] La portada del PDF incluye título, asignatura, docente, estudiantes, institución y fecha.
- [ ] Arial 12; títulos 14; interlineado 1,15; justificado; número de página.
- [ ] Diagrama de 5 componentes mínimo; tabla de endpoints; esquema; criterios de calidad; seguridad conceptual; mejora de rendimiento.
- [ ] El informe tiene **resultados reales**, no capturas o cifras inventadas.
- [ ] Incluir una declaración de utilización de IA acorde a las instrucciones.
- [ ] Archivo final renombrado `EVU1_APELLIDO_NOMBRE.pdf` con los datos reales.
