<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — search.php (Resultados de búsqueda)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Template dedicado para resultados de búsqueda (/?s=término).
 * Reemplaza el fallback que antes manejaba index.php.
 *
 * Mejoras sobre index.php:
 *   - Contador de resultados
 *   - Formulario de nueva búsqueda integrado en el hero
 *   - Diferencia entre posts y páginas en los resultados
 *   - Estado vacío con sugerencias y links rápidos
 *
 * @package HumanPeru
 */

get_header();

// Datos de la búsqueda
$termino = get_search_query();
$total   = $wp_query->found_posts;
?>

<div class="hero-bar" aria-hidden="true"></div>

<!-- ════════════════════════════════════════════════════════════════
     HERO DE BÚSQUEDA
     ════════════════════════════════════════════════════════════════ -->

<section class="search-hero">
    <div class="container">
        <div class="search-hero__content hp-animate">

            <span class="tag tag--blue">Búsqueda</span>

            <h1 class="search-hero__title">
                <?php if ( $total > 0 ) : ?>
                    <?php printf(
                        '%d %s para &ldquo;%s&rdquo;',
                        $total,
                        $total === 1 ? 'resultado' : 'resultados',
                        esc_html( $termino )
                    ); ?>
                <?php else : ?>
                    Sin resultados para &ldquo;<?php echo esc_html( $termino ); ?>&rdquo;
                <?php endif; ?>
            </h1>

            <!-- Formulario para buscar de nuevo sin volver atrás -->
            <form role="search"
                  method="get"
                  action="<?php echo esc_url( home_url( '/' ) ); ?>"
                  class="search-hero__form">
                <input type="search"
                       name="s"
                       class="search-hero__input"
                       value="<?php echo esc_attr( $termino ); ?>"
                       placeholder="Buscar en Human Perú..."
                       aria-label="Nueva búsqueda">
                <button type="submit" class="search-hero__btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    Buscar
                </button>
            </form>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     RESULTADOS + SIDEBAR
     ════════════════════════════════════════════════════════════════ -->

<section class="section">
    <div class="container">
        <div class="blog-layout">

            <!-- ── Resultados ─────────────────────────────────── -->
            <div class="blog-layout__content">

                <?php if ( have_posts() ) : ?>

                    <div class="blog-list">

                        <?php
                        $i = 0;
                        while ( have_posts() ) :
                            the_post();
                            $i++;

                            // Detectar tipo de contenido
                            $es_pagina = ( get_post_type() === 'page' );
                            $tipo_label = $es_pagina ? 'Página' : '';

                            // Categoría (solo para posts)
                            $cats     = get_the_category();
                            $cat_name = '';
                            $cat_link = '';
                            if ( ! $es_pagina && ! empty( $cats ) ) {
                                $cat_name = $cats[0]->name;
                                $cat_link = get_category_link( $cats[0]->term_id );
                            }

                            $delay = ( ( $i - 1 ) % 3 ) + 1;
                        ?>

                        <article <?php post_class( 'blog-list__item hp-animate hp-animate--delay-' . $delay ); ?>>

                            <!-- Imagen destacada -->
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

                                <!-- Etiqueta: categoría o tipo de contenido -->
                                <?php if ( $cat_name ) : ?>
                                    <a href="<?php echo esc_url( $cat_link ); ?>"
                                       class="blog-list__category">
                                        <?php echo esc_html( $cat_name ); ?>
                                    </a>
                                <?php elseif ( $es_pagina ) : ?>
                                    <span class="blog-list__category search-result__type">
                                        Página
                                    </span>
                                <?php endif; ?>

                                <!-- Título -->
                                <h2 class="blog-list__title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <!-- Meta -->
                                <div class="blog-list__meta">
                                    <?php if ( ! $es_pagina ) : ?>
                                        <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                                            <?php echo esc_html( get_the_date( 'j \d\e F, Y' ) ); ?>
                                        </time>
                                        <span class="blog-list__meta-sep">·</span>
                                        <span>Por <?php the_author(); ?></span>
                                    <?php else : ?>
                                        <span><?php echo esc_url( get_the_permalink() ); ?></span>
                                    <?php endif; ?>
                                </div>

                                <!-- Extracto -->
                                <p class="blog-list__excerpt">
                                    <?php echo esc_html( get_the_excerpt() ); ?>
                                </p>

                                <!-- Link -->
                                <a href="<?php the_permalink(); ?>"
                                   class="blog-list__read-more">
                                    <?php echo $es_pagina ? 'Ver página' : 'Leer artículo'; ?>
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
                    <nav class="blog-pagination" aria-label="Paginación de resultados">
                        <?php
                        the_posts_pagination( [
                            'mid_size'  => 2,
                            'prev_text' => '&larr; Anterior',
                            'next_text' => 'Siguiente &rarr;',
                        ] );
                        ?>
                    </nav>

                <?php else : ?>

                    <!-- ── Sin resultados ─────────────────────── -->
                    <div class="search-empty hp-animate">

                        <!-- Ícono decorativo -->
                        <div class="search-empty__icon" aria-hidden="true">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                <line x1="8" y1="8" x2="14" y2="14"></line>
                                <line x1="14" y1="8" x2="8" y2="14"></line>
                            </svg>
                        </div>

                        <h2>No encontramos lo que buscas</h2>

                        <p>
                            No hay resultados para &ldquo;<?php echo esc_html( $termino ); ?>&rdquo;.
                            Prueba con otros términos o navega por nuestras secciones.
                        </p>

                        <!-- Sugerencias -->
                        <div class="search-empty__suggestions">
                            <p><strong>Sugerencias:</strong></p>
                            <ul>
                                <li>Verifica la ortografía de tu búsqueda.</li>
                                <li>Usa palabras más generales (ej: "terapia" en vez de "terapia cognitivo-conductual").</li>
                                <li>Prueba con sinónimos (ej: "capacitación" o "taller").</li>
                            </ul>
                        </div>

                        <!-- Links rápidos -->
                        <div class="search-empty__links">
                            <a href="<?php echo esc_url( home_url( '/servicios/' ) ); ?>"
                               class="btn-secondary">Servicios</a>
                            <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"
                               class="btn-secondary">Blog</a>
                            <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"
                               class="btn-primary">Contáctanos</a>
                        </div>

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
