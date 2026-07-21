<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — header.php (con búsqueda integrada)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Estructura del header del sitio:
 *   1. <!DOCTYPE html> y <head> con meta tags + wp_head()
 *   2. <body> con body_class()
 *   3. Barra de navegación fija (navbar) con:
 *      - Logo de Human Perú
 *      - Menú de navegación principal (wp_nav_menu)
 *      - Botón de búsqueda (lupa) → abre overlay
 *      - Botón CTA "Contáctanos" (solo desktop)
 *      - Botón hamburguesa (solo móvil)
 *   4. Overlay de búsqueda a pantalla completa
 *   5. Panel lateral de menú móvil (con buscador integrado)
 *
 * Comportamiento:
 *   - Navbar: fondo blanco fijo, sombra al hacer scroll (.navbar--scrolled)
 *   - Lupa: abre overlay de búsqueda con campo grande + sugerencias
 *   - Hamburguesa: abre panel lateral con links + buscador + redes
 *   - Link activo: se resalta con var(--hp-orange) automáticamente
 *
 * El JavaScript que controla estos componentes está en:
 *   assets/js/main.js (menú móvil, scroll del navbar, search overlay)
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

    <!-- Viewport para diseño responsive. width=device-width adapta
         el ancho al dispositivo; initial-scale=1.0 evita zoom inicial. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Preconnect a Google Fonts para cargar más rápido.
         Le dice al navegador que abra la conexión TCP + TLS antes
         de necesitar el recurso, ahorrando ~100-300ms. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- wp_head() — Hook OBLIGATORIO de WordPress.
         Aquí se inyectan automáticamente:
           - Estilos encolados (Google Fonts, style.css)
           - Scripts del <head>
           - Meta tags de Yoast SEO (title, description, og:tags)
           - Favicons y manifest
           - CSS del plugin verificador de diplomas
         NUNCA eliminar esta línea. -->
    <?php wp_head(); ?>
</head>

<!-- body_class() agrega clases CSS automáticas según el contexto:
     - page, page-id-X, page-template-*  → para páginas
     - single, single-post               → para posts individuales
     - home, blog                         → para el inicio y archivo
     - logged-in                          → si el usuario tiene sesión
     - admin-bar                          → si se muestra la barra de admin
     Nuestro functions.php también agrega: page-{slug}, hp-front-page -->
<body <?php body_class(); ?>>

<?php
// wp_body_open() — Hook de WordPress 5.2+ para scripts que van
// justo después de <body> (ej: Google Tag Manager, pixel de Facebook).
// Los plugins que necesitan inyectar código aquí lo hacen automáticamente.
wp_body_open();
?>


<!-- ════════════════════════════════════════════════════════════════
     NAVBAR — Barra de navegación principal
     ════════════════════════════════════════════════════════════════

     Estructura visual en desktop:
       [Logo]  Inicio  Servicios  Cooperación  Nosotros  Contacto  Blog  [🔍] [Contáctanos]

     Estructura visual en móvil:
       [Logo]                                                       [🔍] [☰]

     El navbar es position:fixed (siempre visible arriba).
     Al hacer scroll >50px, main.js agrega la clase .navbar--scrolled
     que activa una sombra y reduce el padding.

     z-index: 1000 (debajo del overlay de búsqueda que es 2000
     y del menú móvil que es 1100-1200).
     ════════════════════════════════════════════════════════════════ -->
<header class="navbar" id="navbar" role="banner">
    <div class="navbar__container">

        <!-- ── Logo ────────────────────────────────────────────────
             Enlace al inicio del sitio. La imagen es un SVG para
             que se vea nítido en pantallas retina/HiDPI.
             width y height evitan el CLS (Cumulative Layout Shift)
             al reservar el espacio antes de que cargue la imagen.
             loading="eager" porque el logo es visible inmediatamente. -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="navbar__logo"
           aria-label="Ir al inicio de Human Perú">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo2026.svg' ); ?>"
                 alt="Human Perú — Promoción de la salud mental"
                 width="160"
                 height="48"
                 loading="eager">
        </a>


        <!-- ── Menú de navegación desktop ──────────────────────────
             Genera un <ul> con los links configurados en:
             wp-admin → Apariencia → Menús → "Menú Principal"

             Parámetros:
               theme_location → Ubicación registrada en functions.php
               container      → false = no agrega un <div> extra
               menu_class     → Clase CSS del <ul> generado
               fallback_cb    → false = no muestra nada si no hay menú
               depth          → 1 = solo un nivel (sin submenús)

             WordPress agrega automáticamente estas clases al <li> activo:
               .current-menu-item     → página actual
               .current_page_item     → página actual (alternativa)
               .current-page-ancestor → padre de la página actual

             Estas clases se usan en style.css para resaltar el link
             activo con color naranja y una línea inferior. -->
        <nav class="navbar__nav" id="navbar-nav" role="navigation" aria-label="Menú principal">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'navbar__menu',
                'menu_id'        => 'primary-menu',
                'fallback_cb'    => false,
                'depth'          => 1,
            ] );
            ?>
        </nav>


        <!-- ── Acciones del navbar ─────────────────────────────────
             Contenedor flex que agrupa los 3 botones de la derecha:
               1. Lupa de búsqueda (visible siempre)
               2. CTA "Contáctanos" (visible solo en desktop via CSS)
               3. Hamburguesa (visible solo en móvil via CSS)

             Se agrupan en un div para mantener el gap consistente
             y simplificar el responsive. -->
        <div class="navbar__actions">

            <!-- ── Botón de búsqueda (lupa) ────────────────────────
                 Visible en TODOS los tamaños de pantalla.
                 Al hacer clic, main.js abre el overlay #search-overlay.
                 aria-expanded se actualiza dinámicamente por JS.
                 aria-controls apunta al id del overlay que controla. -->
            <button class="navbar__search-btn"
                    id="navbar-search-btn"
                    type="button"
                    aria-label="Abrir búsqueda"
                    aria-expanded="false"
                    aria-controls="search-overlay">
                <!-- SVG: ícono de lupa (estilo Lucide/Feather) -->
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>

            <!-- ── Botón CTA "Contáctanos" ─────────────────────────
                 Solo visible en desktop (CSS: display:none en móvil,
                 display:inline-flex en 1024px+).
                 Lleva al formulario de contacto. -->
            <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"
               class="navbar__cta">
                Contáctanos
            </a>

            <!-- ── Botón hamburguesa ───────────────────────────────
                 Solo visible en móvil (CSS: display:flex en móvil,
                 display:none en 1024px+).
                 Las 3 barras (.navbar__hamburger-line) se animan a "X"
                 cuando JS agrega la clase .is-active.

                 Área táctil: 44×44px mínimo (recomendación WCAG para
                 accesibilidad en pantallas táctiles).

                 aria-expanded y aria-controls informan a lectores de
                 pantalla si el menú está abierto o cerrado. -->
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

    </div>
</header>


<!-- ════════════════════════════════════════════════════════════════
     OVERLAY DE BÚSQUEDA — Pantalla completa
     ════════════════════════════════════════════════════════════════

     Se abre al hacer clic en la lupa del navbar.
     Comportamiento (controlado por main.js):
       - Clic en lupa → agrega .is-open (se hace visible)
       - Clic en X → remueve .is-open
       - Tecla Escape → remueve .is-open
       - Clic en el fondo oscuro (fuera del form) → cierra

     Diseño:
       - Fondo: hp-navy al 92% con backdrop-filter blur
       - z-index: 2000 (encima de todo, incluyendo el navbar)
       - Campo de búsqueda grande centrado con lupa + input + botón
       - Sugerencias rápidas como pills clicables debajo del campo

     Accesibilidad:
       - role="dialog" para lectores de pantalla
       - aria-hidden se alterna entre "true" y "false" via JS
       - Al abrir, el foco se mueve automáticamente al input
       - body recibe clase .search-open que bloquea el scroll
     ════════════════════════════════════════════════════════════════ -->
<div class="search-overlay" id="search-overlay" role="dialog" aria-label="Buscar en el sitio" aria-hidden="true">

    <div class="search-overlay__inner">

        <!-- Botón cerrar (esquina superior derecha) -->
        <button class="search-overlay__close"
                id="search-overlay-close"
                type="button"
                aria-label="Cerrar búsqueda">
            <!-- SVG: ícono X de cerrar -->
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <!-- Formulario de búsqueda.
             method="get" + action=home_url → envía a /?s=término
             WordPress intercepta el parámetro ?s= y usa search.php
             para mostrar los resultados. -->
        <form role="search"
              method="get"
              action="<?php echo esc_url( home_url( '/' ) ); ?>"
              class="search-overlay__form">

            <label for="search-overlay-input" class="search-overlay__label">
                ¿Qué estás buscando?
            </label>

            <div class="search-overlay__field">
                <!-- Ícono de lupa decorativo dentro del campo -->
                <svg class="search-overlay__icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>

                <!-- Campo de búsqueda. name="s" es el parámetro que
                     WordPress usa para las búsquedas (?s=término). -->
                <input type="search"
                       id="search-overlay-input"
                       name="s"
                       class="search-overlay__input"
                       placeholder="Buscar servicios, artículos, diplomas..."
                       autocomplete="off"
                       required>

                <button type="submit" class="search-overlay__submit">
                    Buscar
                </button>
            </div>

            <!-- Sugerencias rápidas — Links directos a búsquedas
                 frecuentes. Ayudan al usuario a descubrir contenido
                 sin tener que pensar qué escribir. -->
            <div class="search-overlay__suggestions">
                <span>Sugerencias:</span>
                <a href="<?php echo esc_url( home_url( '/?s=salud+mental' ) ); ?>">Salud mental</a>
                <a href="<?php echo esc_url( home_url( '/?s=terapia' ) ); ?>">Terapia</a>
                <a href="<?php echo esc_url( home_url( '/?s=capacitación' ) ); ?>">Capacitación</a>
                <a href="<?php echo esc_url( home_url( '/verificar/' ) ); ?>">Verificar diploma</a>
            </div>

        </form>

    </div>

</div>


<!-- ════════════════════════════════════════════════════════════════
     MENÚ MÓVIL — Panel lateral derecho
     ════════════════════════════════════════════════════════════════

     Se desliza desde la derecha al tocar la hamburguesa.
     Comportamiento (controlado por main.js):
       - Hamburguesa clic → agrega .is-open al panel, .is-visible al overlay
       - X / overlay / Escape / clic en link → cierra todo
       - Si la ventana se agranda a 1024px+ → cierra automáticamente

     Estructura:
       1. Overlay oscuro (fondo, cierra al clic)
       2. Panel lateral con:
          - Header (logo + botón X)
          - Buscador inline
          - Links de navegación (wp_nav_menu)
          - Footer (CTA + redes sociales)

     z-index: overlay=1100, panel=1200 (encima del overlay).
     ════════════════════════════════════════════════════════════════ -->

<!-- Overlay oscuro detrás del panel (cierra el menú al hacer clic) -->
<div class="mobile-menu__overlay" id="mobile-overlay" aria-hidden="true"></div>

<!-- Panel lateral -->
<aside class="mobile-menu" id="mobile-menu" role="dialog" aria-label="Menú de navegación" aria-hidden="true">

    <!-- Cabecera del panel: logo + botón cerrar -->
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
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- ── Buscador dentro del menú móvil ──────────────────────
         Un campo de búsqueda compacto para que el usuario pueda
         buscar directamente desde el menú sin abrir el overlay.
         Fondo gris claro (hp-light) con ícono de lupa a la izquierda.
         Al enviar, WordPress procesa la búsqueda con search.php. -->
    <div class="mobile-menu__search">
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <div class="mobile-menu__search-field">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="search"
                       name="s"
                       placeholder="Buscar..."
                       aria-label="Buscar en el sitio"
                       class="mobile-menu__search-input">
            </div>
        </form>
    </div>

    <!-- Links de navegación móvil.
         Misma ubicación de menú que el desktop ('primary').
         WordPress genera las mismas clases de item activo
         (.current-menu-item, etc.) en ambas instancias. -->
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

    <!-- Footer del panel: CTA + redes sociales -->
    <div class="mobile-menu__footer">

        <!-- Botón CTA dentro del menú móvil (duplicado del navbar
             porque el CTA del navbar no es visible en móvil) -->
        <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"
           class="mobile-menu__cta">
            Contáctanos
        </a>

        <!-- Íconos de redes sociales con SVGs inline.
             Cada link tiene target="_blank" (abre en nueva pestaña)
             y rel="noopener noreferrer" (seguridad contra tab-nabbing).
             aria-label describe el destino para lectores de pantalla
             porque el contenido del link es solo un SVG (sin texto). -->
        <div class="mobile-menu__social">
            <!-- Facebook -->
            <a href="https://www.facebook.com/humanperuorg" target="_blank" rel="noopener noreferrer" aria-label="Facebook de Human Perú">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <!-- Instagram -->
            <a href="https://www.instagram.com/humanperuorg" target="_blank" rel="noopener noreferrer" aria-label="Instagram de Human Perú">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </a>
            <!-- LinkedIn -->
            <a href="https://www.linkedin.com/company/human-peru" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn de Human Perú">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
            <!-- X (Twitter) -->
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
     del documento. Este div "empuja" el contenido hacia abajo para
     que no quede tapado detrás del navbar.
     Su altura (definida en style.css) debe coincidir con la altura
     real del navbar: 66px en móvil, 74px en tablet, 80px en desktop. -->
<div class="navbar-spacer"></div>


<!-- Aquí comienza el contenido principal de cada página.
     Se abre <main> aquí y se cierra con </main> en footer.php.
     role="main" indica a los lectores de pantalla que este es
     el contenido principal del documento. -->
<main id="main-content" role="main">