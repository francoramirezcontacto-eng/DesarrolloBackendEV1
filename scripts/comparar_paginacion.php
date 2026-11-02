<?php

// Se ejecuta desde la carpeta del proyecto: php scripts/comparar_paginacion.php
// Requiere que Laravel y las migraciones estén instalados y que existan datos de prueba.

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SolicitudPedido;

$inicio = microtime(true);
$sinLimite = SolicitudPedido::orderByDesc('id')->get();
$tiempoSinLimite = round((microtime(true) - $inicio) * 1000, 2);

$inicio = microtime(true);
$conPagina = SolicitudPedido::orderByDesc('id')->paginate(10);
$tiempoConPagina = round((microtime(true) - $inicio) * 1000, 2);

echo 'Registros totales en BD: '.SolicitudPedido::count().PHP_EOL;
echo 'Consulta sin paginación: '.$sinLimite->count().' registros, '.$tiempoSinLimite.' ms'.PHP_EOL;
echo 'Consulta con paginación: '.$conPagina->count().' registros, '.$tiempoConPagina.' ms'.PHP_EOL;
echo 'Páginas disponibles: '.$conPagina->lastPage().PHP_EOL;
echo 'Nota: los tiempos locales son orientativos, no constituyen una prueba de carga.'.PHP_EOL;
