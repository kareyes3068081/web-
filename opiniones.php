<?php
session_start();

// Protección de ruta (Solo admins)
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

include 'php/conexion.php';

$nombre    = htmlspecialchars($_SESSION['nombre']);
$apellidos = htmlspecialchars($_SESSION['apellidos']);
$foto      = $_SESSION['ruta_foto'] ?? null;

// Obtener todas las opiniones ordenadas por la más reciente
$opiniones = [];
$res = $conexion->query('SELECT * FROM contactos ORDER BY created_at DESC');
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $opiniones[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opiniones — Ruta del Sabor</title>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Playpen+Sans:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="panel-layout">
    <aside class="panel-sidebar">
        <div class="avatar-wrap">
            <?php if ($foto): ?>
                <img src="<?= htmlspecialchars($foto) ?>" alt="Foto de perfil">
            <?php else: ?>
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#c89f9f" stroke-width="1.5">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
            <?php endif; ?>
        </div>

        <div class="bienvenida">
            Hola,<span><?= $nombre ?></span>
        </div>

        <hr class="sidebar-divider">

        <nav class="sidebar-nav">
            <a href="index.php"> Inicio</a>
            <a href="panel.php"> Panel Admin</a>
            <a href="estadisticas.php"> Estadísticas</a>
            <a href="opiniones.php" class="active"> Opiniones</a>
            <a href="logout.php" class="danger"> Cerrar sesión</a>
        </nav>
    </aside>

    <main class="panel-main">
        <div class="panel-titulo">Bandeja de Opiniones</div>
        <p class="panel-sub">Retroalimentación, comentarios y recetas compartidas por los usuarios.</p>

        <?php if (empty($opiniones)): ?>
            <div style="text-align: center; padding: 50px; color: #b8a0a0;">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom:15px">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <p>Aún no hay mensajes en la bandeja.</p>
            </div>
        <?php else: ?>
            <div class="opiniones-grid">
                <?php foreach ($opiniones as $op): 
                    // Generar estrellitas
                    $rating = intval($op['rating']);
                    $estrellas = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                    $fecha = date('d/m/Y', strtotime($op['created_at']));
                ?>
                    <div class="opinion-card">
                        <div class="op-header">
                            <div>
                                <div class="op-nombre"><?= htmlspecialchars($op['nombre']) ?></div>
                                <div class="op-fecha"><?= $fecha ?></div>
                            </div>
                            <div class="op-rating" title="Calificación: <?= $rating ?>/5"><?= $estrellas ?></div>
                        </div>
                        
                        <div class="op-body">
                            <div class="op-asunto"><?= htmlspecialchars($op['asunto']) ?></div>
                            <div class="op-mensaje">"<?= nl2br(htmlspecialchars($op['mensaje'])) ?>"</div>
                        </div>

                        <div class="op-footer">
                            <span class="op-tag">📍 <?= htmlspecialchars($op['estado_favorito']) ?></span>
                            <div class="op-contacto">
                                ✉️ <a href="mailto:<?= htmlspecialchars($op['correo']) ?>"><?= htmlspecialchars($op['correo']) ?></a>
                                <?php if (!empty($op['telefono'])): ?>
                                    <br>📞 <?= htmlspecialchars($op['telefono']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>