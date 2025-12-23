<?php
// Configuración del error 404
$errorCode = '404';
$errorTitle = '¡Página No Encontrada!';
$errorMessage = 'No pudimos encontrar la página que estás buscando. La página puede haber sido movida o eliminada.';
$errorIcon = 'fa-exclamation-triangle';
$errorIconColor = 'text-warning';
$errorTitleColor = 'text-warning';
$showBackButton = true;
$showHomeButton = true;
$showRefreshButton = false;

// Cargar el layout de errores
require_once __DIR__ . '/layout.php';

