<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — archive.php (Listado del Blog)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Template para el archivo de posts (/blog/).
 * También se usa para archivos de categoría, tag, fecha y autor.
 *
 * Layout: contenido principal (70%) + sidebar (30%) en desktop.
 * En móvil: 1 columna (contenido arriba, sidebar abajo).
 *
 * WordPress usa archive.php cuando:
 *   - Visitas /blog/ (si se configura como página de entradas)
 *   - Visitas /category/nombre/ (archivo de categoría)
 *   - Visitas /tag/nombre/ (archivo de etiqueta)
 *   - Visitas /2026/04/ (archivo por fecha)
 *
 * @package HumanPeru
 */

get_header();
?>

<div class="hero-bar" aria-hidden="true"></div>

<!-- ════════════════════════════════════════════════════════════════
     HERO DEL BLOG
     ════════════════════════════════════════════════════════════════ -->

<section class="blog-hero">
    <div class="container">
        <div class="blog-hero__content hp-animate">
            <span class="tag tag--orange">Artículos</span>

            <?php if ( is_category() ) : ?>
                <h1 class="blog-hero__title">
                    Categoría: <?php single_cat_title(); ?>
                </h1>
                <?php if ( category_description() ) : ?>
                    <p class="blog-hero__description">
                        <?php echo esc_html( strip_tags( category_description() ) ); ?>
                    </p>
                <?php endif; ?>

            <?php elseif ( is_tag() ) : ?>
                <h1 class="blog-hero__title">
                    Etiqueta: <?php single_tag_title(); ?>
                </h1>

            <?php elseif ( is_author() ) : ?>
                <h1 class="blog-hero__title">
                    Artículos de <?php the_author(); ?>
                </h1>

            <?php elseif ( is_date() ) : ?>
                <h1 class="blog-hero__title">
                    Archivo: <?php echo esc_html( get_the_date( 'F Y' ) ); ?>
                </h1>

            <?php else : ?>
                <h1 class="blog-hero__title">Blog</h1>
                <p class="blog-hero__description">
                    Recursos, reflexiones y noticias sobre salud mental
                    para la comunidad peruana.
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     CONTENIDO PRINCIPAL + SIDEBAR
     ════════════════════════════════════════════════════════════════ -->

<section class="section">
    <div class="container">
        <div class="blog-layout">

            <!-- ── Columna principal (70%) ────────────────────── -->
            <div class="blog-layout__content">

                <?php if ( have_posts() ) : ?>

                    <div class="blog-list">

                        <?php
                        $post_index = 0;
                        while ( have_posts() ) :
                            the_post();
                            $post_index++;

                            // Obtener la primera categoría
                            $categorias = get_the_category();
                            $cat_nombre = ! empty( $categorias ) ? $categorias[0]->name : '';
                            $cat_link   = ! empty( $categorias ) ? get_category_link( $categorias[0]->term_id ) : '';

                            // Delay de animación (cicla de 1 a 3)
                            $delay = ( ( $post_index - 1 ) % 3 ) + 1;
                        ?>

                        <article <?php post_class( 'blog-list__item hp-animate hp-animate--delay-' . $delay ); ?>>

                            <!-- Imagen destacada -->
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>"
                                   class="blog-list__image-link"
                                   aria-hidden="true"
                                   tabindex="-1">
                                    <?php the_post_thumbnail( 'hp-card', [
                                        'class'   => 'blog-list__image',
                                        'alt'     => esc_attr( get_the_title() ),
                                        'loading' => 'lazy',
                                    ] ); ?>
                                </a>
                            <?php endif; ?>

                            <!-- Contenido del post -->
                            <div class="blog-list__body">

                                <!-- Meta: categoría -->
                                <?php if ( $cat_nombre ) : ?>
                                    <a href="<?php echo esc_url( $cat_link ); ?>"
                                       class="blog-list__category">
                                        <?php echo esc_html( $cat_nombre ); ?>
                                    </a>
                                <?php endif; ?>

                                <!-- Título -->
                                <h2 class="blog-list__title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <!-- Meta: fecha + autor -->
                                <div class="blog-list__meta">
                                    <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                                        <?php echo esc_html( get_the_date( 'j \d\e F, Y' ) ); ?>
                                    </time>
                                    <span class="blog-list__meta-sep">·</span>
                                    <span>Por <?php the_author(); ?></span>
                                </div>

                                <!-- Extracto -->
                                <p class="blog-list__excerpt">
                                    <?php echo esc_html( get_the_excerpt() ); ?>
                                </p>

                                <!-- Link "Leer más" -->
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

                    </div><!-- /.blog-list -->


                    <!-- ── Paginación ────────────────────────────── -->
                    <nav class="blog-pagination" aria-label="Paginación del blog">
                        <?php
                        the_posts_pagination( [
                            'mid_size'  => 2,
                            'prev_text' => '← Anterior',
                            'next_text' => 'Siguiente →',
                        ] );
                        ?>
                    </nav>


                <?php else : ?>

                    <!-- Sin resultados -->
                    <div class="blog-empty hp-animate">
                        <h2>No hay artículos disponibles</h2>
                        <p>
                            Aún no hemos publicado artículos en esta sección.
                            Vuelve pronto para encontrar recursos sobre salud mental.
                        </p>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                           class="btn-primary">
                            Volver al inicio
                        </a>
                    </div>

                <?php endif; ?>

            </div><!-- /.blog-layout__content -->


            <!-- ── Sidebar (30%) ─────────────────────────────── -->
            <aside class="blog-layout__sidebar">
                <?php get_sidebar(); ?>
            </aside>

        </div>
    </div>
</section>

<?php get_footer(); ?>
