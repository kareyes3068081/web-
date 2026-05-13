<?php
include 'php/conexion.php'; 

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Recibir los datos del formulario
    $nombre   = $_POST['nombre'] ?? '';
    $correo   = $_POST['email'] ?? ''; 
    $telefono = $_POST['telefono'] ?? null; // Puede ir nulo si el usuario no lo llena
    $estado   = $_POST['estado'] ?? '';
    $asunto   = $_POST['asunto'] ?? '';
    $mensaje  = $_POST['mensaje'] ?? '';
    $rating   = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

    // 2. Preparar la consulta SQL para evitar inyección SQL
    // Nota: 'estado_favorito' y 'correo' son los nombres en tu BD
    $sql = "INSERT INTO contactos (nombre, correo, telefono, estado_favorito, asunto, mensaje, rating) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conexion->prepare($sql);
    
    if ($stmt) {
        // "ssssssi" significa: 6 strings y 1 integer
        $stmt->bind_param("ssssssi", $nombre, $correo, $telefono, $estado, $asunto, $mensaje, $rating);
        
        // 3. Ejecutar y responder a JavaScript
        if ($stmt->execute()) {
            echo json_encode(["exito" => true, "mensaje" => "Guardado correctamente"]);
        } else {
            echo json_encode(["exito" => false, "mensaje" => "Error al guardar en la base de datos: " . $stmt->error]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Error en la preparación de la consulta SQL."]);
    }
    
    $conexion->close();
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido."]);
}
?>