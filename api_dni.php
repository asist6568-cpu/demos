<?php
// ============================================
// API KEY SEGURA - NO SE EXPONE EN EL HTML
// ============================================
$API_KEY = 'c9fce1d6a32f5b5d0ca986f76595508c';

// Obtener DNI del parámetro GET
$dni = $_GET['dni'] ?? '';

// Validar DNI (8 dígitos)
if (!preg_match('/^\d{8}$/', $dni)) {
    http_response_code(400);
    echo json_encode([
        'code' => 400,
        'mensaje' => 'DNI inválido. Debe tener 8 dígitos.'
    ]);
    exit;
}

// Construir URL de Perú API
$url = "https://peruapi.com/api/dni/{$dni}?api_token={$API_KEY}";

// Inicializar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Solo si hay problemas SSL

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Verificar errores de cURL
if ($curlError) {
    http_response_code(500);
    echo json_encode([
        'code' => 500,
        'mensaje' => "Error de conexión: {$curlError}"
    ]);
    exit;
}

// Verificar HTTP status
if ($httpCode !== 200) {
    http_response_code($httpCode);
    echo json_encode([
        'code' => $httpCode,
        'mensaje' => "Error HTTP: {$httpCode}"
    ]);
    exit;
}

// Devolver la respuesta de Perú API
header('Content-Type: application/json');
echo $response;
?>