<?php
// Configuración del error 500
$errorCode = '500';
$errorTitle = '¡Error Interno del Servidor!';
$errorMessage = 'Lo sentimos, algo salió mal en el servidor. Nuestro equipo ha sido notificado y está trabajando en solucionarlo.';
$errorIcon = 'fa-times-circle';
$errorIconColor = 'text-danger';
$errorTitleColor = 'text-danger';
$showBackButton = true;
$showHomeButton = true;
$showRefreshButton = true;

// Mostrar detalles técnicos solo en desarrollo
if (defined('APP_ENV') && APP_ENV === 'development' && isset($exception)) {
    $errorDetails = 'Mensaje: ' . $exception->getMessage() . '<br>' .
                   'Archivo: ' . $exception->getFile() . '<br>' .
                   'Línea: ' . $exception->getLine();
}

// Cargar el layout de errores
require_once __DIR__ . '/layout.php';

