<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — 404.php (Página no encontrada)
 * ═══════════════════════════════════════════════════════════════════
 *
 * WordPress muestra esta página cuando la URL solicitada no
 * corresponde a ningún contenido del sitio.
 *
 * Diseño amigable con:
 *   - Ilustración/número 404 grande
 *   - Mensaje empático (no técnico)
 *   - Buscador
 *   - Links rápidos a las páginas principales
 *   - Botón para volver al inicio
 *
 * @package HumanPeru
 */

get_header();
?>

<div class="hero-bar" aria-hidden="true"></div>

<section class="error-404">
    <div class="container">
        <div class="error-404__content">

            <!-- Número 404 decorativo -->
            <div class="error-404__number" aria-hidden="true">
                <span class="error-404__4">4</span>
                <span class="error-404__0">
                    <!-- Círculo con ícono de corazón (identidad de marca) -->
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                    </svg>
                </span>
                <span class="error-404__4">4</span>
            </div>

            <!-- Mensaje principal -->
            <h1 class="error-404__title">
                Página no encontrada
            </h1>

            <p class="error-404__text">
                Lo sentimos, la página que buscas no existe o fue movida.
                Pero no te preocupes, puedes buscar lo que necesitas o
                volver al inicio.
            </p>

            <!-- Buscador -->
            <form role="search"
                  method="get"
                  action="<?php echo esc_url( home_url( '/' ) ); ?>"
                  class="error-404__search">
                <input type="search"
                       name="s"
                       class="error-404__search-input"
                       placeholder="Buscar en Human Perú..."
                       aria-label="Buscar en el sitio"
                       required>
                <button type="submit" class="error-404__search-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>

            <!-- Links rápidos -->
            <div class="error-404__links">
                <p class="error-404__links-label">O visita directamente:</p>
                <div class="error-404__links-grid">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-404__link">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        Inicio
                    </a>
                    <a href="<?php echo esc_url( home_url( '/servicios/' ) ); ?>" class="error-404__link">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                        </svg>
                        Servicios
                    </a>
                    <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="error-404__link">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
                        </svg>
                        Blog
                    </a>
                    <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>" class="error-404__link">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/>
                        </svg>
                        Contacto
                    </a>
                </div>
            </div>

            <!-- Botón de volver al inicio -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary btn-large">
                Volver al inicio
            </a>

        </div>
    </div>
</section>

<?php get_footer(); ?>
