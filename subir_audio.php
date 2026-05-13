<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include 'php/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['ok' => false, 'msg' => 'No autorizado.']);
    exit;
}

$id_estados = intval($_POST['id_estados_audio'] ?? 0);
if (!$id_estados || !isset($_FILES['audio'])) {
    echo json_encode(['ok' => false, 'msg' => 'Datos incompletos.']);
    exit;
}

$ext = strtolower(pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ['mp3', 'wav', 'ogg'])) {
    echo json_encode(['ok' => false, 'msg' => 'Formato no permitido (Solo MP3, WAV, OGG).']);
    exit;
}

$dir = 'img/audios_usuarios/';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$ruta_destino = $dir . uniqid('audio_') . '.' . $ext;
$ruta_destino = str_replace('\\', '/', $ruta_destino); // Ruta limpia

if (move_uploaded_file($_FILES['audio']['tmp_name'], $ruta_destino)) {
    $stmt = $conexion->prepare('INSERT INTO audios_usuarios (id_estados, id_usuario, ruta_audio) VALUES (?, ?, ?)');
    $id_usuario = $_SESSION['id_usuario'];
    $stmt->bind_param('iis', $id_estados, $id_usuario, $ruta_destino);
    
    if ($stmt->execute()) {
        echo json_encode(['ok' => true, 'msg' => 'Audio subido.', 'ruta' => $ruta_destino, 'id_audio' => $conexion->insert_id]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error en BD.']);
    }
    $stmt->close();
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al mover archivo.']);
}