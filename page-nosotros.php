<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — page-nosotros.php (Página Nosotros)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Template Name: Nosotros
 *
 * Secciones:
 *   1. Hero — Título + imagen
 *   2. Visión, Misión, Historia — 3 tarjetas con ícono circular
 *   3. Equipo directivo — 4 miembros en grilla 2×2
 *   4. CTA Banner — Incluido desde footer.php
 *
 * @package HumanPeru
 */

get_header();
?>


<!-- ════════════════════════════════════════════════════════════════
     1. HERO
     ════════════════════════════════════════════════════════════════ -->

<div class="hero-bar" aria-hidden="true"></div>

<section class="page-hero">
    <div class="page-hero__container container">

        <div class="page-hero__text hp-animate">
            <span class="tag tag--orange">Conócenos</span>
            <h1 class="page-hero__title">
                Nacemos con la misión de promover la <span class="highlight">salud mental</span>
            </h1>
            <p class="page-hero__description">
                Somos una organización peruana conformada por profesionales comprometidos
                con el bienestar emocional de las personas, las familias y las comunidades.
            </p>
        </div>

        <div class="page-hero__image hp-animate hp-animate--right">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'hp-hero', [
                    'class'   => 'page-hero__img',
                    'alt'     => 'Equipo de Human Perú',
                    'loading' => 'eager',
                ] ); ?>
            <?php else : ?>
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/nosotros-hero.jpg' ); ?>"
                     alt="Equipo de Human Perú trabajando por la salud mental"
                     class="page-hero__img"
                     width="560" height="380" loading="eager">
            <?php endif; ?>
        </div>

    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     2. VISIÓN, MISIÓN, HISTORIA — 3 tarjetas
     ════════════════════════════════════════════════════════════════ -->

<section class="section section--alt" id="identidad">
    <div class="container">

        <div class="section__header hp-animate">
            <h2>Nuestra identidad</h2>
            <p>Tres pilares que guían cada acción de Human Perú.</p>
        </div>

        <div class="identity-grid">

            <?php
            $pilares = [
                [
                    'color' => 'blue',
                    'svg'   => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
                    'label' => 'Visión',
                    'text'  => 'Ser la principal referencia en Perú para la promoción, educación y apoyo en salud mental, fomentando una sociedad inclusiva y consciente donde cada individuo pueda alcanzar su pleno potencial y bienestar psicológico.',
                ],
                [
                    'color' => 'orange',
                    'svg'   => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>',
                    'label' => 'Misión',
                    'text'  => 'Promover la salud mental en Perú a través de la educación, la sensibilización y el apoyo comunitario, trabajando con profesionales, instituciones y la comunidad.',
                ],
                [
                    'color' => 'yellow',
                    'svg'   => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                    'label' => 'Nuestra historia',
                    'text'  => 'Nacimos en 2024, gracias a la visión de un grupo de profesionales comprometidos con la salud mental en la familia, los centros laborales y en espacios comunitarios. Desde entonces trabajamos para que cada peruano tenga acceso a herramientas que mejoren su bienestar emocional.',
                ],
            ];

            foreach ( $pilares as $i => $pilar ) :
                $delay = $i + 1;
            ?>

            <article class="identity-card hp-animate hp-animate--delay-<?php echo $delay; ?>">

                <!-- Ícono circular de color -->
                <div class="identity-card__icon identity-card__icon--<?php echo esc_attr( $pilar['color'] ); ?>"
                     aria-hidden="true">
                    <?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo $pilar['svg'];
                    ?>
                </div>

                <h3 class="identity-card__label">
                    <?php echo esc_html( $pilar['label'] ); ?>
                </h3>

                <p class="identity-card__text">
                    <?php echo esc_html( $pilar['text'] ); ?>
                </p>

            </article>

            <?php endforeach; ?>

        </div>

    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     3. EQUIPO DIRECTIVO — 4 miembros
     ════════════════════════════════════════════════════════════════ -->

<section class="section" id="equipo">
    <div class="container">

        <div class="section__header hp-animate">
            <span class="tag tag--blue">Quiénes somos</span>
            <h2>Equipo directivo</h2>
            <p>Profesionales comprometidos con la salud mental en el Perú.</p>
        </div>

        <div class="equipo-grid">

            <?php
            $equipo = [
                [
                    'nombre' => 'Rolando Salazar Benítez',
                    'cargo'  => 'Presidente',
                    'foto'   => 'equipo-rolando.jpg',
                ],
                [
                    'nombre' => 'Isabel Orbegoso Delgado',
                    'cargo'  => 'Directora Ejecutiva',
                    'foto'   => 'equipo-isabel.jpg',
                ],
                [
                    'nombre' => 'Óscar Román Quispe',
                    'cargo'  => 'Gestor de Tecnología e Información',
                    'foto'   => 'equipo-oscar.jpg',
                ],
                [
                    'nombre' => 'Norman Cortez Jiménez',
                    'cargo'  => 'Contador',
                    'foto'   => 'equipo-norman.jpg',
                ],
            ];

            foreach ( $equipo as $i => $miembro ) :
                $delay = $i + 1;
                $foto_url = get_template_directory_uri() . '/assets/images/equipo/' . $miembro['foto'];
                // Iniciales para el avatar placeholder
                $iniciales = '';
                $palabras = explode( ' ', $miembro['nombre'] );
                if ( isset( $palabras[0] ) ) $iniciales .= mb_substr( $palabras[0], 0, 1 );
                if ( isset( $palabras[1] ) ) $iniciales .= mb_substr( $palabras[1], 0, 1 );
            ?>

            <article class="equipo-card card hp-animate hp-animate--delay-<?php echo $delay; ?>">

                <!-- Foto circular del miembro -->
                <div class="equipo-card__foto-wrapper">
                    <img src="<?php echo esc_url( $foto_url ); ?>"
                         alt="<?php echo esc_attr( $miembro['nombre'] . ' — ' . $miembro['cargo'] ); ?>"
                         class="equipo-card__foto"
                         width="300"
                         height="300"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    <!-- Avatar placeholder con iniciales (se muestra si la foto falla) -->
                    <div class="equipo-card__avatar" style="display:none;" aria-hidden="true">
                        <span><?php echo esc_html( $iniciales ); ?></span>
                    </div>
                </div>

                <h3 class="equipo-card__nombre">
                    <?php echo esc_html( $miembro['nombre'] ); ?>
                </h3>

                <p class="equipo-card__cargo">
                    <?php echo esc_html( $miembro['cargo'] ); ?>
                </p>

            </article>

            <?php endforeach; ?>

        </div>

    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════
     4. CTA BANNER — Incluido desde footer.php
     ════════════════════════════════════════════════════════════════ -->

<?php get_footer(); ?>
