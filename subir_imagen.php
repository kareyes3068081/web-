<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['ok' => false, 'msg' => 'No autorizado.']);
    exit;
}

include 'php/conexion.php';

$id_estados  = intval($_POST['id_estados']  ?? 0);
$nombre      = trim($_POST['nombre_platillo'] ?? '');
$descripcion = trim($_POST['descripcion']     ?? '');

// Validaciones de texto
if (!$id_estados) {
    echo json_encode(['ok' => false, 'msg' => 'Selecciona un estado.']);
    exit;
}
if (strlen($nombre) < 2) {
    echo json_encode(['ok' => false, 'msg' => 'El nombre del platillo es obligatorio.']);
    exit;
}
if (strlen($descripcion) < 5) {
    echo json_encode(['ok' => false, 'msg' => 'La descripción es obligatoria (mín. 5 caracteres).']);
    exit;
}

// Validación de imagen
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== 0) {
    echo json_encode(['ok' => false, 'msg' => 'La imagen es obligatoria.']);
    exit;
}

$ext_ok = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
$ext    = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $ext_ok)) {
    echo json_encode(['ok' => false, 'msg' => 'Solo se permiten imágenes JPG, PNG, WebP o AVIF.']);
    exit;
}

// Verificar que es imagen real (no solo extensión)
$tipo_mime = mime_content_type($_FILES['imagen']['tmp_name']);
if (!str_starts_with($tipo_mime, 'image/')) {
    echo json_encode(['ok' => false, 'msg' => 'El archivo no es una imagen válida.']);
    exit;
}

// Guardar imagen
$dir = 'img/usuarios_uploads/';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$nombre_archivo = uniqid('platillo_') . '.' . $ext;
$ruta_destino   = $dir . $nombre_archivo;

if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
    echo json_encode(['ok' => false, 'msg' => 'Error al guardar la imagen. Intenta de nuevo.']);
    exit;
}

// Insertar en BD
$id_usuario = $_SESSION['id_usuario'];
$stmt = $conexion->prepare(
    'INSERT INTO comidas (id_estados, nombre, descripcion, ruta_imagen, subida_por, es_usuario)
     VALUES (?, ?, ?, ?, ?, 1)'
);
$stmt->bind_param('isssi', $id_estados, $nombre, $descripcion, $ruta_destino, $id_usuario);

if ($stmt->execute()) {
    $id_comidas = $conexion->insert_id;
    echo json_encode(['ok' => true, 'msg' => 'Tu foto se ha subido exitosamente.', 'ruta' => $ruta_destino, 'id_comidas' => $id_comidas]);
} else {
    // Si falló BD, borrar imagen subida
    unlink($ruta_destino);
    echo json_encode(['ok' => false, 'msg' => 'Error en la base de datos. Intenta de nuevo.']);
}

$stmt->close();