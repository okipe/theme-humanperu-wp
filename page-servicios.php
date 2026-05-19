<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — page-servicios.php (Página de Servicios)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Template Name: Servicios
 *
 * Secciones:
 *   1. Hero — Título + subtexto + imagen
 *   2. Índice rápido — 8 servicios con anclas + tarjeta "Tipos de atención"
 *   3. Lista de servicios — 8 bloques alternados (texto/imagen)
 *   4. CTA Banner — Incluido automáticamente desde footer.php
 *
 * Cada servicio tiene un id para scroll suave desde el índice.
 * Los textos están fijos en PHP (estructura que no se rompe).
 * La imagen destacada de la página es editable desde wp-admin.
 *
 * @package HumanPeru
 */

get_header();

// ── Datos de los 8 servicios ─────────────────────────────────
// Definidos como array PHP para mantener la estructura ordenada.
// Cada servicio tiene: id (ancla), icono, titulo, descripcion,
// objetivo, e imagen (ruta relativa en assets/images/servicios/).
$servicios = [
    [
        'id'          => 'evaluacion',
        'icon'        => 'clipboard',
        'titulo'      => 'Evaluación y diagnóstico',
        'descripcion' => 'Realizamos evaluaciones psicológicas integrales para identificar las necesidades emocionales, cognitivas y conductuales de cada persona. Nuestro equipo de profesionales utiliza herramientas validadas y entrevistas clínicas para construir un perfil completo que permita diseñar un plan de intervención personalizado y efectivo.',
        'objetivo'    => 'Identificar de manera precisa las necesidades de salud mental para diseñar intervenciones personalizadas que respondan a la realidad de cada individuo.',
        'imagen'      => 'servicio-evaluacion.jpg',
        'alt'         => 'Profesional de Human Perú realizando evaluación psicológica',
    ],
    [
        'id'          => 'psicoterapia',
        'icon'        => 'users',
        'titulo'      => 'Psicoterapia',
        'descripcion' => 'Ofrecemos sesiones de psicoterapia individual, de pareja, familiar y grupal. Trabajamos con enfoques basados en evidencia — cognitivo-conductual, humanista y sistémico — adaptándonos a las necesidades y contexto cultural de cada persona. Nuestros espacios son confidenciales, empáticos y orientados a resultados.',
        'objetivo'    => 'Brindar acompañamiento terapéutico profesional que promueva el bienestar emocional y la resolución de conflictos internos y relacionales.',
        'imagen'      => 'servicio-psicoterapia.jpg',
        'alt'         => 'Sesión de psicoterapia grupal en Human Perú',
    ],
    [
        'id'          => 'prevencion',
        'icon'        => 'shield',
        'titulo'      => 'Programas preventivos',
        'descripcion' => 'Diseñamos e implementamos programas de prevención en salud mental dirigidos a poblaciones vulnerables: niños, adolescentes, adultos mayores y comunidades en riesgo. Nuestros programas abordan temas como prevención del suicidio, manejo del estrés, bullying, adicciones y violencia familiar.',
        'objetivo'    => 'Prevenir problemas de salud mental antes de que se desarrollen, fortaleciendo los factores protectores en individuos y comunidades.',
        'imagen'      => 'servicio-prevencion.jpg',
        'alt'         => 'Taller de prevención de salud mental con jóvenes',
    ],
    [
        'id'          => 'crisis',
        'icon'        => 'alert',
        'titulo'      => 'Intervención en crisis',
        'descripcion' => 'Brindamos atención inmediata y especializada a personas que atraviesan situaciones de crisis emocional o psicológica. Nuestro equipo está capacitado para intervenir en emergencias, proporcionar primeros auxilios psicológicos y establecer planes de estabilización y seguimiento posterior.',
        'objetivo'    => 'Ofrecer contención y estabilización emocional inmediata a personas en situación de crisis, conectándolas con los recursos de apoyo necesarios.',
        'imagen'      => 'servicio-crisis.jpg',
        'alt'         => 'Equipo de intervención en crisis de Human Perú',
    ],
    [
        'id'          => 'orientacion',
        'icon'        => 'heart',
        'titulo'      => 'Orientación y apoyo psicosocial',
        'descripcion' => 'Acompañamos a personas y familias que enfrentan situaciones difíciles — duelo, separación, desplazamiento, desempleo — brindando orientación emocional y conectándolos con redes de soporte. Nuestro enfoque es integral: no solo atendemos la dimensión psicológica, sino también la social y comunitaria.',
        'objetivo'    => 'Acompañar a personas y familias en momentos difíciles, facilitando su acceso a recursos de apoyo social y emocional.',
        'imagen'      => 'servicio-orientacion.jpg',
        'alt'         => 'Sesión de orientación psicosocial familiar',
    ],
    [
        'id'          => 'educacion',
        'icon'        => 'book',
        'titulo'      => 'Educación y capacitación',
        'descripcion' => 'Desarrollamos talleres, cursos, diplomados y charlas para profesionales de la salud, docentes, líderes comunitarios y público general. Nuestros programas formativos combinan teoría y práctica, con metodologías participativas que facilitan el aprendizaje significativo en temas de salud mental y bienestar.',
        'objetivo'    => 'Formar agentes de cambio con conocimientos y herramientas prácticas para promover la salud mental en sus entornos.',
        'imagen'      => 'servicio-educacion.jpg',
        'alt'         => 'Capacitación sobre salud mental para profesionales',
    ],
    [
        'id'          => 'recreacion',
        'icon'        => 'sun',
        'titulo'      => 'Actividades recreativas',
        'descripcion' => 'Organizamos actividades lúdicas, artísticas y deportivas que promueven el bienestar emocional desde un enfoque recreativo. Arte-terapia, mindfulness, yoga, dinámicas grupales y encuentros comunitarios son parte de nuestra propuesta para integrar el cuidado de la salud mental en la vida cotidiana.',
        'objetivo'    => 'Promover el bienestar emocional a través de actividades lúdicas y recreativas que fortalezcan vínculos y reduzcan el estrés.',
        'imagen'      => 'servicio-recreacion.jpg',
        'alt'         => 'Actividad recreativa de bienestar emocional',
    ],
    [
        'id'          => 'red-apoyo',
        'icon'        => 'link',
        'titulo'      => 'Red de apoyo y seguimiento',
        'descripcion' => 'Construimos y articulamos redes de soporte entre profesionales, instituciones, familias y comunidades. Nuestro sistema de seguimiento permite monitorear el progreso de las personas atendidas, ajustar las intervenciones cuando sea necesario y garantizar la continuidad del cuidado a largo plazo.',
        'objetivo'    => 'Garantizar la continuidad del cuidado en salud mental a través de redes articuladas y un sistema de seguimiento sostenible.',
        'imagen'      => 'servicio-red-apoyo.jpg',
        'alt'         => 'Red de apoyo comunitario de Human Perú',
    ],
];

// ── Mapa de íconos SVG (reutilizado del service-card) ────────
$svg_icons = [
    'clipboard' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>',
    'users'     => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
    'shield'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>',
    'alert'     => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
    'heart'     => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>',
    'book'      => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>',
    'sun'       => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>',
    'link'      => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 7h3a5 5 0 015 5 5 5 0 01-5 5h-3m-6 0H6a5 5 0 01-5-5 5 5 0 015-5h3"/><line x1="8" y1="12" x2="16" y2="12"/></svg>',
];
?>


<!-- ════════════════════════════════════════════════════════════════
     1. HERO — Encabezado de la página
     ════════════════════════════════════════════════════════════════ -->

<div class="hero-bar" aria-hidden="true"></div>

<section class="page-hero">
    <div class="page-hero__container container">

        <div class="page-hero__text hp-animate">
            <span class="tag tag--orange">Lo que hacemos</span>
            <h1 class="page-hero__title">
                Servicios para promover la <span class="highlight">salud mental</span> y el bienestar
            </h1>
            <p class="page-hero__description">
                Ofrecemos soluciones integrales para personas, instituciones y comunidades.
                Cada servicio es diseñado con profesionales especializados y adaptado al
                contexto peruano.
            </p>
        </div>

        <div class="page-hero__image hp-animate hp-animate--right">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'hp-hero', [
                    'class'   => 'page-hero__img',
                    'alt'     => 'Servicios de salud mental de Human Perú',
                    'loading' => 'eager',
                ] ); ?>
            <?php else : ?>
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/servicios-hero.jpg' ); ?>"
                     alt="Servicios de salud mental de Human Perú"
                     class="page-hero__img"
                     width="560" height="380" loading="eager">
            <?php endif; ?>
        </div>

    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     2. ÍNDICE RÁPIDO — Navegación por anclas + Tipos de atención
     ════════════════════════════════════════════════════════════════
     Columna izquierda: 8 servicios con bullets de color que
     funcionan como links de scroll suave (href="#id").
     Columna derecha: tarjeta con los 3 tipos de atención.
     ════════════════════════════════════════════════════════════════ -->

<section class="section section--alt" id="indice-servicios">
    <div class="container">
        <div class="svc-index hp-animate">

            <!-- Columna izquierda: lista de servicios con anclas -->
            <div class="svc-index__list">
                <h2 class="svc-index__title">Nuestros servicios</h2>
                <p class="svc-index__subtitle">
                    Haz clic en cualquier servicio para ir directamente a su descripción.
                </p>

                <div class="svc-index__grid">
                    <?php
                    // Colores alternos para los bullets del índice
                    $bullet_colors = [ 'orange', 'blue', 'yellow', 'orange', 'blue', 'yellow', 'orange', 'blue' ];

                    foreach ( $servicios as $i => $svc ) :
                        $color = $bullet_colors[ $i ];
                    ?>
                    <a href="#<?php echo esc_attr( $svc['id'] ); ?>"
                       class="svc-index__item">
                        <span class="svc-index__bullet svc-index__bullet--<?php echo esc_attr( $color ); ?>"
                              aria-hidden="true">
                            <?php
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            echo $svg_icons[ $svc['icon'] ] ?? '';
                            ?>
                        </span>
                        <span class="svc-index__label">
                            <?php echo esc_html( $svc['titulo'] ); ?>
                        </span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Columna derecha: tarjeta "Tipos de atención" -->
            <div class="svc-index__aside">
                <div class="svc-types-card">
                    <h3 class="svc-types-card__title">Tipos de atención</h3>
                    <p class="svc-types-card__subtitle">
                        Adaptamos nuestros servicios a diferentes contextos:
                    </p>

                    <!-- Tipo 1: Individual -->
                    <div class="svc-types-card__item">
                        <div class="svc-types-card__icon svc-types-card__icon--orange" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div>
                            <strong>Individual</strong>
                            <p>Atención personalizada uno a uno con profesionales especializados.</p>
                        </div>
                    </div>

                    <!-- Tipo 2: Comunitaria -->
                    <div class="svc-types-card__item">
                        <div class="svc-types-card__icon svc-types-card__icon--blue" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                                <path d="M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                        </div>
                        <div>
                            <strong>Comunitaria</strong>
                            <p>Programas grupales para barrios, colegios y organizaciones locales.</p>
                        </div>
                    </div>

                    <!-- Tipo 3: Institucional -->
                    <div class="svc-types-card__item">
                        <div class="svc-types-card__icon svc-types-card__icon--yellow" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                            </svg>
                        </div>
                        <div>
                            <strong>Institucional</strong>
                            <p>Soluciones a medida para empresas, ONGs e instituciones educativas.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     3. LISTA DE SERVICIOS — 8 bloques alternados
     ════════════════════════════════════════════════════════════════
     Cada servicio alterna la posición:
       - Par (0, 2, 4, 6): texto izquierda, imagen derecha
       - Impar (1, 3, 5, 7): imagen izquierda, texto derecha
     Cada bloque tiene un id para el scroll suave desde el índice.
     ════════════════════════════════════════════════════════════════ -->

<section class="section" id="detalle-servicios">
    <div class="container">

        <?php foreach ( $servicios as $i => $svc ) :
            // Alternar dirección: par = normal, impar = invertido
            $invertido    = ( $i % 2 !== 0 );
            $clase_dir    = $invertido ? ' svc-block--reverse' : '';

            // Alternar fondo: cada 2 servicios poner fondo alterno
            $clase_fondo  = ( $i % 4 >= 2 ) ? ' svc-block--alt' : '';

            // Número del servicio formateado con cero (01, 02, ... 08)
            $numero = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
        ?>

        <!-- ── Servicio <?php echo (int) ( $i + 1 ); ?>: <?php echo esc_html( $svc['titulo'] ); ?> ── -->
        <article class="svc-block<?php echo esc_attr( $clase_dir . $clase_fondo ); ?> hp-animate"
                 id="<?php echo esc_attr( $svc['id'] ); ?>">

            <!-- Columna de texto -->
            <div class="svc-block__text">

                <!-- Número decorativo -->
                <span class="svc-block__number" aria-hidden="true">
                    <?php echo esc_html( $numero ); ?>
                </span>

                <h2 class="svc-block__title">
                    <?php echo esc_html( $svc['titulo'] ); ?>
                </h2>

                <p class="svc-block__description">
                    <?php echo esc_html( $svc['descripcion'] ); ?>
                </p>

                <!-- Objetivo destacado -->
                <div class="svc-block__objective">
                    <strong>Objetivo:</strong>
                    <?php echo esc_html( $svc['objetivo'] ); ?>
                </div>

                <!-- Link de contacto para este servicio -->
                <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"
                   class="btn-primary">
                    Solicitar información
                </a>
            </div>

            <!-- Columna de imagen -->
            <div class="svc-block__media">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/servicios/' . $svc['imagen'] ); ?>"
                     alt="<?php echo esc_attr( $svc['alt'] ); ?>"
                     class="svc-block__img"
                     width="540"
                     height="380"
                     loading="lazy">
            </div>

        </article>

        <?php endforeach; ?>

    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     4. CTA BANNER — Incluido automáticamente desde footer.php
     ════════════════════════════════════════════════════════════════ -->

<?php get_footer(); ?>
