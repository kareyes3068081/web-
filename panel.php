<?php
session_start();

// Protección de ruta
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

include 'php/conexion.php';


$nombre    = htmlspecialchars($_SESSION['nombre']);
$apellidos = htmlspecialchars($_SESSION['apellidos']);
$foto      = $_SESSION['ruta_foto'] ?? null;

// Obtener todos los estados para los selectores
$estados = [];
$res = $conexion->query('SELECT id_estados, nombre FROM estados ORDER BY nombre ASC');
while ($row = $res->fetch_assoc()) $estados[] = $row;

// Estado seleccionado actualmente (para precargar imágenes/videos)
$id_sel = intval($_GET['estado'] ?? ($estados[0]['id_estados'] ?? 1));


// Imágenes del estado seleccionado (originales + usuario)
$imagenes = [];
$stmt = $conexion->prepare(
    'SELECT id_comidas, nombre, descripcion, ruta_imagen, subida_por FROM comidas WHERE id_estados = ? ORDER BY id_comidas ASC'
);
$stmt->bind_param('i', $id_sel);
$stmt->execute();
$res2 = $stmt->get_result();
while ($row = $res2->fetch_assoc()) $imagenes[] = $row;
$stmt->close();

// Video original del estado (Formateado como objeto para JS)
$vid_original = null;
$stmt2 = $conexion->prepare('SELECT ruta_video FROM estados WHERE id_estados = ?');
$stmt2->bind_param('i', $id_sel);
$stmt2->execute();
$stmt2->bind_result($vid_original_ruta);
if ($stmt2->fetch() && !empty($vid_original_ruta)) {
    $vid_original = ['id' => null, 'ruta' => $vid_original_ruta];
}
$stmt2->close();

// Videos de usuarios para este estado (Traemos el ID para poder borrar)
$videos_extra = [];
$stmt3 = $conexion->prepare(
    'SELECT id_video, ruta_video FROM videos_usuarios WHERE id_estados = ? ORDER BY created_at ASC'
);
$stmt3->bind_param('i', $id_sel);
$stmt3->execute();
$res3 = $stmt3->get_result();
while ($row = $res3->fetch_assoc()) {
    $videos_extra[] = ['id' => $row['id_video'], 'ruta' => $row['ruta_video']];
}
$stmt3->close();

// Unimos todo
$todos_videos = [];
if ($vid_original) $todos_videos[] = $vid_original;
$todos_videos = array_merge($todos_videos, $videos_extra);

// Nombre del estado seleccionado
$nombre_estado = '';
foreach ($estados as $e) {
    if ($e['id_estados'] == $id_sel) { $nombre_estado = $e['nombre']; break; }
}

$json_imagenes = json_encode($imagenes);
$json_videos   = json_encode($todos_videos);



//audio
//
// 1. Audio original del estado 
$audio_base = null;
$stmt_a = $conexion->prepare('SELECT ruta_audio FROM estados WHERE id_estados = ?');
$stmt_a->bind_param('i', $id_sel);
$stmt_a->execute();
$stmt_a->bind_result($ruta_audio_base);
if ($stmt_a->fetch() && !empty($ruta_audio_base)) {
    // Limpiamos la ruta de una vez
    $audio_base = ['id_audio' => null, 'ruta_audio' => str_replace('\\', '/', $ruta_audio_base)];
}
$stmt_a->close();

// 2. Audios subidos por usuarios
$audios_extra = [];
$stmt_u = $conexion->prepare('SELECT id_audio, ruta_audio FROM audios_usuarios WHERE id_estados = ? ORDER BY created_at ASC');
$stmt_u->bind_param('i', $id_sel);
$stmt_u->execute();
$res_u = $stmt_u->get_result();
while ($row = $res_u->fetch_assoc()) {
    $row['ruta_audio'] = str_replace('\\', '/', $row['ruta_audio']);
    $audios_extra[] = $row;
}
$stmt_u->close();

// 3. Unir todo en una sola lista para el JavaScript
$todos_audios = [];
if ($audio_base) $todos_audios[] = $audio_base;
$todos_audios = array_merge($todos_audios, $audios_extra);

$json_audios = json_encode($todos_audios);
// --- FIN CONSULTA DE AUDIOS ---


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración — Ruta del Sabor</title>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Playpen+Sans:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="popup-overlay" id="popup">
    <div class="popup-box">
        <span class="popup-ico" id="popupIco">✅</span>
        <div class="popup-titulo" id="popupTitulo">¡Listo!</div>
        <p class="popup-texto" id="popupTexto">Operación realizada.</p>
        <button class="popup-btn" onclick="cerrarPopup()">✕ &nbsp; Cerrar</button>
    </div>
</div>

<div class="panel-layout">

    <aside class="panel-sidebar">

        <div class="avatar-wrap">
            <?php if ($foto): ?>
                <img src="<?= htmlspecialchars($foto) ?>" alt="Foto de perfil">
            <?php else: ?>
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                     stroke="#c89f9f" stroke-width="1.5">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
            <?php endif; ?>
        </div>

        <div class="bienvenida">
            Hola,<span><?= $nombre ?></span>
        </div>

        <hr class="sidebar-divider">

        <div class="sidebar-titulo">Estado activo</div>
        <select class="estado-select" id="selectorEstadoGlobal" onchange="cambiarEstado(this.value)">
            <?php foreach ($estados as $e): ?>
            <option value="<?= $e['id_estados'] ?>"
                <?= $e['id_estados'] == $id_sel ? 'selected' : '' ?>>
                <?= htmlspecialchars($e['nombre']) ?>
            </option>
            <?php endforeach; ?>
        </select>

        <hr class="sidebar-divider">

        <nav class="sidebar-nav">
            <a href="index.php"> Inicio</a>
            <a href="estadisticas.php">Estadísticas</a>
            <a href="opiniones.php"> Opiniones</a>
            <a href="logout.php" class="danger"> Cerrar sesión</a>
        </nav>
    </aside>

    <main class="panel-main">
        <div class="panel-titulo">Panel de Administración</div>
        <p class="panel-sub">Estado activo: <strong><?= htmlspecialchars($nombre_estado) ?></strong></p>

        <div class="seccion">
            <div class="seccion-titulo">Galería de platillos</div>

            <div style="position:relative;">
                <div class="carrusel-wrap">
                    <div class="carrusel-track" id="carruselTrack"></div>
                </div>
                <button class="carrusel-flecha prev" id="flechaImgPrev" onclick="moverCarrusel(-1)">&#8592;</button>
                <button class="carrusel-flecha next" id="flechaImgNext" onclick="moverCarrusel(1)">&#8594;</button>
            </div>
            <div class="carrusel-dots" id="carruselDots"></div>

            <hr class="sec">

            <div class="seccion-titulo" style="font-size:1.1rem;margin-bottom:14px;">
                Añadir nuevo platillo
            </div>

            <form id="formImagen" enctype="multipart/form-data">
                <div class="upload-form">

                    <div class="form-group">
                        <label>Nombre del platillo <span style="color:#d34a3b">*</span></label>
                        <input type="text" id="nombrePlatillo" name="nombre_platillo"
                               placeholder="Ej: Tacos de canasta" maxlength="100">
                    </div>

                    <div class="form-group">
                        <label>Estado <span style="color:#d34a3b">*</span></label>
                        <select id="estadoImagen" name="id_estados">
                            <?php foreach ($estados as $e): ?>
                            <option value="<?= $e['id_estados'] ?>"
                                <?= $e['id_estados'] == $id_sel ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['nombre']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label>Descripción <span style="color:#d34a3b">*</span></label>
                        <textarea id="descPlatillo" name="descripcion" rows="3"
                                  placeholder="Describe brevemente el platillo…" maxlength="250"></textarea>
                    </div>

                    <div class="form-group full">
                        <label>Imagen <span style="color:#d34a3b">*</span> — Solo JPG, PNG, WebP</label>
                        <div class="drop-zona" id="dropZonaImg"
                             ondragover="event.preventDefault(); this.classList.add('dragover')"
                             ondragleave="this.classList.remove('dragover')"
                             ondrop="event.preventDefault(); this.classList.remove('dragover'); handleDropImg(event)">
                            <input type="file" id="inputImagen" name="imagen"
                                   accept=".jpg,.jpeg,.png,.webp,.avif"
                                   onchange="previsualizarImg(this)">
                            <img class="drop-zona-preview" id="prevImg" alt="Vista previa">
                            <p>
                                <strong>Arrastra</strong> la imagen aquí o usa el botón<br>
                                <small>JPG · PNG · WebP · AVIF</small>
                            </p>
                            <button type="button" class="drop-zona-btn"
                                    onclick="document.getElementById('inputImagen').click()">
                                 Seleccionar imagen
                            </button>
                        </div>
                    </div>

                    <div class="error-inline" id="errImagen"></div>

                    <div class="full" style="display:flex;gap:12px;align-items:center;">
                        <button type="button" class="btn-subir" onclick="subirImagen()" id="btnSubirImg">
                             Subir imagen
                        </button>
                        <div class="spinner" id="spinnerImg"></div>
                    </div>

                </div>
            </form>
        </div>

        <div class="seccion">
            <div class="seccion-titulo">Videos del estado</div>

            <div class="video-carrusel-wrap">
                <div class="video-track" id="videoTrack"></div>
                <button class="video-flecha prev" id="flechaVidPrev" onclick="moverVideoCarrusel(-1)">&#8592;</button>
                <button class="video-flecha next" id="flechaVidNext" onclick="moverVideoCarrusel(1)">&#8594;</button>
            </div>
            <div class="video-contador" id="videoContador"></div>

            <hr class="sec">

            <div class="seccion-titulo" style="font-size:1.1rem;margin-bottom:14px;">
                Añadir un nuevo video
            </div>

            <form id="formVideo" enctype="multipart/form-data">
                <div class="upload-form">

                    <div class="form-group full">
                        <label>Estado del video <span style="color:#d34a3b">*</span></label>
                        <select id="estadoVideo" name="id_estados_video">
                            <?php foreach ($estados as $e): ?>
                            <option value="<?= $e['id_estados'] ?>"
                                <?= $e['id_estados'] == $id_sel ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['nombre']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label>Video <span style="color:#d34a3b">*</span> — Solo MP4</label>
                        <div class="drop-zona" id="dropZonaVid"
                             ondragover="event.preventDefault(); this.classList.add('dragover')"
                             ondragleave="this.classList.remove('dragover')"
                             ondrop="event.preventDefault(); this.classList.remove('dragover'); handleDropVid(event)">
                            <input type="file" id="inputVideo" name="video"
                                   accept=".mp4,video/mp4"
                                   onchange="previsualizarVideo(this)">
                            <p id="dropVidTexto">
                                <strong>Arrastra</strong> el video aquí o usa el botón<br>
                                <small>Solo MP4</small>
                            </p>
                            <button type="button" class="drop-zona-btn"
                                    onclick="document.getElementById('inputVideo').click()">
                                 Seleccionar video
                            </button>
                        </div>
                    </div>

                    <div class="error-inline" id="errVideo"></div>

                    <div class="full" style="display:flex;gap:12px;align-items:center;">
                        <button type="button" class="btn-subir" onclick="subirVideo()" id="btnSubirVid">
                             Subir video
                        </button>
                        <div class="spinner" id="spinnerVid"></div>
                    </div>

                </div>
            </form>
        </div>



                        <div class="seccion">
                    <div class="seccion-titulo">Audios del estado</div>
                    
                    <div class="video-carrusel-wrap" style="height: 160px !important; min-height: 160px !important; background: #f5ede6;">
                        <div id="audioTrack" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                            </div>
                        
                        <button class="video-flecha prev" id="flechaAudioPrev" onclick="moverAudioCarrusel(-1)">‹</button>
                        <button class="video-flecha next" id="flechaAudioNext" onclick="moverAudioCarrusel(1)">›</button>
                    </div>
                    
                    <div class="video-contador" id="audioContador"></div>

                    <hr class="sec">

                    <div class="seccion-titulo" style="font-size:1.1rem;margin-bottom:14px;">Añadir un nuevo audio</div>
                    
                    <form id="formAudio" enctype="multipart/form-data">
                        <input type="hidden" name="id_estados_audio" value="<?= $id_sel ?>">
                        
                        <div class="upload-form">
                            <div class="form-group full">
                                <label>AUDIO * — SOLO MP3/WAV</label>
                                <label class="video-drop-zone" for="inputAudio" id="audioDropZone" style="height: 120px;">
                                    <div class="drop-zone-content">
                                        <span style="color: #d34a3b; font-size: 24px;"></span>
                                        <p><strong>Arrastra</strong> el audio aquí o usa el botón</p>
                                        <small>Solo MP3 o WAV</small>
                                    </div>
                                    <input type="file" name="audio" accept=".mp3,.wav" id="inputAudio" style="display:none;" onchange="actualizarNombreAudio(this)">
                                </label>
                                <div id="nombreArchivoAudio" style="text-align:center; margin-top:10px; font-size:0.85rem; color:#7a5c5c;"></div>
                            </div>
                        </div>

                        <button type="button" class="btn-submit" style="margin-top:20px;" onclick="subirAudio()">
                             Subir Audio
                        </button>
                    </form>
                </div>


    </main>
</div>

<script>
    let imagenes = <?= $json_imagenes ?>;
    let videos   = <?= $json_videos ?>;
    const audios = <?= $json_audios ?>;
</script>
<script src="js/script.js"></script>

<script src="js/panel.js"></script>
</body>
</html>