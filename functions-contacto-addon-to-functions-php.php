<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — Bloque para agregar a functions.php
 * ═══════════════════════════════════════════════════════════════════
 *
 * INSTRUCCIONES: Copiar TODO este contenido al final de functions.php,
 * justo ANTES de la línea "// FIN DE functions.php".
 *
 * Contenido:
 *   1. Enqueue condicional de contact.js en /contacto/
 *   2. AJAX handler 'hp_enviar_contacto' con wp_mail()
 */


// ═════════════════════════════════════════════════════════════════
// ENQUEUE — Script del formulario de contacto (solo en /contacto/)
// ═════════════════════════════════════════════════════════════════

function humanperu_enqueue_contacto() {

    // Solo cargar en la página de contacto
    if ( ! is_page( 'contacto' ) ) {
        return;
    }

    wp_enqueue_script(
        'hp-contact-js',
        get_template_directory_uri() . '/assets/js/contact.js',
        [],
        filemtime( get_stylesheet_directory() . '/assets/js/contact.js' ),
        [ 'in_footer' => true, 'strategy' => 'defer' ]
    );

    // Pasar la URL de AJAX al script
    wp_localize_script( 'hp-contact-js', 'hp_contacto', [
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'humanperu_enqueue_contacto' );


// ═════════════════════════════════════════════════════════════════
// AJAX HANDLER — Enviar formulario de contacto con wp_mail()
// ═════════════════════════════════════════════════════════════════
//
// Flujo de seguridad:
//   1. Validar nonce (CSRF)
//   2. Verificar honeypot (anti-spam)
//   3. Rate limiting (máx 5 envíos por IP por hora)
//   4. Sanitizar todos los campos
//   5. Validar campos requeridos y formato de email
//   6. Enviar email con wp_mail()
//   7. Responder con JSON
//
// ═════════════════════════════════════════════════════════════════

add_action( 'wp_ajax_nopriv_hp_enviar_contacto', 'humanperu_ajax_enviar_contacto' );
add_action( 'wp_ajax_hp_enviar_contacto',        'humanperu_ajax_enviar_contacto' );

function humanperu_ajax_enviar_contacto() {

    // ── PASO 1: Validar nonce ────────────────────────────────────
    if ( ! check_ajax_referer( 'hp_contacto_nonce', 'hp_contacto_security', false ) ) {
        wp_send_json_error( [
            'message' => 'Error de seguridad. Recarga la página e intenta de nuevo.',
        ], 403 );
    }

    // ── PASO 2: Verificar honeypot ───────────────────────────────
    // Si el campo oculto "website" tiene contenido, es un bot.
    $honeypot = isset( $_POST['website'] ) ? sanitize_text_field( $_POST['website'] ) : '';
    if ( ! empty( $honeypot ) ) {
        // Responder con "éxito" falso para no alertar al bot
        wp_send_json_success( [
            'message' => 'Mensaje enviado correctamente. Te responderemos pronto.',
        ] );
    }

    // ── PASO 3: Rate limiting ────────────────────────────────────
    // Máximo 5 envíos por IP por hora (evitar spam y abuso)
    $ip             = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' );
    $clave_rate     = 'hp_contacto_rl_' . md5( $ip );
    $envios_por_ip  = (int) get_transient( $clave_rate );

    if ( $envios_por_ip >= 5 ) {
        wp_send_json_error( [
            'message' => 'Has enviado demasiados mensajes. Espera un momento e intenta de nuevo, o contáctanos directamente por WhatsApp.',
        ], 429 );
    }

    // ── PASO 4: Sanitizar todos los campos ───────────────────────
    $nombre   = sanitize_text_field( $_POST['nombre'] ?? '' );
    $email    = sanitize_email( $_POST['email'] ?? '' );
    $telefono = sanitize_text_field( $_POST['telefono'] ?? '' );
    $asunto   = sanitize_text_field( $_POST['asunto'] ?? '' );
    $mensaje  = sanitize_textarea_field( $_POST['mensaje'] ?? '' );

    // ── PASO 5: Validar campos requeridos ────────────────────────
    if ( empty( $nombre ) ) {
        wp_send_json_error( [ 'message' => 'El nombre es obligatorio.' ] );
    }

    if ( empty( $email ) || ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Ingresa un correo electrónico válido.' ] );
    }

    if ( empty( $asunto ) ) {
        wp_send_json_error( [ 'message' => 'Selecciona un asunto.' ] );
    }

    // Validar que el asunto sea uno de los valores permitidos
    $asuntos_validos = [
        'Consulta sobre servicios',
        'Capacitación empresarial',
        'Escuela de padres',
        'Cooperación institucional',
        'Verificación de diploma',
        'Otro',
    ];

    if ( ! in_array( $asunto, $asuntos_validos, true ) ) {
        wp_send_json_error( [ 'message' => 'El asunto seleccionado no es válido.' ] );
    }

    if ( empty( $mensaje ) ) {
        wp_send_json_error( [ 'message' => 'El mensaje es obligatorio.' ] );
    }

    if ( mb_strlen( $mensaje ) < 10 ) {
        wp_send_json_error( [ 'message' => 'El mensaje debe tener al menos 10 caracteres.' ] );
    }

    // ── PASO 6: Construir y enviar el email ──────────────────────
    // Dirección de destino (mesa de partes de Human Perú)
    $destinatario = 'mesadepartes@humanperu.org.pe';

    // Asunto del email
    $email_asunto = sprintf(
        '[Web Human Perú] %s — %s',
        $asunto,
        $nombre
    );

    // Cuerpo del email en texto plano
    $email_cuerpo  = "Nuevo mensaje desde el formulario de contacto de humanperu.org.pe\n";
    $email_cuerpo .= "═══════════════════════════════════════════\n\n";
    $email_cuerpo .= "Nombre:    " . $nombre . "\n";
    $email_cuerpo .= "Email:     " . $email . "\n";
    $email_cuerpo .= "Teléfono:  " . ( $telefono ?: 'No proporcionado' ) . "\n";
    $email_cuerpo .= "Asunto:    " . $asunto . "\n\n";
    $email_cuerpo .= "Mensaje:\n";
    $email_cuerpo .= "───────────────────────────────────────────\n";
    $email_cuerpo .= $mensaje . "\n";
    $email_cuerpo .= "───────────────────────────────────────────\n\n";
    $email_cuerpo .= "IP: " . $ip . "\n";
    $email_cuerpo .= "Fecha: " . date_i18n( 'j \d\e F \d\e Y, g:i A' ) . "\n";

    // Headers del email: responder al remitente
    $headers = [
        'From: Human Perú Web <noreply@humanperu.org.pe>',
        'Reply-To: ' . $nombre . ' <' . $email . '>',
        'Content-Type: text/plain; charset=UTF-8',
    ];

    // Enviar con wp_mail()
    // wp_mail() usa la configuración SMTP del servidor o un plugin SMTP.
    $enviado = wp_mail( $destinatario, $email_asunto, $email_cuerpo, $headers );

    if ( ! $enviado ) {
        wp_send_json_error( [
            'message' => 'No se pudo enviar el mensaje. Por favor, contáctanos directamente a mesadepartes@humanperu.org.pe o por WhatsApp al +51 923 322 521.',
        ] );
    }

    // ── PASO 7: Incrementar rate limit y responder ───────────────
    set_transient( $clave_rate, $envios_por_ip + 1, HOUR_IN_SECONDS );

    wp_send_json_success( [
        'message' => '¡Mensaje enviado correctamente! Nuestro equipo te responderá en un plazo máximo de 24 horas.',
    ] );

    wp_die();
}
