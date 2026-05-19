<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — index.php (Template de respaldo obligatorio)
 * ═══════════════════════════════════════════════════════════════════
 *
 * WordPress REQUIERE que todo theme tenga un index.php.
 * Es el último recurso en la jerarquía de templates: si WordPress
 * no encuentra un template más específico (front-page.php,
 * archive.php, single.php, page-*.php), usa este archivo.
 *
 * En la práctica, con nuestro theme completo, index.php se usa
 * raramente. Casos posibles:
 *   - Resultados de búsqueda (si no existe search.php)
 *   - Tipos de contenido sin template propio
 *   - Páginas sin template asignado
 *
 * Su layout replica el del blog (archive.php): contenido + sidebar.
 *
 * @package HumanPeru
 */

get_header();
?>

<div class="hero-bar" aria-hidden="true"></div>

<!-- ════════════════════════════════════════════════════════════════
     ENCABEZADO
     ════════════════════════════════════════════════════════════════ -->

<section class="blog-hero">
    <div class="container">
        <div class="blog-hero__content hp-animate">

            <?php if ( is_search() ) : ?>
                <span class="tag tag--blue">Resultados</span>
                <h1 class="blog-hero__title">
                    Resultados para: &ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;
                </h1>
                <p class="blog-hero__description">
                    <?php
                    global $wp_query;
                    $total = $wp_query->found_posts;
                    printf(
                        '%d %s encontrado%s.',
                        $total,
                        $total === 1 ? 'resultado' : 'resultados',
                        $total === 1 ? '' : 's'
                    );
                    ?>
                </p>

            <?php elseif ( is_page() ) : ?>
                <h1 class="blog-hero__title"><?php the_title(); ?></h1>

            <?php else : ?>
                <span class="tag tag--orange">Artículos</span>
                <h1 class="blog-hero__title">Blog</h1>
                <p class="blog-hero__description">
                    Recursos, reflexiones y noticias sobre salud mental.
                </p>
            <?php endif; ?>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     CONTENIDO + SIDEBAR
     ════════════════════════════════════════════════════════════════ -->

<section class="section">
    <div class="container">
        <div class="blog-layout">

            <!-- ── Contenido principal ────────────────────────── -->
            <div class="blog-layout__content">

                <?php if ( have_posts() ) : ?>

                    <?php
                    // Si es una página estática, mostrar su contenido
                    if ( is_page() ) :
                        while ( have_posts() ) :
                            the_post();
                    ?>
                        <div class="post-content">
                            <?php the_content(); ?>
                        </div>
                    <?php
                        endwhile;

                    // Si es un listado (búsqueda, archivo genérico), mostrar loop
                    else :
                    ?>

                    <div class="blog-list">
                        <?php
                        $i = 0;
                        while ( have_posts() ) :
                            the_post();
                            $i++;
                            $cats     = get_the_category();
                            $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                            $cat_link = ! empty( $cats ) ? get_category_link( $cats[0]->term_id ) : '';
                            $delay    = ( ( $i - 1 ) % 3 ) + 1;
                        ?>

                        <article <?php post_class( 'blog-list__item hp-animate hp-animate--delay-' . $delay ); ?>>

                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>"
                                   class="blog-list__image-link"
                                   aria-hidden="true" tabindex="-1">
                                    <?php the_post_thumbnail( 'hp-card', [
                                        'class'   => 'blog-list__image',
                                        'alt'     => esc_attr( get_the_title() ),
                                        'loading' => 'lazy',
                                    ] ); ?>
                                </a>
                            <?php endif; ?>

                            <div class="blog-list__body">

                                <?php if ( $cat_name ) : ?>
                                    <a href="<?php echo esc_url( $cat_link ); ?>"
                                       class="blog-list__category">
                                        <?php echo esc_html( $cat_name ); ?>
                                    </a>
                                <?php endif; ?>

                                <h2 class="blog-list__title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <div class="blog-list__meta">
                                    <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                                        <?php echo esc_html( get_the_date( 'j \d\e F, Y' ) ); ?>
                                    </time>
                                    <span class="blog-list__meta-sep">·</span>
                                    <span>Por <?php the_author(); ?></span>
                                </div>

                                <p class="blog-list__excerpt">
                                    <?php echo esc_html( get_the_excerpt() ); ?>
                                </p>

                                <a href="<?php the_permalink(); ?>"
                                   class="blog-list__read-more">
                                    Leer artículo
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" aria-hidden="true">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>

                            </div>
                        </article>

                        <?php endwhile; ?>
                    </div>

                    <!-- Paginación -->
                    <nav class="blog-pagination" aria-label="Paginación">
                        <?php
                        the_posts_pagination( [
                            'mid_size'  => 2,
                            'prev_text' => '&larr; Anterior',
                            'next_text' => 'Siguiente &rarr;',
                        ] );
                        ?>
                    </nav>

                    <?php endif; // is_page vs listado ?>

                <?php else : ?>

                    <!-- Sin resultados -->
                    <div class="blog-empty hp-animate">
                        <?php if ( is_search() ) : ?>
                            <h2>Sin resultados</h2>
                            <p>
                                No encontramos contenido para
                                &ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;.
                                Intenta con otros términos.
                            </p>
                        <?php else : ?>
                            <h2>No hay contenido disponible</h2>
                            <p>Vuelve pronto para encontrar recursos sobre salud mental.</p>
                        <?php endif; ?>

                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                           class="btn-primary">
                            Volver al inicio
                        </a>
                    </div>

                <?php endif; ?>

            </div>

            <!-- ── Sidebar ───────────────────────────────────── -->
            <aside class="blog-layout__sidebar">
                <?php get_sidebar(); ?>
            </aside>

        </div>
    </div>
</section>

<?php get_footer(); ?>
