<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — footer.php
 * ═══════════════════════════════════════════════════════════════════
 *
 * Cierra la estructura HTML abierta en header.php.
 *
 * Estructura:
 *   1. </main> — Cierra el contenido principal abierto en header.php
 *   2. CTA Banner — Template part con llamada a la acción
 *   3. <footer> — 3 columnas + barra inferior
 *      Col 1: Logo + descripción (Acerca de nosotros)
 *      Col 2: Links de acceso rápido
 *      Col 3: Redes sociales con íconos SVG
 *      Barra: Teléfono + email + copyright
 *   4. wp_footer() + </body> + </html>
 *
 * Colores:
 *   - Footer principal: var(--hp-dark-blue) → #1B3A5C
 *   - Barra inferior:   var(--hp-navy)      → #0F2A44
 *   - Texto:            #CCDDEE (gris claro sobre fondo oscuro)
 *
 * @package HumanPeru
 */

// SEGURIDAD: bloquear acceso directo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

</main><!-- Cierre de #main-content abierto en header.php -->


<!-- ════════════════════════════════════════════════════════════════
     CTA BANNER — Se muestra antes del footer en casi todas las páginas.
     Para ocultarlo en una página específica, usar condicional:
     <?php if ( ! is_page('contacto') ) get_template_part(...); ?>
     ════════════════════════════════════════════════════════════════ -->
<?php
// No mostrar el CTA en la página de contacto (ya estás ahí)
if ( ! is_page( 'contacto' ) ) {
    get_template_part( 'template-parts/cta-banner' );
}
?>


<!-- ════════════════════════════════════════════════════════════════
     FOOTER PRINCIPAL
     ════════════════════════════════════════════════════════════════ -->
<footer class="site-footer" role="contentinfo">

    <!-- ── Área de columnas ───────────────────────────────────── -->
    <div class="footer__columns">

        <!-- ════════════════════════════════════════════════════════
             COLUMNA 1 — Acerca de nosotros
             ════════════════════════════════════════════════════════ -->
        <div class="footer__col footer__col--about">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo2026-light.svg' ); ?>"
                 alt="Human Perú"
                 class="footer__logo"
                 width="170"
                 height="50"
                 loading="lazy">

            <p class="footer__description">
                Promovemos la salud mental mediante la educación, la
                sensibilización y el apoyo comunitario.
            </p>

            <!-- Dirección física -->
            <address class="footer__address">
                <!-- SVG: ícono de ubicación -->
                <svg class="footer__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Av. Universitaria 2017 Oficina 705,<br>
                San Miguel, Lima — Perú
            </address>
        </div>


        <!-- ════════════════════════════════════════════════════════
             COLUMNA 2 — Accesos directos
             ════════════════════════════════════════════════════════ -->
        <div class="footer__col footer__col--links">
            <h4 class="footer__heading">Accesos directos</h4>

            <ul class="footer__nav">
                <li>
                    <a href="<?php echo esc_url( home_url( '/nosotros/' ) ); ?>">Nosotros</a>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/servicios/' ) ); ?>">Servicios</a>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/cooperacion/' ) ); ?>">Cooperación</a>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Contacto</a>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/verificar-diploma/' ) ); ?>">Verificar diploma</a>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/asistencia/' ) ); ?>">Asistencia del personal</a>
                </li>
            </ul>
        </div>


        <!-- ════════════════════════════════════════════════════════
             COLUMNA 3 — Redes sociales
             ════════════════════════════════════════════════════════ -->
        <div class="footer__col footer__col--social">
            <h4 class="footer__heading">Sigue nuestras redes</h4>

            <div class="footer__social">

                <!-- Facebook -->
                <a href="https://www.facebook.com/humanperu"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="footer__social-link footer__social-link--facebook"
                   aria-label="Síguenos en Facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>

                <!-- Instagram -->
                <a href="https://www.instagram.com/humanperuorg"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="footer__social-link footer__social-link--instagram"
                   aria-label="Síguenos en Instagram">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                </a>

                <!-- LinkedIn -->
                <a href="https://www.linkedin.com/company/human-peru"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="footer__social-link footer__social-link--linkedin"
                   aria-label="Síguenos en LinkedIn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </a>

                <!-- X (Twitter) -->
                <a href="https://x.com/humanperu"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="footer__social-link footer__social-link--x"
                   aria-label="Síguenos en X">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>

            </div>

            <!-- WhatsApp directo -->
            <a href="https://wa.me/51923322521"
               target="_blank"
               rel="noopener noreferrer"
               class="footer__whatsapp">
                <!-- SVG: ícono de WhatsApp -->
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Escríbenos por WhatsApp
            </a>
        </div>

    </div><!-- /.footer__columns -->


    <!-- ════════════════════════════════════════════════════════════
         BARRA INFERIOR — Contacto + Copyright
         ════════════════════════════════════════════════════════════ -->
    <div class="footer__bottom">
        <div class="footer__bottom-inner">

            <!-- Datos de contacto -->
            <div class="footer__contact">
                <!-- Teléfono -->
                <a href="tel:+51923322521" class="footer__contact-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"></path>
                    </svg>
                    +51 923 322 521
                </a>

                <!-- Email -->
                <a href="mailto:mesadepartes@humanperu.org.pe" class="footer__contact-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <path d="M22 7l-10 7L2 7"></path>
                    </svg>
                    mesadepartes@humanperu.org.pe
                </a>
            </div>

            <!-- Copyright -->
            <p class="footer__copyright">
                &copy; <?php echo date( 'Y' ); ?> Human Perú. Todos los derechos reservados.
            </p>

        </div>
    </div><!-- /.footer__bottom -->

</footer>


<?php
// wp_footer() — Hook obligatorio de WordPress.
// Inyecta scripts encolados, tracking codes, y plugins que
// necesitan ejecutarse antes de cerrar </body>.
wp_footer();
?>
</body>
</html>
