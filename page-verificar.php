<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — page-verificar.php (Verificar Diploma)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Template Name: Verificar Diploma
 *
 * Esta página es un WRAPPER para el plugin "human-verificador".
 * El formulario del verificador se carga con do_shortcode().
 * El plugin maneja su propio CSS, JS, AJAX y tabla de diplomas.
 *
 * Secciones:
 *   1. Hero — Título + instrucciones
 *   2. Formulario del verificador (shortcode del plugin)
 *   3. Preguntas frecuentes
 *   4. CTA Banner — Desde footer.php
 *
 * @package HumanPeru
 */

get_header();
?>

<div class="hero-bar" aria-hidden="true"></div>

<!-- ════════════════════════════════════════════════════════════════
     1. HERO — Encabezado
     ════════════════════════════════════════════════════════════════ -->

<section class="verificar-page">
    <div class="container">

        <div class="verificar-page__header hp-animate">
            <span class="tag tag--blue">Validación oficial</span>
            <h1>Verificar diploma</h1>
            <p>
                Comprueba la autenticidad de los certificados y diplomas
                emitidos por la Asociación Human Perú ingresando el código
                de serie impreso en tu documento.
            </p>
        </div>


        <!-- ════════════════════════════════════════════════════
             2. FORMULARIO DEL VERIFICADOR (plugin)
             ════════════════════════════════════════════════════
             El shortcode [verificador_diplomas] es registrado por
             el plugin human-verificador.php. Genera el formulario
             completo con su propio CSS y JavaScript.
             ════════════════════════════════════════════════════ -->

        <div class="verificar-page__form hp-animate">
            <?php echo do_shortcode( '[verificador_diplomas]' ); ?>
        </div>


        <!-- ════════════════════════════════════════════════════
             3. PREGUNTAS FRECUENTES
             ════════════════════════════════════════════════════ -->

        <div class="verificar-faq hp-animate">

            <h2 class="verificar-faq__title">Preguntas frecuentes</h2>

            <?php
            $faqs = [
                [
                    'q' => '¿Dónde encuentro el código de serie?',
                    'a' => 'El código de serie se encuentra impreso en la esquina inferior derecha de tu diploma o certificado. Tiene el formato XX-AAAA-MM-NNN (ejemplo: TG-2026-02-001).',
                ],
                [
                    'q' => '¿Qué hago si mi diploma no aparece en el sistema?',
                    'a' => 'Verifica que el código esté escrito correctamente, incluyendo los guiones. Si persiste el problema, contáctanos a mesadepartes@humanperu.org.pe con una foto de tu diploma.',
                ],
                [
                    'q' => '¿Los diplomas tienen vigencia?',
                    'a' => 'Los diplomas y certificados emitidos por Human Perú no tienen fecha de vencimiento, salvo que se indique expresamente en el documento.',
                ],
                [
                    'q' => '¿Puedo solicitar un duplicado?',
                    'a' => 'Sí. Envía tu solicitud a mesadepartes@humanperu.org.pe indicando tu nombre completo, número de documento de identidad y el nombre del curso o programa.',
                ],
            ];

            foreach ( $faqs as $faq ) :
            ?>
            <details class="verificar-faq__item">
                <summary class="verificar-faq__question">
                    <span><?php echo esc_html( $faq['q'] ); ?></span>
                    <svg class="verificar-faq__chevron" width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </summary>
                <div class="verificar-faq__answer">
                    <p><?php echo esc_html( $faq['a'] ); ?></p>
                </div>
            </details>
            <?php endforeach; ?>

        </div>

    </div>
</section>


<?php get_footer(); ?>
