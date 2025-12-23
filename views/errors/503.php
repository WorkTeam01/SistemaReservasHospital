<?php
// Configuración del error 503
$errorCode = '503';
$errorTitle = '¡Servicio No Disponible!';
$errorMessage = 'El sistema está temporalmente fuera de servicio por mantenimiento. Por favor, inténtalo de nuevo en unos minutos.';
$errorIcon = 'fa-tools';
$errorIconColor = 'text-info';
$errorTitleColor = 'text-info';
$showBackButton = false;
$showHomeButton = true;
$showRefreshButton = true;

// Cargar el layout de errores
require_once __DIR__ . '/layout.php';

