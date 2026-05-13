<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
 
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['ok' => false, 'msg' => 'No autorizado.']);
    exit;
}
 
include 'php/conexion.php';
 
$id_estados = intval($_POST['id_estados_video'] ?? 0);
 
if (!$id_estados) {
    echo json_encode(['ok' => false, 'msg' => 'Selecciona un estado para el video.']);
    exit;
}
 
if (!isset($_FILES['video']) || $_FILES['video']['error'] !== 0) {
    echo json_encode(['ok' => false, 'msg' => 'El video es obligatorio.']);
    exit;
}
 
// Solo MP4
$ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
if ($ext !== 'mp4') {
    echo json_encode(['ok' => false, 'msg' => 'Solo se permiten archivos MP4.']);
    exit;
}
 
// Verificar MIME
$mime = mime_content_type($_FILES['video']['tmp_name']);
$mimes_ok = ['video/mp4', 'application/octet-stream'];
if (!in_array($mime, $mimes_ok) && !str_contains($mime, 'video')) {
    echo json_encode(['ok' => false, 'msg' => 'El archivo no es un video MP4 válido.']);
    exit;
}
 
// Guardar
$dir = 'img/videos_usuarios/';
if (!is_dir($dir)) mkdir($dir, 0755, true);
 
$nombre_archivo = uniqid('video_') . '.mp4';
$ruta_destino   = $dir . $nombre_archivo;
 
if (!move_uploaded_file($_FILES['video']['tmp_name'], $ruta_destino)) {
    echo json_encode(['ok' => false, 'msg' => 'Error al guardar el video. Intenta de nuevo.']);
    exit;
}
 
$id_usuario = $_SESSION['id_usuario'];
$stmt = $conexion->prepare(
    'INSERT INTO videos_usuarios (id_estados, id_usuario, ruta_video) VALUES (?, ?, ?)'
);
$stmt->bind_param('iis', $id_estados, $id_usuario, $ruta_destino);
 
if ($stmt->execute()) {
    $id_video = $conexion->insert_id;
    echo json_encode(['ok' => true, 'msg' => 'Tu video se ha subido exitosamente.', 'ruta' => $ruta_destino, 'id_video' => $id_video]);
} else {
    unlink($ruta_destino);
    echo json_encode(['ok' => false, 'msg' => 'Error en la base de datos. Intenta de nuevo.']);
}
 
$stmt->close();