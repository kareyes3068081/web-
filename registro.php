<?php 

session_start();

if (isset($_SESSION['id_usuario'])) {
    header('Location: panel.php');
    exit;
}

include 'php/conexion.php';
include 'includes/header.php'; 

$errores = [];
$exito   = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre    = trim($_POST['nombre']    ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $genero    = trim($_POST['genero']    ?? '');
    $correo    = trim($_POST['correo']    ?? '');
    $password  = $_POST['password']       ?? '';
    $confirm   = $_POST['confirm']        ?? '';

    // Validaciones
    if (strlen($nombre)    < 2) $errores[] = 'El nombre debe tener al menos 2 caracteres.';
    if (strlen($apellidos) < 2) $errores[] = 'Los apellidos deben tener al menos 2 caracteres.';
    if (!$genero)               $errores[] = 'Selecciona un género.';
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = 'Correo inválido.';
    if (strlen($password) < 6) $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    if ($password !== $confirm) $errores[] = 'Las contraseñas no coinciden.';

    // Verificar correo duplicado
    if (empty($errores)) {
        $stmt = $conexion->prepare('SELECT id_usuario FROM usuarios WHERE correo = ?');
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) $errores[] = 'Ese correo ya está registrado.';
        $stmt->close();
    }

    // Foto de perfil
    $ruta_foto = null;
    if (empty($errores) && isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $ext_ok = ['jpg','jpeg','png','webp','gif'];
        $ext    = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ext_ok)) {
            $errores[] = 'La foto debe ser JPG, PNG o WebP.';
        } else {
            $dir = 'img/usuarios/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $nombre_archivo = uniqid('usr_') . '.' . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], $dir . $nombre_archivo);
            $ruta_foto = $dir . $nombre_archivo;
        }
    }

    // Insertar usuario
    if (empty($errores)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare(
            'INSERT INTO usuarios (nombre, apellidos, genero, correo, password_hash, ruta_foto)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('ssssss', $nombre, $apellidos, $genero, $correo, $hash, $ruta_foto);
        if ($stmt->execute()) {
            $exito = true;
        } else {
            $errores[] = 'Error al guardar. Intenta de nuevo.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro — Ruta del Sabor</title>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Playpen+Sans:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php if ($exito): ?>
<div class="popup-overlay visible" id="popupExito" style="opacity: 1; visibility: visible;">
    <div class="popup-box">
        <span class="popup-ico">✅</span> <div class="popup-titulo">¡Cuenta creada!</div>
        <p class="popup-texto">
            Tu cuenta se ha registrado exitosamente.<br>
            Ya puedes iniciar sesión con tu correo y contraseña.
        </p>
        <button class="popup-btn" onclick="window.location.href='login.php'">
            ✕ &nbsp; Ir al login
        </button>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="logo">Ruta del Sabor</div>
    <p class="subtitulo">Crea tu cuenta de administrador</p>

    <?php if (!empty($errores)): ?>
    <div class="errores">
        <ul>
            <?php foreach ($errores as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label class="foto-label" for="foto">
            <div class="foto-preview" id="fotoPreview">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                     stroke="#c89f9f" stroke-width="1.5">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
            </div>
            <span class="foto-hint">Toca para subir foto de perfil</span>
            <input type="file" id="foto" name="foto" accept="image/*"
                   onchange="previsualizarFoto(this)">
        </label>

        <div class="grid-2">
            <div class="form-group">
                <label for="nombre">Nombre <span>*</span></label>
                <input type="text" id="nombre" name="nombre" required
                       value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                       placeholder="Ej: Sergio Uriel">
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos <span>*</span></label>
                <input type="text" id="apellidos" name="apellidos" required
                       value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>"
                       placeholder="Ej: Martínez Huerta">
            </div>
            <div class="form-group full">
                <label for="genero">Género <span>*</span></label>
                <select id="genero" name="genero" required>
                    <option value="">— Selecciona —</option>
                    <option value="Masculino"  <?= (($_POST['genero'] ?? '') === 'Masculino')  ? 'selected' : '' ?>>Masculino</option>
                    <option value="Femenino"   <?= (($_POST['genero'] ?? '') === 'Femenino')   ? 'selected' : '' ?>>Femenino</option>
                    <option value="No binario" <?= (($_POST['genero'] ?? '') === 'No binario') ? 'selected' : '' ?>>No binario</option>
                    <option value="Prefiero no decirlo" <?= (($_POST['genero'] ?? '') === 'Prefiero no decirlo') ? 'selected' : '' ?>>Prefiero no decirlo</option>
                </select>
            </div>
            <div class="form-group full">
                <label for="correo">Correo electrónico <span>*</span></label>
                <input type="email" id="correo" name="correo" required
                       value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>"
                       placeholder="correo@ejemplo.com">
            </div>
            <div class="form-group">
                <label for="password">Contraseña <span>*</span></label>
                <input type="password" id="password" name="password"
                       required minlength="6" placeholder="Mín. 6 caracteres">
            </div>
            <div class="form-group">
                <label for="confirm">Confirmar contraseña <span>*</span></label>
                <input type="password" id="confirm" name="confirm"
                       required placeholder="Repite la contraseña">
            </div>
        </div>

        <button type="submit" class="btn-submit">Crear cuenta</button>
    </form>

    <div class="link-login">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
    </div>
</div>

<script src="js/script.js"></script>
</body>
</html>