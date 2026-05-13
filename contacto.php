<?php include 'includes/header.php'; ?>
 
<div class="contacto-wrapper">
    <h1 class="contacto-titulo">Contáctanos</h1>
    <p class="contacto-subtitulo">¿Tienes dudas, sugerencias o quieres compartir una receta? Escríbenos.</p>
 
    <div class="form-card">
        <form id="formContacto" novalidate autocomplete="off">
            <div class="form-grid">
 
                <!-- Nombre -->
                <div class="form-group">
                    <label for="nombre">Nombre <span class="req">*</span></label>
                    <input type="text" id="nombre" name="nombre"
                           placeholder="Ej: María López" maxlength="60">
                    <span class="msg-error" id="err-nombre"></span>
                </div>
 
                <!-- Email -->
                <div class="form-group">
                    <label for="email">Correo electrónico <span class="req">*</span></label>
                    <input type="email" id="email" name="email"
                           placeholder="correo@ejemplo.com" maxlength="80">
                    <span class="msg-error" id="err-email"></span>
                </div>
 
                <!-- Teléfono -->
                <div class="form-group">
                    <label for="telefono">Teléfono <span style="color:#b8a0a0;font-size:0.75rem;text-transform:none">(opcional)</span></label>
                    <input type="tel" id="telefono" name="telefono"
                           placeholder="10 dígitos" maxlength="10"
                           pattern="[0-9]{10}">
                    <span class="msg-error" id="err-telefono"></span>
                </div>
 
                <!-- Estado favorito -->
                <div class="form-group">
                    <label for="estado">Estado favorito <span class="req">*</span></label>
                    <select id="estado" name="estado">
                        <option value="">— Elige un estado —</option>
                        <option>Baja California</option>
                        <option>Baja California Sur</option>
                        <option>Chiapas</option>
                        <option>Ciudad de México</option>
                        <option>Guanajuato</option>
                        <option>Jalisco</option>
                        <option>Oaxaca</option>
                        <option>Puebla</option>
                        <option>Quintana Roo</option>
                        <option>Veracruz</option>
                        <option>Yucatán</option>
                        <option>Otro</option>
                    </select>
                    <span class="msg-error" id="err-estado"></span>
                </div>
 
                <hr class="form-divider">
 
                <!-- Asunto -->
                <div class="form-group full">
                    <label for="asunto">Asunto <span class="req">*</span></label>
                    <input type="text" id="asunto" name="asunto"
                           placeholder="¿De qué quieres hablarnos?" maxlength="100">
                    <span class="msg-error" id="err-asunto"></span>
                </div>
 
                <!-- Mensaje -->
                <div class="form-group full">
                    <label for="mensaje">Mensaje <span class="req">*</span></label>
                    <textarea id="mensaje" name="mensaje" rows="5"
                              placeholder="Escribe aquí tu mensaje…" maxlength="500"></textarea>
                    <span class="msg-error" id="err-mensaje"></span>
                </div>
 
                <!-- Contador -->
                <div class="contador-wrap full">
                    <span id="contador-chars">0 / 500</span>
                </div>
 
                <!-- Rating -->
                <div class="form-group full">
                    <label>¿Cómo calificarías el sitio? <span class="req">*</span></label>
                    <div class="stars-wrap" role="radiogroup" aria-label="Calificación">
                        <input type="radio" id="s5" name="rating" value="5"><label for="s5" title="Excelente">★</label>
                        <input type="radio" id="s4" name="rating" value="4"><label for="s4" title="Muy bueno">★</label>
                        <input type="radio" id="s3" name="rating" value="3"><label for="s3" title="Bueno">★</label>
                        <input type="radio" id="s2" name="rating" value="2"><label for="s2" title="Regular">★</label>
                        <input type="radio" id="s1" name="rating" value="1"><label for="s1" title="Malo">★</label>
                    </div>
                    <span class="msg-error" id="err-rating"></span>
                </div>
 
                <!-- Checkbox aviso -->
                <div class="check-group">
                    <input type="checkbox" id="aviso" name="aviso">
                    <label for="aviso">
                        Acepto el <a href="#">aviso de privacidad</a> y doy consentimiento
                        para que mi información sea utilizada con fines del proyecto académico.
                    </label>
                </div>
                <span class="msg-error" id="err-aviso" style="grid-column:1/-1;padding-left:28px;margin-top:-10px"></span>
 
                <!-- Botón -->
                <button type="submit" class="btn-enviar" id="btnEnviar">
                    Enviar mensaje
                </button>
 
            </div><!-- /form-grid -->
        </form>
    </div><!-- /form-card -->
</div>
 
<!-- Toast -->
<div class="toast" id="toast">✔ ¡Mensaje enviado con éxito! Gracias por escribirnos.</div>
 
<?php include 'includes/footer.php'; ?>