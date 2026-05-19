<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — sidebar.php (Sidebar del blog)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Se muestra a la derecha del listado del blog (archive.php).
 *
 * Contenido:
 *   1. Widgets de WordPress (si hay alguno configurado en wp-admin)
 *   2. Fallback manual: posts recientes + categorías + CTA
 *
 * El practicante de marketing puede agregar/quitar widgets desde:
 *   wp-admin → Apariencia → Widgets → "Sidebar del Blog"
 *
 * @package HumanPeru
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="sidebar" role="complementary" aria-label="Sidebar del blog">

    <?php if ( is_active_sidebar( 'blog-sidebar' ) ) : ?>

        <!-- ── Widgets configurados desde wp-admin ────────────── -->
        <?php dynamic_sidebar( 'blog-sidebar' ); ?>

    <?php else : ?>

        <!-- ══════════════════════════════════════════════════════
             FALLBACK — Contenido por defecto cuando no hay widgets
             ══════════════════════════════════════════════════════ -->

        <!-- ── Buscador ──────────────────────────────────────── -->
        <div class="hp-widget sidebar__search">
            <h4 class="hp-widget-title">Buscar</h4>
            <form role="search"
                  method="get"
                  action="<?php echo esc_url( home_url( '/' ) ); ?>"
                  class="sidebar__search-form">
                <input type="search"
                       name="s"
                       placeholder="Buscar artículos..."
                       value="<?php echo esc_attr( get_search_query() ); ?>"
                       class="sidebar__search-input"
                       aria-label="Buscar en el blog">
                <button type="submit"
                        class="sidebar__search-btn"
                        aria-label="Buscar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>
        </div>


        <!-- ── Posts recientes ───────────────────────────────── -->
        <div class="hp-widget">
            <h4 class="hp-widget-title">Posts recientes</h4>

            <?php
            $recent = new WP_Query( [
                'post_type'      => 'post',
                'posts_per_page' => 5,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ] );

            if ( $recent->have_posts() ) :
            ?>
            <ul class="sidebar__recent-posts">
                <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
                <li class="sidebar__recent-item">
                    <!-- Miniatura -->
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>"
                           class="sidebar__recent-thumb"
                           aria-hidden="true"
                           tabindex="-1">
                            <?php the_post_thumbnail( 'thumbnail', [
                                'class'   => 'sidebar__recent-img',
                                'alt'     => '',
                                'loading' => 'lazy',
                            ] ); ?>
                        </a>
                    <?php endif; ?>

                    <div class="sidebar__recent-info">
                        <a href="<?php the_permalink(); ?>" class="sidebar__recent-title">
                            <?php the_title(); ?>
                        </a>
                        <time class="sidebar__recent-date"
                              datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                            <?php echo esc_html( get_the_date( 'j M, Y' ) ); ?>
                        </time>
                    </div>
                </li>
                <?php endwhile; ?>
            </ul>
            <?php
            wp_reset_postdata();
            endif;
            ?>
        </div>


        <!-- ── Categorías ────────────────────────────────────── -->
        <div class="hp-widget">
            <h4 class="hp-widget-title">Categorías</h4>
            <ul class="sidebar__categories">
                <?php
                $cats = get_categories( [
                    'orderby' => 'count',
                    'order'   => 'DESC',
                    'number'  => 8,
                ] );

                foreach ( $cats as $cat ) :
                ?>
                <li>
                    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
                       class="sidebar__cat-link">
                        <span class="sidebar__cat-name">
                            <?php echo esc_html( $cat->name ); ?>
                        </span>
                        <span class="sidebar__cat-count">
                            <?php echo (int) $cat->count; ?>
                        </span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>


        <!-- ── CTA: Contacto ────────────────────────────────── -->
        <div class="hp-widget sidebar__cta">
            <h4 class="sidebar__cta-title">¿Necesitas orientación?</h4>
            <p class="sidebar__cta-text">
                Nuestro equipo de profesionales puede ayudarte.
            </p>
            <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"
               class="btn-primary sidebar__cta-btn">
                Contáctanos
            </a>
        </div>

    <?php endif; ?>

</div>
