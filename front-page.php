<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — front-page.php (Página de inicio)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Template de la página principal del sitio.
 * WordPress usa front-page.php automáticamente cuando se configura
 * una página estática como "Página de inicio" en Ajustes → Lectura.
 *
 * Secciones (de arriba a abajo):
 *   1. Hero — Título principal + imagen + CTAs
 *   2. Introducción — Mensaje de bienvenida
 *   3. Historia — Cómo nació Human Perú
 *   4. Nuestros servicios — 6 tarjetas
 *   5. Press Badge — Mención en El Comercio
 *   6. Nuestra visión — Hacia dónde vamos
 *   7. Blog — 3 posts más recientes
 *   8. CTA Banner — Llamada a la acción final
 *
 * ENFOQUE HÍBRIDO:
 *   La estructura visual está fija aquí en PHP (no se rompe).
 *   El contenido editable se gestiona desde wp-admin:
 *     - Imagen destacada del hero → Imagen destacada de la página
 *     - Posts del blog → Se publican desde el editor de WP
 *
 * @package HumanPeru
 */

get_header();
?>


<!-- ════════════════════════════════════════════════════════════════
     1. HERO — Sección principal
     ════════════════════════════════════════════════════════════════
     Layout: texto a la izquierda (60%) + imagen a la derecha (40%)
     en desktop. En móvil: texto arriba, imagen abajo.

     La barra decorativa superior (4px) va de naranja → amarillo → azul.
     ════════════════════════════════════════════════════════════════ -->

<!-- Barra decorativa gradiente en la parte superior del hero -->
<div class="hero-bar" aria-hidden="true"></div>

<section class="hero">
    <div class="hero__container">

        <!-- ── Columna izquierda: Texto ──────────────────────── -->
        <div class="hero__text">

            <!-- Badge decorativo sobre el título -->
            <span class="hero__badge">
                Salud mental para todos
            </span>

            <h1 class="hero__title">
                Promovemos la <span class="highlight">salud mental</span> con la educación, la sensibilización y el apoyo comunitario
            </h1>

            <p class="hero__description">
                Trabajamos con la comunidad y los diversos actores sociales para un fin en común: promover la vida y convivencia saludable.
            </p>

            <!-- Botones de acción -->
            <div class="hero__actions">
                <a href="<?php echo esc_url( home_url( '/servicios/' ) ); ?>"
                   class="btn-primary btn-large">
                    Nuestros servicios
                </a>
                <a href="<?php echo esc_url( home_url( '/nosotros/' ) ); ?>"
                   class="btn-secondary btn-large">
                    Saber más
                </a>
            </div>

            <!-- Estadísticas rápidas debajo de los botones -->
            <div class="hero__stats">
                <div class="hero__stat">
                    <span class="hero__stat-number">500+</span>
                    <span class="hero__stat-label">Personas atendidas</span>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-number">20+</span>
                    <span class="hero__stat-label">Profesionales</span>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-number">15+</span>
                    <span class="hero__stat-label">Programas activos</span>
                </div>
                <div class="hero__stat">
                    <span class="hero__stat-number">2024</span>
                    <span class="hero__stat-label">Año de fundación</span>
                </div>
            </div>

        </div>

        <!-- ── Columna derecha: Imagen ───────────────────────── -->
        <div class="hero__image">
            <?php
            // Mostrar la imagen destacada de la página.
            // El practicante puede cambiarla desde wp-admin → Páginas → Inicio
            // → Imagen destacada sin tocar código.
            if ( has_post_thumbnail() ) :
                the_post_thumbnail( 'hp-hero', [
                    'class'   => 'hero__img',
                    'alt'     => 'Human Perú — Promoviendo la salud mental en el Perú',
                    'loading' => 'eager', // La imagen del hero NO lleva lazy (es lo primero visible)
                ] );
            else :
                // Placeholder si no hay imagen destacada asignada
            ?>
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero-placeholder.jpg' ); ?>"
                     alt="Equipo de Human Perú trabajando por la salud mental"
                     class="hero__img"
                     width="600"
                     height="400"
                     loading="eager">
            <?php endif; ?>
        </div>

    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     2. INTRODUCCIÓN — Mensaje de bienvenida
     ════════════════════════════════════════════════════════════════ -->

<section class="section section--alt" id="introduccion">
    <div class="container">
        <div class="intro hp-animate">

            <div class="intro__content">
                <h2 class="intro__title">
                    Trabajando con profesionales, instituciones y la comunidad
                </h2>

                <div class="intro__text">
                    <p>
                        En Human Perú creemos que la salud mental es un derecho fundamental.
                        Nuestra labor se centra en crear espacios seguros de diálogo, educación
                        y acompañamiento donde cada persona pueda desarrollar su potencial
                        emocional y psicológico.
                    </p>
                    <p>
                        Colaboramos con instituciones educativas, empresas, organizaciones
                        comunitarias y profesionales de la salud para diseñar programas
                        que respondan a las necesidades reales de la población peruana.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     3. HISTORIA — Cómo nació Human Perú
     ════════════════════════════════════════════════════════════════
     2 columnas: imagen a la izquierda, texto a la derecha.
     ════════════════════════════════════════════════════════════════ -->

<section class="section" id="historia">
    <div class="container">
        <div class="two-col">

            <!-- Columna izquierda: imagen -->
            <div class="two-col__media hp-animate hp-animate--left">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/historia-humanperu.jpg' ); ?>"
                     alt="Fundadores de Human Perú en su primera reunión"
                     class="two-col__img"
                     width="560"
                     height="380"
                     loading="lazy">
            </div>

            <!-- Columna derecha: texto -->
            <div class="two-col__text hp-animate hp-animate--right">

                <span class="tag tag--orange">Nuestra historia</span>

                <h2>Nacemos para promover la salud mental</h2>

                <p>
                    Human Perú nació en <strong>2024</strong>, gracias a la visión de un grupo
                    de profesionales comprometidos con la salud mental en la familia, los centros
                    laborales y en espacios comunitarios.
                </p>
                <p>
                    Desde nuestros inicios, hemos trabajado para construir una red de apoyo
                    que conecte a especialistas, instituciones y comunidades en torno a un
                    objetivo común: que cada peruano pueda acceder a herramientas que
                    mejoren su bienestar emocional.
                </p>

                <a href="<?php echo esc_url( home_url( '/nosotros/' ) ); ?>"
                   class="btn-primary">
                    Más información
                </a>

            </div>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     4. NUESTROS SERVICIOS — 6 tarjetas
     ════════════════════════════════════════════════════════════════
     Cada tarjeta usa get_template_part() con variables ($args).
     El delay escalonado crea un efecto cascada en la animación.
     ════════════════════════════════════════════════════════════════ -->

<section class="section section--alt" id="servicios">
    <div class="container">

        <!-- Encabezado de la sección -->
        <div class="section__header hp-animate">
            <span class="tag tag--blue">Lo que hacemos</span>
            <h2>Nuestros servicios</h2>
            <p>
                Ofrecemos soluciones integrales para el cuidado de la salud mental,
                adaptadas a las necesidades de personas, instituciones y comunidades.
            </p>
        </div>

        <!-- Grilla de 6 servicios (1 col móvil → 2 col tablet → 3 col desktop) -->
        <div class="servicios-grid">

            <?php
            // ── Datos de los 6 servicios ─────────────────────────
            // Definidos como array PHP para mantener la estructura
            // fija pero fácil de editar por un desarrollador.
            $servicios = [
                [
                    'icon'        => 'clipboard',
                    'title'       => 'Evaluación y diagnóstico',
                    'description' => 'Evaluaciones psicológicas profesionales para identificar necesidades emocionales y diseñar planes de intervención personalizados.',
                    'delay'       => 1,
                ],
                [
                    'icon'        => 'users',
                    'title'       => 'Terapia grupal e individual',
                    'description' => 'Espacios terapéuticos seguros con profesionales especializados, tanto en sesiones individuales como en dinámicas grupales.',
                    'delay'       => 2,
                ],
                [
                    'icon'        => 'shield',
                    'title'       => 'Programas de prevención',
                    'description' => 'Programas diseñados para prevenir problemas de salud mental en poblaciones vulnerables antes de que se desarrollen.',
                    'delay'       => 3,
                ],
                [
                    'icon'        => 'heart',
                    'title'       => 'Orientación y apoyo psicosocial',
                    'description' => 'Acompañamiento emocional y orientación práctica para personas y familias que enfrentan situaciones difíciles.',
                    'delay'       => 1,
                ],
                [
                    'icon'        => 'book',
                    'title'       => 'Educación y capacitación',
                    'description' => 'Talleres, cursos y charlas para profesionales, docentes y la comunidad sobre temas de salud mental y bienestar.',
                    'delay'       => 2,
                ],
                [
                    'icon'        => 'link',
                    'title'       => 'Red de apoyo y seguimiento',
                    'description' => 'Construimos redes de soporte entre profesionales, instituciones y comunidades para un seguimiento continuo y efectivo.',
                    'delay'       => 3,
                ],
            ];

            // Renderizar cada tarjeta usando el template part
            foreach ( $servicios as $servicio ) :
                get_template_part( 'template-parts/service-card', null, $servicio );
            endforeach;
            ?>

        </div>

        <!-- Botón para ver todos los servicios -->
        <div class="section__footer hp-animate">
            <a href="<?php echo esc_url( home_url( '/servicios/' ) ); ?>"
               class="btn-secondary">
                Más servicios
            </a>
        </div>

    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     5. PRESS BADGE — Mención en El Comercio
     ════════════════════════════════════════════════════════════════ -->

<section class="section" id="prensa">
    <div class="container">
        <?php get_template_part( 'template-parts/press-badge' ); ?>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     6. NUESTRA VISIÓN — Hacia dónde vamos
     ════════════════════════════════════════════════════════════════
     2 columnas: imagen a la izquierda, texto a la derecha.
     Fondo alterno (gris claro) para diferenciar de la sección anterior.
     ════════════════════════════════════════════════════════════════ -->

<section class="section section--alt" id="vision">
    <div class="container">
        <div class="two-col">

            <!-- Columna izquierda: imagen -->
            <div class="two-col__media hp-animate hp-animate--left">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/vision-humanperu.jpg' ); ?>"
                     alt="Comunidad participando en taller de salud mental de Human Perú"
                     class="two-col__img"
                     width="560"
                     height="380"
                     loading="lazy">
            </div>

            <!-- Columna derecha: texto -->
            <div class="two-col__text hp-animate hp-animate--right">

                <span class="tag tag--blue">Hacia dónde vamos</span>

                <h2>Nuestra visión</h2>

                <p>
                    Ser la principal referencia en Perú para la promoción, educación y apoyo
                    en salud mental, fomentando una sociedad inclusiva y consciente donde cada
                    individuo pueda alcanzar su pleno potencial y bienestar psicológico.
                </p>

                <p>
                    Trabajamos cada día para que la salud mental deje de ser un tabú
                    y se convierta en una prioridad en las familias, las escuelas,
                    los centros de trabajo y las políticas públicas del Perú.
                </p>

                <a href="<?php echo esc_url( home_url( '/nosotros/' ) ); ?>"
                   class="btn-secondary">
                    Conócenos
                </a>

            </div>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     7. BLOG — 3 posts más recientes
     ════════════════════════════════════════════════════════════════
     Usa WP_Query para obtener los últimos 3 posts publicados.
     Cada post muestra: imagen destacada, categoría, título, fecha
     y extracto.

     IMPORTANTE: wp_reset_postdata() al final del loop para restaurar
     el objeto $post global. Sin esto, el footer y otros elementos
     podrían leer datos del último post en vez de la página actual.
     ════════════════════════════════════════════════════════════════ -->

<?php
// Consulta personalizada para los 3 posts más recientes
$blog_query = new WP_Query( [
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    // Excluir posts sin imagen destacada (opcionar: comentar si quieres incluirlos)
    // 'meta_key'       => '_thumbnail_id',
] );

// Solo mostrar la sección si hay posts publicados
if ( $blog_query->have_posts() ) :
?>

<section class="section" id="blog">
    <div class="container">

        <!-- Encabezado de la sección -->
        <div class="section__header hp-animate">
            <span class="tag tag--orange">Artículos recientes</span>
            <h2>Blog</h2>
            <p>
                Recursos, reflexiones y noticias sobre salud mental
                para la comunidad peruana.
            </p>
        </div>

        <!-- Grilla de posts (1 col móvil → 2 col tablet → 3 col desktop) -->
        <div class="blog-grid">

            <?php
            $post_index = 0;
            while ( $blog_query->have_posts() ) :
                $blog_query->the_post();
                $post_index++;

                // Obtener la primera categoría del post
                $categorias = get_the_category();
                $categoria_nombre = ! empty( $categorias ) ? $categorias[0]->name : 'General';
            ?>

            <article class="card blog-card hp-animate hp-animate--delay-<?php echo $post_index; ?>">

                <!-- Imagen destacada del post -->
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

                    <!-- Categoría del post -->
                    <span class="blog-card__category">
                        <?php echo esc_html( $categoria_nombre ); ?>
                    </span>

                    <!-- Título del post (link al artículo) -->
                    <h3 class="blog-card__title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>

                    <!-- Extracto del post -->
                    <p class="blog-card__excerpt">
                        <?php echo esc_html( get_the_excerpt() ); ?>
                    </p>

                    <!-- Fecha + link "Leer más" -->
                    <div class="blog-card__footer">
                        <time class="blog-card__date"
                              datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                            <?php echo esc_html( get_the_date( 'j \d\e F, Y' ) ); ?>
                        </time>
                        <a href="<?php the_permalink(); ?>"
                           class="blog-card__read-more">
                            Leer más →
                        </a>
                    </div>

                </div>

            </article>

            <?php endwhile; ?>

        </div><!-- /.blog-grid -->

        <!-- Botón para ir al blog completo -->
        <div class="section__footer hp-animate">
            <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"
               class="btn-primary">
                Leer más artículos
            </a>
        </div>

    </div>
</section>

<?php
// IMPORTANTE: restaurar el $post global al de la página actual.
// Sin esto, get_footer() y otros template parts podrían leer
// datos del último post del loop en vez de la página de inicio.
wp_reset_postdata();

endif; // Fin if ( $blog_query->have_posts() )
?>


<!-- ════════════════════════════════════════════════════════════════
     8. CTA BANNER — Se incluye automáticamente desde footer.php
     ════════════════════════════════════════════════════════════════
     El CTA banner ya se carga en footer.php con:
       get_template_part( 'template-parts/cta-banner' );
     No lo duplicamos aquí. Si necesitas uno adicional en el medio
     de la página, descomenta la línea siguiente:
     ════════════════════════════════════════════════════════════════ -->
<?php // get_template_part( 'template-parts/cta-banner' ); ?>


<?php get_footer(); ?>
