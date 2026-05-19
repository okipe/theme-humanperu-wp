<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — Template Part: Press Badge
 * ═══════════════════════════════════════════════════════════════════
 *
 * Archivo: template-parts/press-badge.php
 *
 * Mención de Human Perú en El Comercio (marzo 2026).
 * Muestra la credibilidad de la organización con una cita real.
 *
 * Uso:
 *   <?php get_template_part( 'template-parts/press-badge' ); ?>
 *
 * Diseño:
 *   - Fondo #FAFAF7 (off-white cálido)
 *   - Borde izquierdo grueso var(--hp-yellow) (5px)
 *   - "COMO SE VIO EN" en mayúsculas con letter-spacing
 *   - "El Comercio" en Georgia, itálica, tamaño grande
 *   - "Marzo 2026" en gris claro
 *   - Línea divisoria horizontal
 *   - Cita con comillas « »
 *   - Atribución: — Rolando Salazar, CEO de Human Perú
 *
 * @package HumanPeru
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!-- ════════════════════════════════════════════════════════════════
     PRESS BADGE — Mención en El Comercio
     ════════════════════════════════════════════════════════════════ -->
<div class="press-badge hp-animate">

    <!-- Cabecera: etiqueta + nombre del medio + fecha -->
    <div class="press-badge__header">

        <!-- "COMO SE VIO EN" — Etiqueta en mayúsculas, gris, espaciada -->
        <p class="press-badge__label">Como se vio en</p>

        <!-- Nombre del medio — Georgia itálica para estilo editorial -->
        <p class="press-badge__source">El Comercio</p>

        <!-- Fecha de la publicación -->
        <p class="press-badge__date">Marzo 2026</p>

    </div>

    <!-- Línea divisoria entre el medio y la cita -->
    <hr class="press-badge__divider">

    <!-- Cita del artículo -->
    <blockquote class="press-badge__quote">
        <p>Cambiar de carrera universitaria no es fracasar</p>
    </blockquote>

    <!-- Atribución -->
    <p class="press-badge__attribution">
        — <strong>Rolando Salazar</strong>, CEO de Human Perú
    </p>

</div>
