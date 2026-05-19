<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — header.php
 * ═══════════════════════════════════════════════════════════════════
 *
 * Estructura del header del sitio:
 *   1. <!DOCTYPE html> y <head> con meta tags + wp_head()
 *   2. <body> con body_class()
 *   3. Barra de navegación fija (navbar) con:
 *      - Logo de Human Perú
 *      - Menú de navegación principal (wp_nav_menu)
 *      - Botón hamburguesa para móvil
 *   4. Overlay/panel lateral para menú móvil
 *
 * Comportamiento del navbar:
 *   - Por defecto: fondo blanco (sólido en todas las páginas)
 *   - Al hacer scroll: se agrega .navbar--scrolled (sombra)
 *   - En móvil: hamburguesa que abre panel lateral derecho
 *   - El link activo se resalta con var(--hp-orange)
 *
 * @package HumanPeru
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <!-- ════════════════════════════════════════════════════════════
         META TAGS BÁSICOS
         ════════════════════════════════════════════════════════════ -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Preconnect a Google Fonts para cargar más rápido.
         Le dice al navegador que abra la conexión antes de necesitar el recurso. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- wp_head() — Hook obligatorio de WordPress.
         Aquí se inyectan: estilos encolados, scripts del <head>,
         meta tags de Yoast SEO, favicons, etc. -->
    <?php wp_head(); ?>
</head>

<!-- body_class() agrega clases CSS automáticas según el contexto:
     page, page-id-X, page-template-*, logged-in, etc.
     Nuestro functions.php también agrega: page-{slug}, hp-front-page -->
<body <?php body_class(); ?>>

<?php
// wp_body_open() — Hook de WordPress 5.2+ para scripts que van
// justo después de <body> (Google Tag Manager, etc.)
wp_body_open();
?>

<!-- ════════════════════════════════════════════════════════════════
     NAVBAR — Barra de navegación principal
     ════════════════════════════════════════════════════════════════
     Estructura:
       .navbar (fixed, z-index alto)
         └─ .navbar__container (max-width centrado)
              ├─ .navbar__logo
              ├─ .navbar__menu (links, oculto en móvil)
              └─ .navbar__hamburger (visible solo en móvil)
     ════════════════════════════════════════════════════════════════ -->
<header class="navbar" id="navbar" role="banner">
    <div class="navbar__container">

        <!-- ── Logo ────────────────────────────────────────────── -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="navbar__logo"
           aria-label="Ir al inicio de Human Perú">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo2026.svg' ); ?>"
                 alt="Human Perú — Promoción de la salud mental"
                 width="160"
                 height="48"
                 loading="eager">
        </a>

        <!-- ── Menú de navegación (desktop) ────────────────────── -->
        <!-- wp_nav_menu() genera un <ul> con los links definidos
             en wp-admin → Apariencia → Menús.
             'container' => false  → No agrega un <div> contenedor extra
             'menu_class'          → Clase CSS del <ul> generado
             'fallback_cb' => false → No muestra nada si no hay menú asignado -->
        <nav class="navbar__nav" id="navbar-nav" role="navigation" aria-label="Menú principal">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'navbar__menu',
                'menu_id'        => 'primary-menu',
                'fallback_cb'    => false,
                'depth'          => 1, // Solo un nivel (sin submenús desplegables)
            ] );
            ?>
        </nav>

        <!-- ── Botón CTA del navbar (visible en desktop) ───────── -->
        <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"
           class="navbar__cta">
            Contáctanos
        </a>

        <!-- ── Botón hamburguesa (visible solo en móvil) ───────── -->
        <!-- Las 3 barras (.navbar__hamburger-line) se animan a "X"
             cuando se agrega la clase .is-active via JS.
             aria-expanded y aria-controls mejoran la accesibilidad. -->
        <button class="navbar__hamburger"
                id="navbar-hamburger"
                type="button"
                aria-label="Abrir menú de navegación"
                aria-expanded="false"
                aria-controls="mobile-menu">
            <span class="navbar__hamburger-line"></span>
            <span class="navbar__hamburger-line"></span>
            <span class="navbar__hamburger-line"></span>
        </button>

    </div>
</header>


<!-- ════════════════════════════════════════════════════════════════
     MENÚ MÓVIL — Panel lateral derecho
     ════════════════════════════════════════════════════════════════
     Se desliza desde la derecha al tocar la hamburguesa.
     El overlay oscuro (.mobile-menu__overlay) cierra el menú al tocar.
     ════════════════════════════════════════════════════════════════ -->

<!-- Overlay oscuro detrás del panel (cierra el menú al hacer clic) -->
<div class="mobile-menu__overlay" id="mobile-overlay" aria-hidden="true"></div>

<!-- Panel lateral con la navegación -->
<aside class="mobile-menu" id="mobile-menu" role="dialog" aria-label="Menú de navegación" aria-hidden="true">

    <!-- Cabecera del panel con botón de cerrar -->
    <div class="mobile-menu__header">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo2026.svg' ); ?>"
             alt="Human Perú"
             class="mobile-menu__logo"
             width="140"
             height="42">
        <button class="mobile-menu__close"
                id="mobile-close"
                type="button"
                aria-label="Cerrar menú">
            <!-- SVG inline: icono X de cerrar -->
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Links de navegación móvil -->
    <nav class="mobile-menu__nav" aria-label="Menú principal móvil">
        <?php
        wp_nav_menu( [
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'mobile-menu__list',
            'menu_id'        => 'mobile-primary-menu',
            'fallback_cb'    => false,
            'depth'          => 1,
        ] );
        ?>
    </nav>

    <!-- CTA dentro del menú móvil -->
    <div class="mobile-menu__footer">
        <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"
           class="mobile-menu__cta">
            Contáctanos
        </a>

        <!-- Redes sociales en el menú móvil -->
        <div class="mobile-menu__social">
            <a href="https://www.facebook.com/humanperu" target="_blank" rel="noopener noreferrer" aria-label="Facebook de Human Perú">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://www.instagram.com/humanperuorg" target="_blank" rel="noopener noreferrer" aria-label="Instagram de Human Perú">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </a>
            <a href="https://www.linkedin.com/company/human-peru" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn de Human Perú">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
            <a href="https://x.com/humanperu" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter) de Human Perú">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
        </div>
    </div>

</aside>

<!-- ════════════════════════════════════════════════════════════════
     SPACER del navbar
     ════════════════════════════════════════════════════════════════
     Como el navbar es position:fixed, no ocupa espacio en el flujo
     del documento. Este div empuja el contenido hacia abajo para
     que no quede tapado por el navbar. -->
<div class="navbar-spacer"></div>

<!-- Aquí comienza el contenido principal de cada página.
     Se cierra en footer.php con </main>, </body>, </html> -->
<main id="main-content" role="main">
