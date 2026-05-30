<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — functions.php del Theme
 * ═══════════════════════════════════════════════════════════════════
 *
 * Este archivo centraliza toda la configuración del theme:
 *   1. Setup del theme (soporte de funcionalidades)
 *   2. Carga de estilos y scripts (Google Fonts, CSS, JS)
 *   3. Registro de menús y sidebars
 *   4. Creación de tablas custom (empleados + asistencia)
 *   5. AJAX handler para marcar asistencia
 *   6. Limpieza de WordPress (rendimiento)
 *   7. Funciones auxiliares reutilizables
 *
 * NOTA IMPORTANTE SOBRE EL VERIFICADOR DE DIPLOMAS:
 * ──────────────────────────────────────────────────
 * La verificación de diplomas se maneja con el plugin "human-verificador"
 * (human-verificador.php), que ya está activo en producción y gestiona:
 *   - La tabla wp_human_diplomas
 *   - El shortcode [verificador_diplomas]
 *   - El endpoint AJAX 'verificar_diploma'
 *   - Rate limiting y seguridad
 *
 * NO duplicar esa lógica aquí. El theme solo necesita incluir la
 * página page-verificar.php con the_content() o el shortcode.
 *
 * @package    HumanPeru
 * @version    1.0.0
 * @author     Human Perú — humanperu.org.pe
 */

// ─────────────────────────────────────────────────────────────────
// SEGURIDAD: bloquear acceso directo al archivo
// ─────────────────────────────────────────────────────────────────
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// ═════════════════════════════════════════════════════════════════
// 1. SETUP DEL THEME
// ═════════════════════════════════════════════════════════════════

function humanperu_setup() {

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    add_image_size( 'hp-hero', 1200, 600, true );
    add_image_size( 'hp-card', 400, 260, true );
    add_image_size( 'hp-team', 300, 300, true );

    add_theme_support( 'html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ] );

    register_nav_menus( [
        'primary' => 'Menú Principal',
        'footer'  => 'Menú del Footer',
    ] );

    add_theme_support( 'editor-styles' );
    remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', 'humanperu_setup' );


// ═════════════════════════════════════════════════════════════════
// 2. ENQUEUE DE ESTILOS Y SCRIPTS
// ═════════════════════════════════════════════════════════════════

function humanperu_enqueue_assets() {

    wp_enqueue_style(
        'hp-google-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Nunito:wght@400;500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'hp-theme-style',
        get_stylesheet_uri(),
        [ 'hp-google-fonts' ],
        filemtime( get_stylesheet_directory() . '/style.css' )
    );

    wp_enqueue_script(
        'hp-main-js',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        filemtime( get_stylesheet_directory() . '/assets/js/main.js' ),
        [ 'in_footer' => true, 'strategy' => 'defer' ]
    );

    // AJAX de asistencia — solo en /asistencia/
    if ( is_page( 'asistencia' ) ) {

        wp_enqueue_script(
            'hp-attendance-js',
            get_template_directory_uri() . '/assets/js/attendance.js',
            [],
            filemtime( get_stylesheet_directory() . '/assets/js/attendance.js' ),
            [ 'in_footer' => true, 'strategy' => 'defer' ]
        );

        wp_localize_script( 'hp-attendance-js', 'hp_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'hp_asistencia_nonce' ),
        ] );
    }
}
add_action( 'wp_enqueue_scripts', 'humanperu_enqueue_assets' );


// ═════════════════════════════════════════════════════════════════
// 3. ENQUEUE — Formulario de contacto con EmailJS (solo en /contacto/)
// ═════════════════════════════════════════════════════════════════

function humanperu_enqueue_contacto() {

    if ( ! is_page( 'contacto' ) ) {
        return;
    }

    // Librería EmailJS desde CDN
    wp_enqueue_script(
        'emailjs-sdk',
        'https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js',
        [],
        null,
        [ 'in_footer' => true ]
    );

    // Nuestro contact.js, que depende de EmailJS
    wp_enqueue_script(
        'hp-contact-js',
        get_template_directory_uri() . '/assets/js/contact.js',
        [ 'emailjs-sdk' ],
        filemtime( get_stylesheet_directory() . '/assets/js/contact.js' ),
        [ 'in_footer' => true, 'strategy' => 'defer' ]
    );
}
add_action( 'wp_enqueue_scripts', 'humanperu_enqueue_contacto' );


// ═════════════════════════════════════════════════════════════════
// 4. REGISTRO DE SIDEBAR (WIDGETS)
// ═════════════════════════════════════════════════════════════════

function humanperu_widgets_init() {

    register_sidebar( [
        'name'          => 'Sidebar del Blog',
        'id'            => 'blog-sidebar',
        'description'   => 'Widgets que aparecen en la barra lateral del blog (archive.php y single.php).',
        'before_widget' => '<div id="%1$s" class="hp-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="hp-widget-title">',
        'after_title'   => '</h4>',
    ] );
}
add_action( 'widgets_init', 'humanperu_widgets_init' );


// ═════════════════════════════════════════════════════════════════
// 5. CREACIÓN DE TABLAS CUSTOM EN LA BASE DE DATOS
// ═════════════════════════════════════════════════════════════════

function humanperu_crear_tablas() {

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $tabla_empleados = $wpdb->prefix . 'hp_empleados';
    $sql_empleados = "CREATE TABLE {$tabla_empleados} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        nombre VARCHAR(150) NOT NULL,
        cargo VARCHAR(100) NOT NULL DEFAULT '',
        password_hash VARCHAR(255) NOT NULL,
        activo TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_activo (activo)
    ) {$charset_collate};";

    dbDelta( $sql_empleados );

    $tabla_asistencia = $wpdb->prefix . 'hp_asistencia';
    $sql_asistencia = "CREATE TABLE {$tabla_asistencia} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        empleado_id BIGINT UNSIGNED NOT NULL,
        tipo ENUM('entrada','salida') NOT NULL,
        fecha DATE NOT NULL,
        hora TIME NOT NULL,
        ip VARCHAR(45) NOT NULL DEFAULT '',
        user_agent VARCHAR(255) NOT NULL DEFAULT '',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_empleado_fecha (empleado_id, fecha),
        KEY idx_fecha (fecha)
    ) {$charset_collate};";

    dbDelta( $sql_asistencia );

    update_option( 'hp_db_version', '1.0.0' );
}
add_action( 'after_switch_theme', 'humanperu_crear_tablas' );


// ═════════════════════════════════════════════════════════════════
// 6. AJAX HANDLER — MARCAR ASISTENCIA
// ═════════════════════════════════════════════════════════════════

add_action( 'wp_ajax_nopriv_marcar_asistencia', 'humanperu_ajax_marcar_asistencia' );
add_action( 'wp_ajax_marcar_asistencia',        'humanperu_ajax_marcar_asistencia' );

function humanperu_ajax_marcar_asistencia() {

    if ( ! check_ajax_referer( 'hp_asistencia_nonce', 'security', false ) ) {
        wp_send_json_error( [
            'message' => 'Error de seguridad. Recarga la página e intenta de nuevo.',
        ], 403 );
    }

    $empleado_id = isset( $_POST['empleado_id'] ) ? absint( $_POST['empleado_id'] ) : 0;
    $password    = isset( $_POST['password'] )    ? sanitize_text_field( $_POST['password'] ) : '';
    $tipo        = isset( $_POST['tipo'] )         ? sanitize_text_field( $_POST['tipo'] ) : '';

    if ( $empleado_id === 0 || empty( $password ) || empty( $tipo ) ) {
        wp_send_json_error( [ 'message' => 'Todos los campos son obligatorios.' ] );
    }

    if ( ! in_array( $tipo, [ 'entrada', 'salida' ], true ) ) {
        wp_send_json_error( [ 'message' => 'Tipo de marcación no válido.' ] );
    }

    global $wpdb;
    $tabla_empleados = $wpdb->prefix . 'hp_empleados';

    $empleado = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT id, nombre, cargo, password_hash FROM {$tabla_empleados} WHERE id = %d AND activo = 1 LIMIT 1",
            $empleado_id
        )
    );

    if ( is_null( $empleado ) ) {
        wp_send_json_error( [ 'message' => 'Empleado no encontrado o inactivo.' ] );
    }

    if ( ! password_verify( $password, $empleado->password_hash ) ) {
        wp_send_json_error( [ 'message' => 'Contraseña incorrecta.' ] );
    }

    $tabla_asistencia = $wpdb->prefix . 'hp_asistencia';
    $hoy = current_time( 'Y-m-d' );

    $ya_marco = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$tabla_asistencia} WHERE empleado_id = %d AND tipo = %s AND fecha = %s",
            $empleado_id,
            $tipo,
            $hoy
        )
    );

    if ( (int) $ya_marco > 0 ) {
        wp_send_json_error( [
            'message' => sprintf(
                'Ya registraste tu %s hoy. Solo puedes marcar una vez por tipo al día.',
                $tipo
            ),
        ] );
    }

    $hora_actual = current_time( 'H:i:s' );

    $insertado = $wpdb->insert(
        $tabla_asistencia,
        [
            'empleado_id' => $empleado_id,
            'tipo'        => $tipo,
            'fecha'       => $hoy,
            'hora'        => $hora_actual,
            'ip'          => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ),
            'user_agent'  => sanitize_text_field( substr( $_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255 ) ),
        ],
        [ '%d', '%s', '%s', '%s', '%s', '%s' ]
    );

    if ( false === $insertado ) {
        wp_send_json_error( [ 'message' => 'Error al registrar la asistencia. Intenta nuevamente.' ] );
    }

    wp_send_json_success( [
        'message'  => sprintf(
            '✅ %s de %s registrada correctamente a las %s.',
            ucfirst( $tipo ),
            esc_html( $empleado->nombre ),
            date_i18n( 'g:i A', strtotime( $hora_actual ) )
        ),
        'empleado' => esc_html( $empleado->nombre ),
        'tipo'     => $tipo,
        'hora'     => date_i18n( 'g:i A', strtotime( $hora_actual ) ),
        'fecha'    => date_i18n( 'j \d\e F \d\e Y', strtotime( $hoy ) ),
    ] );

    wp_die();
}


// ═════════════════════════════════════════════════════════════════
// 7. LIMPIEZA DE WORDPRESS (RENDIMIENTO)
// ═════════════════════════════════════════════════════════════════

function humanperu_limpiar_head() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
}
add_action( 'after_setup_theme', 'humanperu_limpiar_head' );

function humanperu_desactivar_embeds() {
    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'humanperu_desactivar_embeds' );

add_filter( 'emoji_svg_url', '__return_false' );
add_filter( 'xmlrpc_enabled', '__return_false' );

function humanperu_remover_version_assets( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src',  'humanperu_remover_version_assets' );
add_filter( 'script_loader_src', 'humanperu_remover_version_assets' );


// ═════════════════════════════════════════════════════════════════
// 8. FUNCIONES AUXILIARES DEL THEME
// ═════════════════════════════════════════════════════════════════

function humanperu_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'humanperu_excerpt_length' );

function humanperu_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'humanperu_excerpt_more' );

function humanperu_body_classes( $classes ) {
    if ( is_page() ) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }
    if ( is_front_page() ) {
        $classes[] = 'hp-front-page';
    }
    return $classes;
}
add_filter( 'body_class', 'humanperu_body_classes' );

function hp_asset( $path ) {
    return esc_url( get_template_directory_uri() . '/assets/' . ltrim( $path, '/' ) );
}

function hp_icon( $name ) {
    $file = get_template_directory() . '/assets/icons/' . sanitize_file_name( $name ) . '.svg';
    if ( file_exists( $file ) ) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo file_get_contents( $file );
    }
}


// ═════════════════════════════════════════════════════════════════
// 9. UTILIDAD — CREAR EMPLEADOS (WP-CLI)
// ═════════════════════════════════════════════════════════════════
// Uso: wp eval "humanperu_crear_empleado('Nombre', 'Cargo', 'clave123');"

function humanperu_crear_empleado( $nombre, $cargo, $password ) {

    global $wpdb;
    $tabla = $wpdb->prefix . 'hp_empleados';

    $insertado = $wpdb->insert(
        $tabla,
        [
            'nombre'        => sanitize_text_field( $nombre ),
            'cargo'         => sanitize_text_field( $cargo ),
            'password_hash' => password_hash( $password, PASSWORD_DEFAULT ),
            'activo'        => 1,
        ],
        [ '%s', '%s', '%s', '%d' ]
    );

    if ( false === $insertado ) {
        return false;
    }

    return $wpdb->insert_id;
}