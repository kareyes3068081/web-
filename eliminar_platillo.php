<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['ok' => false, 'msg' => 'No autorizado.']);
    exit;
}

include 'php/conexion.php';

$id_comida = intval($_POST['id_comida'] ?? 0);

if (!$id_comida) {
    echo json_encode(['ok' => false, 'msg' => 'ID inválido.']);
    exit;
}

// Obtener la comida para verificar si es del usuario y obtener ruta
$stmt = $conexion->prepare('SELECT ruta_imagen, subida_por FROM comidas WHERE id_comidas = ?');
$stmt->bind_param('i', $id_comida);
$stmt->execute();
$stmt->bind_result($ruta_imagen, $subida_por);
$stmt->fetch();
$stmt->close();

if (!$ruta_imagen) {
    echo json_encode(['ok' => false, 'msg' => 'Platillo no encontrado.']);
    exit;
}

// Verificar que sea el usuario que lo subió o que sea admin (id_usuario = 1)
if ($subida_por !== $_SESSION['id_usuario'] && $_SESSION['id_usuario'] !== 1) {
    echo json_encode(['ok' => false, 'msg' => 'No tienes permiso para eliminar este platillo.']);
    exit;
}

// Eliminar de BD
$stmt = $conexion->prepare('DELETE FROM comidas WHERE id_comidas = ?');
$stmt->bind_param('i', $id_comida);
if ($stmt->execute()) {
    // Intentar eliminar imagen del servidor
    if (file_exists($ruta_imagen)) {
        @unlink($ruta_imagen);
    }
    echo json_encode(['ok' => true, 'msg' => 'Platillo eliminado exitosamente.']);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al eliminar. Intenta de nuevo.']);
}
$stmt->close();
