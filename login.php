<?php
session_start();

if (isset($_SESSION['id_usuario'])) {
    header('Location: panel.php');
    exit;
}

include 'php/conexion.php';
include 'includes/header.php'; 


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim($_POST['correo']   ?? '');
    $password = $_POST['password']      ?? '';

    if ($correo && $password) {
        $stmt = $conexion->prepare(
            'SELECT id_usuario, nombre, apellidos, ruta_foto, password_hash FROM usuarios WHERE correo = ?'
        );
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $stmt->bind_result($id, $nombre, $apellidos, $ruta_foto, $hash);
        $stmt->fetch();
        $stmt->close();

        if ($id && password_verify($password, $hash)) {
            $_SESSION['id_usuario']  = $id;
            $_SESSION['nombre']      = $nombre;
            $_SESSION['apellidos']   = $apellidos;
            $_SESSION['ruta_foto']   = $ruta_foto;
            header('Location: panel.php');
            exit;
        } else {
            $error = 'Correo o contraseña incorrectos, intente de nuevo.';
        }
    } else {
        $error = 'Por favor completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso — Ruta del Sabor</title>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Playpen+Sans:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="card">
    <div class="logo">Ruta del Sabor</div>
    <p class="subtitulo">Panel de Administrador — Acceso</p>

    <?php if ($error): ?>
    <div class="error-msg">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo" required
                   placeholder="correo@ejemplo.com"
                   value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password"
                   required placeholder="Tu contraseña">
        </div>
        <button type="submit" class="btn-submit">Entrar</button>
    </form>

    <div class="divider">¿No tienes cuenta?</div>

    <a href="registro.php" class="btn-registro">Nuevo usuario</a>

    <div class="volver">
        <a href="index.php">← Volver al inicio</a>
    </div>
</div>

<script src="js/script.js"></script>
</body>
</html>