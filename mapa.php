<?php include 'includes/header.php'; ?>
 
 
<h1>Selecciona un estado</h1>
 
<!-- BLOQUE GEOLOCALIZACIÓN -->
<div class="geo-wrap">
    <button class="geo-btn" id="btnGeo" onclick="iniciarGeo()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"/>
            <path d="M12 2v3M12 19v3M2 12h3M19 12h3"/>
            <circle cx="12" cy="12" r="8" stroke-dasharray="4 2"/>
        </svg>
        <span id="btnGeoTexto">¿Dónde estoy?</span>
        <span class="geo-spinner" id="geoSpinner"></span>
    </button>
 
    <div class="geo-resultado" id="geoResultado">
        <span class="geo-resultado-ico"></span>
        <div class="geo-resultado-texto">
            <strong id="geoNombreEstado">—</strong>
            <span id="geoCoords">—</span>
        </div>
        <button class="geo-ver-btn" id="geoVerBtn" onclick="">Ver platillos</button>
    </div>
 
    <div class="geo-error" id="geoError"></div>
 
    <!-- ESTADOS CERCANOS -->
    <div class="geo-cercanos-wrap" id="geoCercanosWrap">
        <span class="geo-cercanos-titulo"> Estados cercanos a ti</span>
        <div class="geo-cercanos-grid" id="geoCercanosGrid"></div>
    </div>
</div>
 
<div class="map-container">
    <img src="img/1mapa/Mapa.png" alt="Mapa de México" class="mapa">
 
    <!-- PIN USUARIO -->
    <div class="pin-usuario" id="pinUsuario">
        <div class="pin-usuario-pulso"></div>
    </div>
 
    <!-- NORTE -->
    <div class="pin-wrapper" style="top:12.74%; left:7.40%;" data-estado="Baja California" data-lat="30.8406" data-lng="-115.2838">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="1">
        <span class="pin-tooltip">Baja California</span>
    </div>
    <div class="pin-wrapper" style="top:40.66%; left:19.44%;" data-estado="Baja California Sur" data-lat="23.7369" data-lng="-110.0227">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="3">
        <span class="pin-tooltip">Baja California Sur</span>
    </div>
    <div class="pin-wrapper" style="top:18.81%; left:22.72%;" data-estado="Sonora" data-lat="29.2972" data-lng="-110.3309">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="26">
        <span class="pin-tooltip">Sonora</span>
    </div>
    <div class="pin-wrapper" style="top:22.25%; left:36.26%;" data-estado="Chihuahua" data-lat="28.6330" data-lng="-106.0691">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="6">
        <span class="pin-tooltip">Chihuahua</span>
    </div>
    <div class="pin-wrapper" style="top:28.93%; left:49.66%;" data-estado="Coahuila" data-lat="27.0587" data-lng="-101.7068">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="8">
        <span class="pin-tooltip">Coahuila</span>
    </div>
    <div class="pin-wrapper" style="top:40.05%; left:31.74%;" data-estado="Sinaloa" data-lat="24.8091" data-lng="-107.3940">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="25">
        <span class="pin-tooltip">Sinaloa</span>
    </div>
    <div class="pin-wrapper" style="top:42.07%; left:40.77%;" data-estado="Durango" data-lat="24.0277" data-lng="-104.6532">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="10">
        <span class="pin-tooltip">Durango</span>
    </div>
    <div class="pin-wrapper" style="top:39.04%; left:57.59%;" data-estado="Nuevo León" data-lat="25.5922" data-lng="-99.9962">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="19">
        <span class="pin-tooltip">Nuevo León</span>
    </div>
    <div class="pin-wrapper" style="top:47.94%; left:61.42%;" data-estado="Tamaulipas" data-lat="24.2669" data-lng="-98.8363">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="28">
        <span class="pin-tooltip">Tamaulipas</span>
    </div>
 
    <!-- CENTRO -->
    <div class="pin-wrapper" style="top:59.07%; left:41.18%;" data-estado="Nayarit" data-lat="21.7514" data-lng="-104.8455">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="18">
        <span class="pin-tooltip">Nayarit</span>
    </div>
    <div class="pin-wrapper" style="top:51.38%; left:47.20%;" data-estado="Zacatecas" data-lat="22.7709" data-lng="-102.5832">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="32">
        <span class="pin-tooltip">Zacatecas</span>
    </div>
    <div class="pin-wrapper" style="top:54.62%; left:55.13%;" data-estado="San Luis Potosí" data-lat="22.1565" data-lng="-100.9855">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="24">
        <span class="pin-tooltip">San Luis Potosí</span>
    </div>
    <div class="pin-wrapper" style="top:57.65%; left:49.11%;" data-estado="Aguascalientes" data-lat="21.8853" data-lng="-102.2916">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="2">
        <span class="pin-tooltip">Aguascalientes</span>
    </div>
    <div class="pin-wrapper" style="top:65.54%; left:44.60%;" data-estado="Jalisco" data-lat="20.6595" data-lng="-103.3494">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="15">
        <span class="pin-tooltip">Jalisco</span>
    </div>
    <div class="pin-wrapper" style="top:72.42%; left:44.05%;" data-estado="Colima" data-lat="19.1223" data-lng="-103.7240">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="9">
        <span class="pin-tooltip">Colima</span>
    </div>
    <div class="pin-wrapper" style="top:72.82%; left:50.62%;" data-estado="Michoacán" data-lat="19.5665" data-lng="-101.7068">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="16">
        <span class="pin-tooltip">Michoacán</span>
    </div>
    <div class="pin-wrapper" style="top:63.31%; left:53.35%;" data-estado="Guanajuato" data-lat="21.0190" data-lng="-101.2574">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="12">
        <span class="pin-tooltip">Guanajuato</span>
    </div>
    <div class="pin-wrapper" style="top:63.92%; left:57.18%;" data-estado="Querétaro" data-lat="20.5888" data-lng="-100.3899">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="22">
        <span class="pin-tooltip">Querétaro</span>
    </div>
 
    <!-- CENTRO SUR -->
    <div class="pin-wrapper" style="top:71.99%; left:59.67%;" data-estado="Ciudad de México" data-lat="19.4326" data-lng="-99.1332">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="7">
        <span class="pin-tooltip">Ciudad de México</span>
    </div>
    <div class="pin-wrapper" style="top:72.78%; left:56.73%;" data-estado="Estado de México" data-lat="19.3594" data-lng="-99.8562">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="11">
        <span class="pin-tooltip">Estado de México</span>
    </div>
    <div class="pin-wrapper" style="top:65.28%; left:60.33%;" data-estado="Hidalgo" data-lat="20.0911" data-lng="-98.7624">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="14">
        <span class="pin-tooltip">Hidalgo</span>
    </div>
    <div class="pin-wrapper" style="top:75.14%; left:60.20%;" data-estado="Morelos" data-lat="18.6813" data-lng="-99.1013">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="17">
        <span class="pin-tooltip">Morelos</span>
    </div>
    <div class="pin-wrapper" style="top:71.20%; left:62.87%;" data-estado="Tlaxcala" data-lat="19.3182" data-lng="-98.2375">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="29">
        <span class="pin-tooltip">Tlaxcala</span>
    </div>
    <div class="pin-wrapper" style="top:75.34%; left:62.87%;" data-estado="Puebla" data-lat="19.0414" data-lng="-98.2063">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="21">
        <span class="pin-tooltip">Puebla</span>
    </div>
    <div class="pin-wrapper" style="top:80.57%; left:57.00%;" data-estado="Guerrero" data-lat="17.4392" data-lng="-99.5451">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="13">
        <span class="pin-tooltip">Guerrero</span>
    </div>
 
    <!-- SUR -->
    <div class="pin-wrapper" style="top:72.58%; left:68.07%;" data-estado="Veracruz" data-lat="19.1738" data-lng="-96.1342">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="30">
        <span class="pin-tooltip">Veracruz</span>
    </div>
    <div class="pin-wrapper" style="top:84.81%; left:68.74%;" data-estado="Oaxaca" data-lat="17.0732" data-lng="-96.7266">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="20">
        <span class="pin-tooltip">Oaxaca</span>
    </div>
    <div class="pin-wrapper" style="top:87.17%; left:81.27%;" data-estado="Chiapas" data-lat="16.7569" data-lng="-93.1292">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="5">
        <span class="pin-tooltip">Chiapas</span>
    </div>
    <div class="pin-wrapper" style="top:77.90%; left:80.87%;" data-estado="Tabasco" data-lat="17.8409" data-lng="-92.6189">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="27">
        <span class="pin-tooltip">Tabasco</span>
    </div>
    <div class="pin-wrapper" style="top:74.35%; left:88.07%;" data-estado="Campeche" data-lat="19.8301" data-lng="-90.5349">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="4">
        <span class="pin-tooltip">Campeche</span>
    </div>
    <div class="pin-wrapper" style="top:64.30%; left:91.93%;" data-estado="Yucatán" data-lat="20.7099" data-lng="-89.0943">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="31">
        <span class="pin-tooltip">Yucatán</span>
    </div>
    <div class="pin-wrapper" style="top:71.99%; left:94.73%;" data-estado="Quintana Roo" data-lat="19.1817" data-lng="-88.4791">
        <img src="img/1mapa/PunteroMapa.png" class="pin" data-id="23">
        <span class="pin-tooltip">Quintana Roo</span>
    </div>
</div>
 
<div>
    <div class="botones">
        <button data-page="antojitos.php">Antojitos</button>
        <button data-page="fuerte.php">Plato Fuerte</button>
        <button data-page="postre.php">Postres</button>
    </div>
</div>
 
<div id="contenidoExtra"></div>
 
<!-- MODAL -->
<div class="modal" id="modal">
    <div class="modal-content">
        <button class="close-btn" id="closeBtn">✕</button>
        <iframe id="modalFrame"></iframe>
    </div>
</div>
 
<?php include 'includes/footer.php'; ?>