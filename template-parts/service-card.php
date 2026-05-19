<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — Template Part: Service Card (Tarjeta de servicio)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Archivo: template-parts/service-card.php
 *
 * Tarjeta reutilizable para mostrar un servicio de Human Perú.
 * Recibe datos a través del tercer parámetro de get_template_part().
 *
 * Uso:
 *   get_template_part( 'template-parts/service-card', null, [
 *       'icon'        => 'clipboard',   // Clave del ícono SVG
 *       'title'       => 'Evaluación y diagnóstico',
 *       'description' => 'Texto descriptivo del servicio...',
 *       'delay'       => 1,             // Delay de animación (0-4)
 *       'link'        => '/servicios/#evaluacion', // Opcional
 *   ] );
 *
 * Diseño:
 *   - Fondo blanco, border-radius var(--card-radius), sombra sutil
 *   - Hover: borde izquierdo naranja (4px) + sombra más pronunciada
 *   - Ícono SVG inline dentro de círculo con fondo semitransparente
 *
 * @package HumanPeru
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ── Recibir variables desde get_template_part() ──────────────
// WordPress 5.5+ pasa el tercer argumento como $args
$icon        = $args['icon'] ?? 'heart';
$title       = $args['title'] ?? '';
$description = $args['description'] ?? '';
$delay       = $args['delay'] ?? 0;
$link        = $args['link'] ?? '';

// Clase de delay para animación escalonada
$delay_class = $delay > 0 ? ' hp-animate--delay-' . (int) $delay : '';

// ── Mapa de íconos SVG inline ────────────────────────────────
// Cada ícono es un SVG de 24x24 (estilo Lucide/Feather).
// Usamos un array asociativo para no depender de Font Awesome
// ni cargar un sprite SVG externo.
$icons = [
    'clipboard' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="M9 14l2 2 4-4"/></svg>',

    'users' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',

    'shield' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>',

    'heart' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>',

    'book' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>',

    'link' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 7h3a5 5 0 015 5 5 5 0 01-5 5h-3m-6 0H6a5 5 0 01-5-5 5 5 0 015-5h3"/><line x1="8" y1="12" x2="16" y2="12"/></svg>',

    'alert' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',

    'sun' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>',

    'brain' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0112 4.5v15a2.5 2.5 0 01-4.96.44A2.5 2.5 0 015 17.5a2.5 2.5 0 01-.64-4.9A2.5 2.5 0 016 9.5 2.5 2.5 0 019.5 2z"/><path d="M14.5 2A2.5 2.5 0 0012 4.5v15a2.5 2.5 0 004.96.44A2.5 2.5 0 0019 17.5a2.5 2.5 0 00.64-4.9A2.5 2.5 0 0018 9.5 2.5 2.5 0 0014.5 2z"/></svg>',
];

// Obtener el SVG correspondiente o usar fallback
$svg = $icons[ $icon ] ?? $icons['heart'];

// Determinar si la tarjeta es un link
$es_link = ! empty( $link );
$tag     = $es_link ? 'a' : 'article';
$href    = $es_link ? ' href="' . esc_url( home_url( $link ) ) . '"' : '';
?>

<!-- Tarjeta de servicio individual -->
<<?php echo $tag . $href; ?>
    class="svc-card hp-animate<?php echo esc_attr( $delay_class ); ?>"
    <?php if ( $es_link ) : ?>aria-label="<?php echo esc_attr( $title ); ?>"<?php endif; ?>>

    <!-- Ícono del servicio dentro de círculo de color -->
    <div class="svc-card__icon" aria-hidden="true">
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — SVG controlado
        echo $svg;
        ?>
    </div>

    <!-- Texto del servicio -->
    <div class="svc-card__content">

        <h3 class="svc-card__title">
            <?php echo esc_html( $title ); ?>
        </h3>

        <p class="svc-card__text">
            <?php echo esc_html( $description ); ?>
        </p>

    </div>

    <!-- Indicador visual de hover (flecha, solo si es link) -->
    <?php if ( $es_link ) : ?>
        <span class="svc-card__arrow" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </span>
    <?php endif; ?>

</<?php echo $tag; ?>>
