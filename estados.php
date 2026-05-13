<?php include 'includes/header_sinHm.php'; ?>
        <?php
        //conexión a la base de datos
        include 'php/conexion.php';

        // 2. ID DINAMICA
        $id_estado_actual = intval($_GET['id_estados'] ?? 1);

        $estados_colores = [
        26 =>'#9DB4CC',  // Azul claro
        1 => '#7A9ECC',  // Azul medio
        6 => '#5A88B8',  // Azul más oscuro
        25 => '#c6ddff',  // Azul oscuro (Baja California Sur)
        3 => '#4791ff62',  // Azul muy oscuro
       
        // Tonos rosas/rojos
        13 => '#E89B9B',  // Rosa claro
        24 => '#D67A7A',  // Rosa medio
        28 => '#ee9494d8',  // Rosa más oscuro
        19 => '#f7a29e',  // Rojo oscuro
        5 => '#b39d9d',  // Rojo muy oscuro
        30 => '#ffcece',  // Rojo marrón
       
        // Tonos beige/crema
        8 => '#F5EBD9',  // Beige muy claro
        2 => '#EDD5B5',  // Beige claro
        15=> '#E8C99D',  // Beige medio
       
        // Tonos naranja/melocotón
        10 => '#F5B897',  // Naranja claro
        18 => '#E89A77',  // Naranja medio
       
        // Tonos verdes
        27 => '#C4D9A8',  // Verde oliva claro
        4 => '#A8C48C',  // Verde oliva medio
        23 => '#9daf8e',  // Verde oscuro
        31 => '#94b389',  // Verde muy oscuro
       
        // Tonos turquesa/cian
        32 => '#A8D4D9',  // Turquesa claro
        12 => '#88C4CC',  // Turquesa medio
        14 => '#6ABAB8',  // Turquesa oscuro
       
        // Tonos amarillo/dorado
        16 => '#F5D9A8',  // Amarillo claro
        9 => '#E8C585',  // Amarillo medio
        7 => '#DDB877',  // Amarillo oscuro
       
        // Tonos grises
        20 => '#9BA8B8',  // Gris azulado
        11 => '#B8B8B8',   // Gris neutro

        17 => '#D6BFA2',  // Beige cálido intermedio
        21 => '#7FA6A3',  // Verde azulado suave
        22 => '#C6A6D9',  // Lavanda suave (añade variedad sin romper armonía)
        29 => '#83a5db',  // Azul intermedio entre medio y oscuro
           
        ];

        $color_dinamico = $estados_colores[$id_estado_actual] ?? '#fdf8f8';

        // Los datos del estado
        $query_estado = "SELECT * FROM estados WHERE id_estados = $id_estado_actual";
        $resultado_estado = $conexion->query($query_estado);
        // fetch_assoc() convierte el resultado de MySQL en un arreglo que PHP puede leer
        $estado = $resultado_estado->fetch_assoc();


            // 1. Tomamos el video original del estado
            $video_original = $estado['ruta_video'] ?? '';

            // 2. Buscamos si los usuarios han subido más videos para este estado
            $videos_extra = [];

            // Asegúrate de usar la variable que tenga el ID actual del estado (puede ser $id_estado, $_GET['id_estados'] o $estado['id_estados'])
            $id_estado_actual = $estado['id_estados'];

            $stmt_vid = $conexion->prepare("SELECT ruta_video FROM videos_usuarios WHERE id_estados = ? ORDER BY created_at ASC");
            $stmt_vid->bind_param("i", $id_estado_actual);
            $stmt_vid->execute();
            $res_vid = $stmt_vid->get_result();

            while ($fila = $res_vid->fetch_assoc()) {
                $videos_extra[] = $fila['ruta_video'];
            }
            $stmt_vid->close();

            // 3. Unimos el video original con los nuevos en un solo arreglo
            // array_filter quita elementos vacíos por si algún estado no tiene video original
            $todos_videos = array_filter(array_merge([$video_original], $videos_extra));


        // el estado existe
        if($estado) {
            echo "<div class='detalle-content' style='background-color: {$color_dinamico};'>";
            echo "<div class='estado-header'>";
            echo "<img src='img/1mapa/PunteroMapa.png' class='pin-icon' alt='pin'>";
            echo "<h2>" . $estado['nombre'] . "</h2>";
            echo "</div>";
            echo "<div class='descripcion-header'>";
            echo "<p>" . $estado['descripcion'] . "</p>";
            echo "</div>";
           
            $query_platillos = "SELECT * FROM comidas WHERE id_estados = $id_estado_actual";
            $resultado_platillos = $conexion->query($query_platillos);

            echo '<div class="carrusel-cliente-wrap">';
            echo '<button class="carrusel-btn-cliente prev" onclick="moverPlatillos(-1)">&#8592;</button>';
            echo '<div class="card-grid" id="platillosGrid">';

            while($platillo = $resultado_platillos->fetch_assoc()) {
                $ruta = str_replace('\\', '/', $platillo['ruta_imagen']);
               
                echo "<div class='tarjeta-platillo'>";
                echo "<img src='" . $ruta . "' alt='" . $platillo['nombre'] . "'>";
                echo "<h4>" . $platillo['nombre'] . "</h4>";
                echo "<p>" . $platillo['descripcion'] . "</p>";
                echo "</div>";
            }

            echo "</div>";
            echo '<button class="carrusel-btn-cliente next" onclick="moverPlatillos(1)">&#8594;</button>';
            echo '</div>';



            // SECCIÓN DE VIDEOS

                echo '<div class="carrusel-cliente-wrap">';
                    // Botón para retroceder video
                    echo '<button class="carrusel-btn-cliente prev" onclick="moverVideos(-1)">&#8592;</button>';
                   
                    echo '<div id="videosGrid">';
                        // Iteramos sobre el array que contiene el video original y los de usuarios
                        foreach ($todos_videos as $video_path) {
                            $ruta_v = str_replace('\\', '/', $video_path);
                           
                            // Importante: No usamos ID fijo para no repetir IDs en el DOM
                            echo "<video style='width:100%; border-radius:16px;'>";
                                echo "<source src='" . $ruta_v . "' type='video/mp4' preload='auto'>";
                                echo "Tu navegador no soporta videos.";
                            echo "</video>";
                        }
                    echo '</div>';
                   
                    // Botón para avanzar video
                    echo '<button class="carrusel-btn-cliente next" onclick="moverVideos(1)">&#8594;</button>';
                echo '</div>';
           
                // CONTROLES DE VIDEO
                echo '<div class="video-controls">';
                    echo '<button class="video-btn" onclick="startFromBeginning()">⏮ Inicio</button>';
                    echo '<button class="video-btn" onclick="playVideo()">▶ Reproducir</button>';
                    echo '<button class="video-btn" onclick="pauseVideo()">⏸ Pausa</button>';
                    echo '<button class="video-btn" onclick="stopVideo()">⏹ Detener</button>';
                    echo '<button class="video-btn" onclick="skipToEnd()">⏭ Final</button>';
                echo '</div>';


                echo '<div class="seccion-titulo">Ambiente Sonoro</div>';
                    echo '<div id="audiosGrid" class="audio-container">';
                echo '</div>';

                if (!empty($estado['ruta_audio'])) {
                // Primero normalizamos la ruta para evitar problemas de escape en el navegador casi uero gracias a esto
                $ruta_limpia = str_replace('\\', '/', $estado['ruta_audio']);
                echo '<div class="seccion-audio" style="margin-top: 20px; padding: 15px; background: #f5ede6; border-radius: 12px;">';
                echo '<p style="font-family: \'Playpen Sans\', sans-serif; font-size: 0.9rem; color: #5a3d3d; margin-bottom: 8px;">';
                echo '<strong>Audio regional:</strong>';
                echo '</p>';
                //Controles de audio
                echo '<div class="audio-controls">';
                echo '<button class="audio-btn" onclick="startAudioFromBeginning()">⏮ Inicio</button>';
                echo '<button class="audio-btn" onclick="playAudio()">▶ Reproducir</button>';
                echo '<button class="audio-btn" onclick="pauseAudio()">⏸ Pausa</button>';
                echo '<button class="audio-btn" onclick="stopAudio()">⏹ Detener</button>';
                echo '<button class="audio-btn" onclick="skipAudioToEnd()">⏭ Final</button>';
                echo '</div>';
               
                echo '<audio style="width: 100%; outline: none;">';
                echo '<source src="' . $ruta_limpia . '" type="audio/mp4">';
                echo 'Tu navegador no soporta la reproducción de audio.';
                echo '</audio>';
                echo '</div>';
            } else {
                echo '<p style="font-size: 0.8rem; color: #b8a0a0; font-style: italic; margin-top: 15px;">';
                echo 'Contenido sonoro próximamente disponible para este estado.';
                echo '</p>';
            }

            $query_estados = "SELECT * FROM estados WHERE id_estados = $id_estado_actual";
            $resultado_leyendas = $conexion->query($query_estados);

            while($leyenda = $resultado_leyendas->fetch_assoc()) {
                //LEYENDAS DEL TEXTO
                echo '<div class="leyendaEstado">';
                    echo "<p>" . $leyenda['leyenda'] . "</p>";
                echo '</div>';
            }

        echo '<div class="leyendaFinal">';
            echo '<span> "HECHO CON PASIÓN POR MÉXICO" <br> "© 2026 RUTA DEL SABOR • COLECCIÓN DE HERENCIA" </spam>';
        echo '</div>';
       
        } else {
            echo "<p>Error: No se encontró el estado en la base de datos.</p>";
        }
        ?>
        <?php include 'includes/footer.php' ; ?>