<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — Template Part: CTA Banner
 * ═══════════════════════════════════════════════════════════════════
 *
 * Archivo: template-parts/cta-banner.php
 *
 * Banner de llamada a la acción que aparece al final de casi todas
 * las páginas, justo antes del footer.
 *
 * Uso en cualquier template:
 *   <?php get_template_part( 'template-parts/cta-banner' ); ?>
 *
 * Diseño:
 *   - Fondo claro (hp-cream) con formas circulares decorativas
 *     generadas con ::before y ::after en CSS (no divs extra)
 *   - Texto de invitación centrado
 *   - Botón "CONTÁCTANOS" → /contacto/
 *   - Debajo: teléfono + email con íconos SVG inline
 *
 * @package HumanPeru
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!-- ════════════════════════════════════════════════════════════════
     CTA BANNER — Llamada a la acción
     ════════════════════════════════════════════════════════════════
     Las formas circulares decorativas se generan con CSS
     ::before y ::after en .cta-banner (ver style.css).
     No se necesitan divs adicionales en el HTML.
     ════════════════════════════════════════════════════════════════ -->
<section class="cta-banner hp-animate" aria-label="Llamada a la acción">
    <div class="cta-banner__inner">

        <!-- Contenido principal centrado -->
        <div class="cta-banner__content">

            <!-- Ícono decorativo (sobre de carta) -->
            <div class="cta-banner__icon" aria-hidden="true">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                     stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                    <path d="M22 7l-10 7L2 7"></path>
                </svg>
            </div>

            <h2 class="cta-banner__title">
                ¿Deseas una propuesta o mayor información?
            </h2>

            <p class="cta-banner__subtitle">
                Estaremos encantados de atenderte
            </p>

            <!-- Botón principal -->
            <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"
               class="cta-banner__btn">
                Contáctanos
            </a>

        </div>

        <!-- Datos de contacto debajo del botón -->
        <div class="cta-banner__contact">

            <!-- Teléfono / WhatsApp -->
            <a href="https://wa.me/51923322521"
               target="_blank"
               rel="noopener noreferrer"
               class="cta-banner__contact-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"></path>
                </svg>
                +51 923 322 521
            </a>

            <!-- Separador -->
            <span class="cta-banner__contact-sep" aria-hidden="true">|</span>

            <!-- Email -->
            <a href="mailto:servicios@humanperu.org.pe"
               class="cta-banner__contact-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                    <path d="M22 7l-10 7L2 7"></path>
                </svg>
                servicios@humanperu.org.pe
            </a>

        </div>

    </div>
</section>
