<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — single.php (Post individual del blog)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Template para un post individual.
 * WordPress lo usa automáticamente para /blog/titulo-del-post/.
 *
 * Estructura:
 *   1. Encabezado: categoría, título grande, meta (fecha, autor)
 *   2. Imagen destacada
 *   3. Contenido con the_content() (estilos de prosa en .post-content)
 *   4. Botones de compartir (WhatsApp, Facebook, LinkedIn)
 *   5. 3 posts relacionados (misma categoría)
 *   6. CTA Banner (desde footer.php)
 *
 * @package HumanPeru
 */

get_header();

// Iniciar el loop de WordPress
while ( have_posts() ) :
    the_post();

    // Datos del post para compartir
    $post_url     = urlencode( get_the_permalink() );
    $post_title   = urlencode( get_the_title() );
    $post_excerpt = urlencode( wp_trim_words( get_the_excerpt(), 20, '...' ) );

    // Primera categoría del post
    $categorias = get_the_category();
    $cat_nombre = ! empty( $categorias ) ? $categorias[0]->name : '';
    $cat_link   = ! empty( $categorias ) ? get_category_link( $categorias[0]->term_id ) : '';
    $cat_id     = ! empty( $categorias ) ? $categorias[0]->term_id : 0;
?>

<div class="hero-bar" aria-hidden="true"></div>

<!-- ════════════════════════════════════════════════════════════════
     ENCABEZADO DEL POST
     ════════════════════════════════════════════════════════════════ -->

<article class="single-post">
    <div class="container">

        <header class="single-post__header hp-animate">

            <!-- Categoría -->
            <?php if ( $cat_nombre ) : ?>
                <a href="<?php echo esc_url( $cat_link ); ?>"
                   class="single-post__category">
                    <?php echo esc_html( $cat_nombre ); ?>
                </a>
            <?php endif; ?>

            <!-- Título -->
            <h1 class="single-post__title">
                <?php the_title(); ?>
            </h1>

            <!-- Meta: fecha + autor + tiempo de lectura -->
            <div class="single-post__meta">
                <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                    <?php echo esc_html( get_the_date( 'j \d\e F, Y' ) ); ?>
                </time>
                <span class="single-post__meta-sep">·</span>
                <span>Por <?php the_author(); ?></span>

                <?php
                // Tiempo de lectura estimado (200 palabras por minuto)
                $contenido = get_the_content();
                $palabras  = str_word_count( strip_tags( $contenido ) );
                $minutos   = max( 1, ceil( $palabras / 200 ) );
                ?>
                <span class="single-post__meta-sep">·</span>
                <span><?php echo $minutos; ?> min de lectura</span>
            </div>

        </header>


        <!-- ════════════════════════════════════════════════════
             IMAGEN DESTACADA
             ════════════════════════════════════════════════════ -->

        <?php if ( has_post_thumbnail() ) : ?>
            <div class="single-post__featured hp-animate">
                <?php the_post_thumbnail( 'large', [
                    'class'   => 'single-post__featured-img',
                    'alt'     => esc_attr( get_the_title() ),
                    'loading' => 'eager',
                ] ); ?>
            </div>
        <?php endif; ?>


        <!-- ════════════════════════════════════════════════════
             CONTENIDO DEL POST
             ════════════════════════════════════════════════════
             .post-content aplica estilos de prosa a todo el HTML
             generado por Gutenberg: p, h2, h3, ul, ol, blockquote,
             img, figure, etc. (definidos en style.css sección 13).
             ════════════════════════════════════════════════════ -->

        <div class="post-content hp-animate">
            <?php the_content(); ?>
        </div>


        <!-- ════════════════════════════════════════════════════
             ETIQUETAS DEL POST
             ════════════════════════════════════════════════════ -->

        <?php
        $tags = get_the_tags();
        if ( $tags ) :
        ?>
        <div class="single-post__tags">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
            <?php foreach ( $tags as $tag ) : ?>
                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                   class="single-post__tag">
                    <?php echo esc_html( $tag->name ); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>


        <!-- ════════════════════════════════════════════════════
             BOTONES DE COMPARTIR
             ════════════════════════════════════════════════════ -->

        <div class="share-buttons hp-animate">
            <span class="share-buttons__label">Compartir:</span>

            <!-- WhatsApp -->
            <a href="https://wa.me/?text=<?php echo $post_title . '%20' . $post_url; ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="share-buttons__btn share-buttons__btn--whatsapp"
               aria-label="Compartir en WhatsApp">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp
            </a>

            <!-- Facebook -->
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="share-buttons__btn share-buttons__btn--facebook"
               aria-label="Compartir en Facebook">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Facebook
            </a>

            <!-- LinkedIn -->
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="share-buttons__btn share-buttons__btn--linkedin"
               aria-label="Compartir en LinkedIn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
                LinkedIn
            </a>
        </div>

    </div><!-- /.container -->
</article>


<!-- ════════════════════════════════════════════════════════════════
     POSTS RELACIONADOS — 3 posts de la misma categoría
     ════════════════════════════════════════════════════════════════ -->

<?php
$related_query = new WP_Query( [
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'post__not_in'   => [ get_the_ID() ],  // Excluir el post actual
    'cat'            => $cat_id,            // Misma categoría
    'orderby'        => 'rand',             // Aleatorio para variedad
] );

if ( $related_query->have_posts() ) :
?>

<section class="section section--alt">
    <div class="container">

        <div class="section__header hp-animate">
            <h2>Artículos relacionados</h2>
            <p>Más recursos sobre <?php echo esc_html( $cat_nombre ?: 'salud mental' ); ?>.</p>
        </div>

        <div class="blog-grid">
            <?php
            $ri = 0;
            while ( $related_query->have_posts() ) :
                $related_query->the_post();
                $ri++;
                $r_cats = get_the_category();
                $r_cat  = ! empty( $r_cats ) ? $r_cats[0]->name : '';
            ?>

            <article class="card blog-card hp-animate hp-animate--delay-<?php echo $ri; ?>">

                <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                        <?php the_post_thumbnail( 'hp-card', [
                            'class'   => 'blog-card__image card__image',
                            'alt'     => esc_attr( get_the_title() ),
                            'loading' => 'lazy',
                        ] ); ?>
                    </a>
                <?php endif; ?>

                <div class="blog-card__body card__body">
                    <?php if ( $r_cat ) : ?>
                        <span class="blog-card__category"><?php echo esc_html( $r_cat ); ?></span>
                    <?php endif; ?>

                    <h3 class="blog-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>

                    <p class="blog-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>

                    <div class="blog-card__footer">
                        <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                            <?php echo esc_html( get_the_date( 'j \d\e F, Y' ) ); ?>
                        </time>
                        <a href="<?php the_permalink(); ?>" class="blog-card__read-more">Leer más →</a>
                    </div>
                </div>

            </article>

            <?php endwhile; ?>
        </div>

    </div>
</section>

<?php
wp_reset_postdata();
endif; // related posts
?>

<?php endwhile; // main loop ?>

<?php get_footer(); ?>
