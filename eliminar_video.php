<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['ok' => false, 'msg' => 'No autorizado.']);
    exit;
}

include 'php/conexion.php';

$id_video = intval($_POST['id_video'] ?? 0);

if (!$id_video) {
    echo json_encode(['ok' => false, 'msg' => 'ID inválido.']);
    exit;
}

// Obtener el video para verificar permisos
$stmt = $conexion->prepare('SELECT ruta_video, id_usuario FROM videos_usuarios WHERE id_video = ?');
$stmt->bind_param('i', $id_video);
$stmt->execute();
$stmt->bind_result($ruta_video, $id_usuario_propietario);
$stmt->fetch();
$stmt->close();

if (!$ruta_video) {
    echo json_encode(['ok' => false, 'msg' => 'Video no encontrado.']);
    exit;
}

// Verificar permisos
if ($id_usuario_propietario !== $_SESSION['id_usuario'] && $_SESSION['id_usuario'] !== 1) {
    echo json_encode(['ok' => false, 'msg' => 'No tienes permiso para eliminar este video.']);
    exit;
}

// Eliminar de BD
$stmt = $conexion->prepare('DELETE FROM videos_usuarios WHERE id_video = ?');
$stmt->bind_param('i', $id_video);
if ($stmt->execute()) {
    // Intentar eliminar video del servidor
    if (file_exists($ruta_video)) {
        @unlink($ruta_video);
    }
    echo json_encode(['ok' => true, 'msg' => 'Video eliminado exitosamente.']);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al eliminar. Intenta de nuevo.']);
}
$stmt->close();
