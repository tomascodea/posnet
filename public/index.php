<?php
// Cargar el autoload de Composer
require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Controllers\CardController;
use Controllers\PaymentController;

/**
 * Punto de entrada principal de la aplicación.
 * Configura Slim y define las rutas para el registro de tarjetas y el procesamiento de pagos.
 */

// Crear la aplicación Slim
$app = AppFactory::create();

// Definir las rutas de la API y los controladores
$app->post('/register-card', [CardController::class, 'registerCard']);
$app->post('/make-payment', [PaymentController::class, 'makePayment']);

// Ejecutar la aplicación
$app->run();
