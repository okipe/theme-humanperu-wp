<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — page-contacto.php (Página de Contacto)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Template Name: Contacto
 *
 * Secciones:
 *   1. Hero compacto — Título + subtexto
 *   2. Formulario (izq) + Datos de contacto (der)
 *   3. CTA Banner — Desactivado en esta página (ya estás aquí)
 *
 * El formulario envía vía AJAX a un handler en functions.php
 * que usa wp_mail() para enviar el email. NO necesita Contact Form 7.
 *
 * Seguridad:
 *   - Nonce (wp_nonce_field / wp_verify_nonce)
 *   - Honeypot (campo oculto que los bots llenan)
 *   - Sanitización de todos los campos
 *   - Rate limiting con transients
 *
 * @package HumanPeru
 */

get_header();
?>


<!-- ════════════════════════════════════════════════════════════════
     1. HERO COMPACTO
     ════════════════════════════════════════════════════════════════ -->

<div class="hero-bar" aria-hidden="true"></div>

<section class="contacto-hero">
    <div class="container">
        <div class="contacto-hero__content hp-animate">
            <span class="tag tag--orange">Hablemos</span>
            <h1 class="contacto-hero__title">
                ¿En qué podemos <span class="highlight">ayudarte</span>?
            </h1>
            <p class="contacto-hero__description">
                Completa el formulario y nuestro equipo te responderá en un plazo
                máximo de 24 horas. También puedes contactarnos directamente por
                WhatsApp o correo electrónico.
            </p>
        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     2. FORMULARIO + DATOS DE CONTACTO
     ════════════════════════════════════════════════════════════════ -->

<section class="section" id="contacto-form">
    <div class="container">
        <div class="contacto-grid">

            <!-- ══════════════════════════════════════════════════
                 COLUMNA IZQUIERDA — Formulario de contacto
                 ══════════════════════════════════════════════════ -->
            <div class="contacto-form-wrapper hp-animate">

                <form id="hp-contacto-form" class="contacto-form" novalidate>

                    <!-- Nonce de seguridad (WordPress CSRF protection).
                         Este campo oculto contiene un token que el servidor
                         valida para confirmar que el form viene de nuestro sitio. -->
                    <?php wp_nonce_field( 'hp_contacto_nonce', 'hp_contacto_security' ); ?>

                    <!-- ── Nombre ──────────────────────────────── -->
                    <div class="contacto-form__field">
                        <label for="hp-nombre" class="contacto-form__label">
                            Nombre completo <span class="contacto-form__required">*</span>
                        </label>
                        <input type="text"
                               id="hp-nombre"
                               name="nombre"
                               class="contacto-form__input"
                               placeholder="Tu nombre completo"
                               required
                               autocomplete="name"
                               maxlength="100">
                    </div>

                    <!-- ── Email ───────────────────────────────── -->
                    <div class="contacto-form__field">
                        <label for="hp-email" class="contacto-form__label">
                            Correo electrónico <span class="contacto-form__required">*</span>
                        </label>
                        <input type="email"
                               id="hp-email"
                               name="email"
                               class="contacto-form__input"
                               placeholder="tucorreo@ejemplo.com"
                               required
                               autocomplete="email"
                               maxlength="100">
                    </div>

                    <!-- ── Teléfono ────────────────────────────── -->
                    <div class="contacto-form__field">
                        <label for="hp-telefono" class="contacto-form__label">
                            Teléfono / WhatsApp
                        </label>
                        <input type="tel"
                               id="hp-telefono"
                               name="telefono"
                               class="contacto-form__input"
                               placeholder="+51 9XX XXX XXX"
                               autocomplete="tel"
                               maxlength="20">
                    </div>

                    <!-- ── Asunto (select) ─────────────────────── -->
                    <div class="contacto-form__field">
                        <label for="hp-asunto" class="contacto-form__label">
                            Asunto <span class="contacto-form__required">*</span>
                        </label>
                        <select id="hp-asunto"
                                name="asunto"
                                class="contacto-form__select"
                                required>
                            <option value="" disabled selected>Selecciona un asunto</option>
                            <option value="Consulta sobre servicios">Consulta sobre servicios</option>
                            <option value="Capacitación empresarial">Capacitación empresarial</option>
                            <option value="Escuela de padres">Escuela de padres</option>
                            <option value="Cooperación institucional">Cooperación institucional</option>
                            <option value="Verificación de diploma">Verificación de diploma</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <!-- ── Mensaje ─────────────────────────────── -->
                    <div class="contacto-form__field">
                        <label for="hp-mensaje" class="contacto-form__label">
                            Mensaje <span class="contacto-form__required">*</span>
                        </label>
                        <textarea id="hp-mensaje"
                                  name="mensaje"
                                  class="contacto-form__textarea"
                                  placeholder="Cuéntanos en qué podemos ayudarte..."
                                  required
                                  rows="5"
                                  maxlength="2000"></textarea>
                    </div>

                    <!-- ── Honeypot (anti-spam) ────────────────── -->
                    <!-- Campo invisible. Los bots lo llenan automáticamente.
                         Si el servidor detecta contenido aquí, rechaza el envío.
                         aria-hidden + tabindex=-1 para que sea invisible
                         también para lectores de pantalla y teclado. -->
                    <div class="contacto-form__hp" aria-hidden="true">
                        <label for="hp-website">No completar este campo</label>
                        <input type="text"
                               id="hp-website"
                               name="website"
                               tabindex="-1"
                               autocomplete="off">
                    </div>

                    <!-- ── Botón de envío ──────────────────────── -->
                    <button type="submit"
                            id="hp-contacto-btn"
                            class="btn-primary contacto-form__submit">
                        <!-- SVG: ícono de enviar -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" aria-hidden="true">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        Enviar mensaje
                    </button>

                    <!-- Texto de tiempo de respuesta -->
                    <p class="contacto-form__note">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Responderemos en un plazo máximo de 24 horas.
                    </p>

                </form>

                <!-- Área de resultado (el JS escribe aquí el mensaje de éxito/error) -->
                <div id="hp-contacto-resultado" class="contacto-form__resultado"></div>

            </div>


            <!-- ══════════════════════════════════════════════════
                 COLUMNA DERECHA — Datos de contacto
                 ══════════════════════════════════════════════════ -->
            <aside class="contacto-sidebar hp-animate hp-animate--right">

                <!-- Tarjeta: WhatsApp -->
                <div class="contacto-dato">
                    <div class="contacto-dato__icon contacto-dato__icon--green">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <div class="contacto-dato__text">
                        <h4>WhatsApp</h4>
                        <a href="https://wa.me/51923322521" target="_blank" rel="noopener noreferrer">
                            +51 923 322 521
                        </a>
                        <p>Lunes a viernes, 9:00 a.m. — 6:00 p.m.</p>
                    </div>
                </div>

                <!-- Tarjeta: Email general -->
                <div class="contacto-dato">
                    <div class="contacto-dato__icon contacto-dato__icon--blue">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="M22 7l-10 7L2 7"></path>
                        </svg>
                    </div>
                    <div class="contacto-dato__text">
                        <h4>Mesa de partes</h4>
                        <a href="mailto:mesadepartes@humanperu.org.pe">
                            mesadepartes@humanperu.org.pe
                        </a>
                        <p>Para consultas generales y mesa de partes.</p>
                    </div>
                </div>

                <!-- Tarjeta: Email de servicios -->
                <div class="contacto-dato">
                    <div class="contacto-dato__icon contacto-dato__icon--orange">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="M22 7l-10 7L2 7"></path>
                        </svg>
                    </div>
                    <div class="contacto-dato__text">
                        <h4>Servicios</h4>
                        <a href="mailto:servicios@humanperu.org.pe">
                            servicios@humanperu.org.pe
                        </a>
                        <p>Para cotizaciones y propuestas de servicios.</p>
                    </div>
                </div>

                <!-- Tarjeta: Dirección -->
                <div class="contacto-dato">
                    <div class="contacto-dato__icon contacto-dato__icon--navy">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <div class="contacto-dato__text">
                        <h4>Oficina</h4>
                        <p>Av. Universitaria 2017 Oficina 705,<br>San Miguel, Lima — Perú</p>
                    </div>
                </div>

                <!-- Google Maps -->
                <div class="contacto-mapa">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3901.8!2d-77.085!3d-12.075!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sAv.+Universitaria+2017%2C+San+Miguel%2C+Lima!5e0!3m2!1ses!2spe!4v1700000000000"
                            width="100%"
                            height="220"
                            style="border:0; border-radius: var(--card-radius);"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Ubicación de Human Perú en Google Maps">
                    </iframe>
                </div>

            </aside>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     El CTA Banner no se muestra en /contacto/ porque ya estás aquí.
     (footer.php tiene la condición: if ( ! is_page('contacto') ))
     ════════════════════════════════════════════════════════════════ -->

<?php get_footer(); ?>
