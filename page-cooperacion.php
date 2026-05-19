<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — page-cooperacion.php (Página de Cooperación)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Template Name: Cooperación
 *
 * Secciones:
 *   1. Hero — Título + imagen
 *   2. Tipos de cooperación — 3 tarjetas (Convenios, Proyectos, Voluntariado)
 *   3. ¿Por qué cooperar? — Beneficios en 2 columnas
 *   4. Cómo cooperar — 4 pasos numerados
 *   5. CTA Banner — Desde footer.php
 *
 * @package HumanPeru
 */

get_header();
?>

<div class="hero-bar" aria-hidden="true"></div>

<!-- ════════════════════════════════════════════════════════════════
     1. HERO
     ════════════════════════════════════════════════════════════════ -->

<section class="page-hero">
    <div class="page-hero__container container">

        <div class="page-hero__text hp-animate">
            <span class="tag tag--orange">Alianzas estratégicas</span>
            <h1 class="page-hero__title">
                Cooperación <span class="highlight">institucional</span>
            </h1>
            <p class="page-hero__description">
                Trabajamos con instituciones, empresas y organizaciones que comparten
                nuestra visión de promover la salud mental en el Perú. Juntos podemos
                generar un mayor impacto.
            </p>
        </div>

        <div class="page-hero__image hp-animate hp-animate--right">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'hp-hero', [
                    'class'   => 'page-hero__img',
                    'alt'     => 'Cooperación institucional de Human Perú',
                    'loading' => 'eager',
                ] ); ?>
            <?php else : ?>
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/cooperacion-hero.jpg' ); ?>"
                     alt="Cooperación institucional de Human Perú"
                     class="page-hero__img"
                     width="560" height="380" loading="eager">
            <?php endif; ?>
        </div>

    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     2. TIPOS DE COOPERACIÓN — 3 tarjetas
     ════════════════════════════════════════════════════════════════ -->

<section class="section section--alt" id="tipos-cooperacion">
    <div class="container">

        <div class="section__header hp-animate">
            <h2>Formas de cooperación</h2>
            <p>Ofrecemos diferentes modalidades para trabajar juntos según las necesidades de cada organización.</p>
        </div>

        <div class="grid-3">

            <?php
            $tipos = [
                [
                    'icon'  => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
                    'color' => 'orange',
                    'title' => 'Convenios institucionales',
                    'text'  => 'Firmamos convenios marco y específicos con universidades, municipalidades, colegios profesionales y organizaciones de la sociedad civil para desarrollar programas conjuntos de salud mental.',
                ],
                [
                    'icon'  => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>',
                    'color' => 'blue',
                    'title' => 'Proyectos colaborativos',
                    'text'  => 'Diseñamos e implementamos proyectos de investigación, intervención y formación en salud mental con financiamiento compartido o de terceros.',
                ],
                [
                    'icon'  => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>',
                    'color' => 'yellow',
                    'title' => 'Voluntariado profesional',
                    'text'  => 'Profesionales de la salud mental pueden sumarse a nuestras actividades comunitarias, talleres y campañas de sensibilización como voluntarios especializados.',
                ],
            ];

            foreach ( $tipos as $i => $tipo ) :
                $delay = $i + 1;
            ?>
            <article class="identity-card hp-animate hp-animate--delay-<?php echo $delay; ?>">
                <div class="identity-card__icon identity-card__icon--<?php echo esc_attr( $tipo['color'] ); ?>" aria-hidden="true">
                    <?php echo $tipo['icon']; // phpcs:ignore ?>
                </div>
                <h3 class="identity-card__label"><?php echo esc_html( $tipo['title'] ); ?></h3>
                <p class="identity-card__text"><?php echo esc_html( $tipo['text'] ); ?></p>
            </article>
            <?php endforeach; ?>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     3. ¿POR QUÉ COOPERAR? — 2 columnas
     ════════════════════════════════════════════════════════════════ -->

<section class="section" id="por-que-cooperar">
    <div class="container">
        <div class="two-col">

            <div class="two-col__media hp-animate hp-animate--left">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/cooperacion-beneficios.jpg' ); ?>"
                     alt="Beneficios de cooperar con Human Perú"
                     class="two-col__img" width="560" height="380" loading="lazy">
            </div>

            <div class="two-col__text hp-animate hp-animate--right">
                <span class="tag tag--blue">Impacto compartido</span>
                <h2>¿Por qué cooperar con Human Perú?</h2>

                <div class="benefit-list">
                    <?php
                    $beneficios = [
                        [ 'Alcance comunitario',   'Llegamos a poblaciones vulnerables con programas adaptados a su realidad.' ],
                        [ 'Equipo especializado',   'Contamos con profesionales en psicología, educación y trabajo social.' ],
                        [ 'Metodología validada',   'Nuestros programas se basan en evidencia y buenas prácticas internacionales.' ],
                        [ 'Visibilidad y reputación', 'Tu organización se asocia con una causa de alto impacto social.' ],
                    ];

                    foreach ( $beneficios as $b ) :
                    ?>
                    <div class="benefit-item">
                        <span class="benefit-item__check" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <div>
                            <strong><?php echo esc_html( $b[0] ); ?></strong>
                            <p><?php echo esc_html( $b[1] ); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>" class="btn-primary" style="margin-top: 16px;">
                    Iniciar una alianza
                </a>
            </div>

        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     4. CÓMO COOPERAR — 4 pasos
     ════════════════════════════════════════════════════════════════ -->

<section class="section section--alt" id="como-cooperar">
    <div class="container">

        <div class="section__header hp-animate">
            <h2>¿Cómo iniciar una cooperación?</h2>
            <p>Un proceso sencillo en 4 pasos para comenzar a trabajar juntos.</p>
        </div>

        <div class="steps-grid">

            <?php
            $pasos = [
                [ 'Contáctanos',        'Escríbenos a servicios@humanperu.org.pe o completa nuestro formulario de contacto indicando tu interés.' ],
                [ 'Reunión diagnóstica', 'Agendamos una reunión virtual o presencial para conocer tus necesidades y objetivos.' ],
                [ 'Propuesta a medida',  'Diseñamos una propuesta técnica y económica adaptada a tu organización.' ],
                [ 'Ejecución y seguimiento', 'Implementamos el proyecto con indicadores de impacto y reportes periódicos.' ],
            ];

            foreach ( $pasos as $i => $paso ) :
                $num   = $i + 1;
                $delay = $i + 1;
            ?>
            <div class="step-card hp-animate hp-animate--delay-<?php echo $delay; ?>">
                <span class="step-card__number" aria-hidden="true"><?php echo $num; ?></span>
                <h3 class="step-card__title"><?php echo esc_html( $paso[0] ); ?></h3>
                <p class="step-card__text"><?php echo esc_html( $paso[1] ); ?></p>
            </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>


<?php get_footer(); ?>
