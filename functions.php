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
// Se ejecuta al cargar el theme. Registra el soporte de
// funcionalidades nativas de WordPress que usaremos.
// ═════════════════════════════════════════════════════════════════

function humanperu_setup() {

    // Permite que WordPress gestione la etiqueta <title> automáticamente.
    // Así Yoast SEO puede controlar el título de cada página sin conflictos.
    add_theme_support( 'title-tag' );

    // Habilita las imágenes destacadas (Featured Image) en posts y pages.
    // Las usaremos como hero en posts del blog y en secciones de páginas.
    add_theme_support( 'post-thumbnails' );

    // Tamaños de imagen personalizados para el theme
    // WordPress genera estas variantes al subir una imagen
    add_image_size( 'hp-hero', 1200, 600, true );       // Heros de secciones
    add_image_size( 'hp-card', 400, 260, true );         // Tarjetas del blog y servicios
    add_image_size( 'hp-team', 300, 300, true );         // Fotos del equipo (cuadradas)

    // Usa HTML5 semántico en elementos generados por WordPress.
    // Esto evita que WP inserte tablas o divs anticuados en estos componentes.
    add_theme_support( 'html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ] );

    // Registrar las ubicaciones de menú del theme.
    // 'primary' es el menú del header (Inicio, Servicios, Cooperación, etc.)
    register_nav_menus( [
        'primary' => 'Menú Principal',
        'footer'  => 'Menú del Footer',
    ] );

    // Desactivar el editor clásico para que Gutenberg sea el predeterminado.
    // Los posts del blog se escriben con Gutenberg; las páginas usan templates PHP.
    add_theme_support( 'editor-styles' );

    // Desactivar los patrones remotos del editor de bloques (rendimiento)
    remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', 'humanperu_setup' );


// ═════════════════════════════════════════════════════════════════
// 2. ENQUEUE DE ESTILOS Y SCRIPTS
// ═════════════════════════════════════════════════════════════════
// Carga las fuentes de Google, el CSS del theme y el JS principal.
// Usa wp_enqueue para que WordPress controle el orden y las
// dependencias correctamente.
// ═════════════════════════════════════════════════════════════════

function humanperu_enqueue_assets() {

    // ── Google Fonts: Poppins + Nunito ────────────────────────────
    // display=swap evita el FOIT (Flash of Invisible Text):
    // el navegador muestra una fuente fallback mientras carga la web font.
    wp_enqueue_style(
        'hp-google-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Nunito:wght@400;500;600;700&display=swap',
        [],
        null // Sin versión → evita ?ver= en la URL de Google Fonts
    );

    // ── CSS principal del theme ──────────────────────────────────
    // filemtime() usa la fecha de modificación del archivo como versión.
    // Cada vez que editas style.css, el navegador descarga la nueva versión
    // automáticamente (cache busting sin tener que cambiar un número manual).
    wp_enqueue_style(
        'hp-theme-style',
        get_stylesheet_uri(),
        [ 'hp-google-fonts' ], // Depende de Google Fonts (se carga después)
        filemtime( get_stylesheet_directory() . '/style.css' )
    );

    // ── JavaScript principal del theme ───────────────────────────
    // Controla: menú hamburguesa, scroll del header, Intersection Observer
    // para animaciones, y cualquier interactividad del frontend.
    //
    // 'in_footer' => true → carga al final del <body> (mejor rendimiento)
    // Sin dependencias de jQuery → vanilla JS puro
    wp_enqueue_script(
        'hp-main-js',
        get_template_directory_uri() . '/assets/js/main.js',
        [],      // Sin dependencias (NO jQuery)
        filemtime( get_stylesheet_directory() . '/assets/js/main.js' ),
        [ 'in_footer' => true, 'strategy' => 'defer' ]
    );

    // ── Variables PHP → JS para AJAX de asistencia ───────────────
    // Solo se cargan en la página de asistencia para no sobrecargar
    // el resto del sitio con datos que no necesitan.
    if ( is_page( 'asistencia' ) ) {
        wp_localize_script( 'hp-main-js', 'hp_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'hp_asistencia_nonce' ),
        ] );
    }
}
add_action( 'wp_enqueue_scripts', 'humanperu_enqueue_assets' );


// ═════════════════════════════════════════════════════════════════
// 3. REGISTRO DE SIDEBAR (WIDGETS)
// ═════════════════════════════════════════════════════════════════
// Sidebar para la página de archivo del blog. Permite al
// practicante de marketing agregar widgets desde wp-admin
// (Apariencia → Widgets): buscador, categorías, posts recientes.
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
// 4. CREACIÓN DE TABLAS CUSTOM EN LA BASE DE DATOS
// ═════════════════════════════════════════════════════════════════
//
// Se ejecuta SOLO al activar el theme (after_switch_theme).
// dbDelta() crea las tablas si no existen o las actualiza si
// la estructura cambió (agrega columnas nuevas, por ejemplo).
//
// IMPORTANTE:
// - La tabla de diplomas (wp_human_diplomas) la gestiona el plugin
//   "human-verificador". NO la creamos aquí para evitar conflictos.
// - Aquí solo creamos las tablas de empleados y asistencia.
//
// ═════════════════════════════════════════════════════════════════

function humanperu_crear_tablas() {

    global $wpdb;

    // charset y collation de la base de datos de WordPress
    // (normalmente utf8mb4_unicode_ci en instalaciones modernas)
    $charset_collate = $wpdb->get_charset_collate();

    // Necesario para usar dbDelta()
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // ── Tabla: wp_hp_empleados ────────────────────────────────────
    // Almacena los datos de los empleados que marcan asistencia.
    // password_hash guarda el hash bcrypt generado con password_hash()
    // de PHP (nunca se almacena la contraseña en texto plano).
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

    // ── Tabla: wp_hp_asistencia ───────────────────────────────────
    // Registra cada marcación de asistencia (entrada o salida).
    // Guarda IP y user_agent para auditoría de seguridad.
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

    // Guardar la versión de la estructura de tablas.
    // Útil para migraciones futuras: si cambia la estructura,
    // comparar esta versión y ejecutar ALTER TABLE si es menor.
    update_option( 'hp_db_version', '1.0.0' );
}
add_action( 'after_switch_theme', 'humanperu_crear_tablas' );


// ═════════════════════════════════════════════════════════════════
// 5. AJAX HANDLER — MARCAR ASISTENCIA
// ═════════════════════════════════════════════════════════════════
//
// Endpoint: wp_ajax_nopriv_marcar_asistencia (visitantes)
//           wp_ajax_marcar_asistencia        (logueados)
//
// Flujo:
//   1. Validar nonce
//   2. Sanitizar inputs (empleado_id, password, tipo)
//   3. Buscar empleado activo en BD
//   4. Verificar contraseña con password_verify()
//   5. Verificar que no haya marcado el mismo tipo hoy
//   6. Insertar registro de asistencia
//   7. Devolver respuesta JSON
//
// ═════════════════════════════════════════════════════════════════

add_action( 'wp_ajax_nopriv_marcar_asistencia', 'humanperu_ajax_marcar_asistencia' );
add_action( 'wp_ajax_marcar_asistencia',        'humanperu_ajax_marcar_asistencia' );

function humanperu_ajax_marcar_asistencia() {

    // ── PASO 1: Validar nonce ────────────────────────────────────
    if ( ! check_ajax_referer( 'hp_asistencia_nonce', 'security', false ) ) {
        wp_send_json_error( [
            'message' => 'Error de seguridad. Recarga la página e intenta de nuevo.',
        ], 403 );
    }

    // ── PASO 2: Sanitizar inputs ─────────────────────────────────
    $empleado_id = isset( $_POST['empleado_id'] )
        ? absint( $_POST['empleado_id'] )
        : 0;

    $password = isset( $_POST['password'] )
        ? sanitize_text_field( $_POST['password'] )
        : '';

    $tipo = isset( $_POST['tipo'] )
        ? sanitize_text_field( $_POST['tipo'] )
        : '';

    // Validar que llegaron todos los campos requeridos
    if ( $empleado_id === 0 || empty( $password ) || empty( $tipo ) ) {
        wp_send_json_error( [
            'message' => 'Todos los campos son obligatorios.',
        ] );
    }

    // Validar que el tipo sea 'entrada' o 'salida'
    if ( ! in_array( $tipo, [ 'entrada', 'salida' ], true ) ) {
        wp_send_json_error( [
            'message' => 'Tipo de marcación no válido.',
        ] );
    }

    // ── PASO 3: Buscar empleado activo en la BD ──────────────────
    global $wpdb;
    $tabla_empleados = $wpdb->prefix . 'hp_empleados';

    $empleado = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT id, nombre, cargo, password_hash FROM {$tabla_empleados} WHERE id = %d AND activo = 1 LIMIT 1",
            $empleado_id
        )
    );

    if ( is_null( $empleado ) ) {
        wp_send_json_error( [
            'message' => 'Empleado no encontrado o inactivo.',
        ] );
    }

    // ── PASO 4: Verificar contraseña ─────────────────────────────
    // password_verify() compara la contraseña ingresada contra el
    // hash bcrypt almacenado en la BD. NUNCA comparar en texto plano.
    if ( ! password_verify( $password, $empleado->password_hash ) ) {
        wp_send_json_error( [
            'message' => 'Contraseña incorrecta.',
        ] );
    }

    // ── PASO 5: Verificar duplicado del mismo tipo hoy ───────────
    // Evitar que un empleado marque "entrada" dos veces el mismo día.
    $tabla_asistencia = $wpdb->prefix . 'hp_asistencia';
    $hoy = current_time( 'Y-m-d' ); // Fecha según zona horaria de WordPress

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

    // ── PASO 6: Insertar registro de asistencia ──────────────────
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
        [ '%d', '%s', '%s', '%s', '%s', '%s' ] // Tipos de dato para prepare implícito
    );

    if ( false === $insertado ) {
        wp_send_json_error( [
            'message' => 'Error al registrar la asistencia. Intenta nuevamente.',
        ] );
    }

    // ── PASO 7: Respuesta exitosa ────────────────────────────────
    wp_send_json_success( [
        'message' => sprintf(
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
// 6. LIMPIEZA DE WORDPRESS (RENDIMIENTO)
// ═════════════════════════════════════════════════════════════════
// Elimina scripts y metadatos que WordPress carga por defecto
// pero que no necesitamos. Esto reduce el peso de cada página.
// ═════════════════════════════════════════════════════════════════

function humanperu_limpiar_head() {

    // Remover el script de emojis de WordPress (carga ~15KB innecesarios).
    // Los emojis funcionan de forma nativa en todos los navegadores modernos.
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );

    // Remover enlaces RSS de feeds (no usamos feeds RSS)
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );

    // Remover el enlace RSD (Really Simple Discovery) — usado por
    // clientes XML-RPC que no necesitamos
    remove_action( 'wp_head', 'rsd_link' );

    // Remover el enlace de Windows Live Writer (obsoleto)
    remove_action( 'wp_head', 'wlwmanifest_link' );

    // Remover el meta tag del generador de WordPress
    // (oculta la versión de WP por seguridad)
    remove_action( 'wp_head', 'wp_generator' );

    // Remover enlaces shortlink (redundantes con Yoast SEO activo)
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );

    // Remover el enlace de oEmbed discovery
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

    // Remover REST API link del head (sigue funcionando, solo no se anuncia)
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
}
add_action( 'after_setup_theme', 'humanperu_limpiar_head' );

/**
 * Desencolar wp-embed.js si no usamos embeds de WordPress.
 * Este script carga ~5KB para permitir que otros sitios incrusten
 * nuestros posts. No lo necesitamos.
 */
function humanperu_desactivar_embeds() {
    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'humanperu_desactivar_embeds' );

/**
 * Remover el filtro de emojis en el DNS prefetch.
 * WordPress agrega un dns-prefetch al CDN de emojis que no necesitamos.
 */
add_filter( 'emoji_svg_url', '__return_false' );

/**
 * Desactivar XML-RPC completamente (seguridad).
 * XML-RPC es un vector de ataque para fuerza bruta y DDoS.
 * No lo necesitamos porque usamos wp-admin y la API REST.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remover la versión de WordPress de los assets.
 * Evita que atacantes identifiquen la versión exacta de WP.
 * (Nuestros propios assets usan filemtime para cache busting.)
 */
function humanperu_remover_version_assets( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src', 'humanperu_remover_version_assets' );
add_filter( 'script_loader_src', 'humanperu_remover_version_assets' );


// ═════════════════════════════════════════════════════════════════
// 7. FUNCIONES AUXILIARES DEL THEME
// ═════════════════════════════════════════════════════════════════

/**
 * Limita el excerpt (resumen) de los posts a un número de palabras.
 * WordPress muestra 55 palabras por defecto; reducimos a 25 para
 * que las tarjetas del blog se vean limpias.
 */
function humanperu_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'humanperu_excerpt_length' );

/**
 * Cambia el texto "[...]" al final del excerpt por "..."
 * Más limpio visualmente.
 */
function humanperu_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'humanperu_excerpt_more' );

/**
 * Agrega clases CSS al body según el contexto.
 * Útil para estilos específicos por página sin crear CSS adicional.
 * Ejemplo: body.page-servicios { ... }
 */
function humanperu_body_classes( $classes ) {

    // Agregar el slug de la página al body
    if ( is_page() ) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }

    // Clase para identificar el tipo de dispositivo en CSS
    // (complementa el enfoque mobile-first)
    if ( is_front_page() ) {
        $classes[] = 'hp-front-page';
    }

    return $classes;
}
add_filter( 'body_class', 'humanperu_body_classes' );

/**
 * Función helper para obtener el URI de un asset del theme.
 * Evita repetir get_template_directory_uri() en los templates.
 *
 * Uso en templates: <img src="<?php echo hp_asset('img/logo.svg'); ?>">
 *
 * @param  string $path  Ruta relativa dentro del theme
 * @return string        URL completa al asset
 */
function hp_asset( $path ) {
    return esc_url( get_template_directory_uri() . '/assets/' . ltrim( $path, '/' ) );
}

/**
 * Función helper para imprimir SVG inline desde un archivo.
 * Permite usar SVGs como iconos sin cargar Font Awesome ni
 * un icon font externo.
 *
 * Uso: <?php hp_icon('phone'); ?> → carga assets/icons/phone.svg
 *
 * @param string $name  Nombre del archivo SVG (sin extensión)
 */
function hp_icon( $name ) {
    $file = get_template_directory() . '/assets/icons/' . sanitize_file_name( $name ) . '.svg';
    if ( file_exists( $file ) ) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo file_get_contents( $file );
    }
}

/**
 * Registrar un custom walker para wp_nav_menu no es necesario
 * en este theme porque usamos clases CSS simples.
 * Si en el futuro se necesitan submenús desplegables, agregar aquí
 * un walker que añada las clases y atributos ARIA correspondientes.
 */


// ═════════════════════════════════════════════════════════════════
// 8. UTILIDAD DE ADMINISTRACIÓN — CREAR EMPLEADOS
// ═════════════════════════════════════════════════════════════════
// Función auxiliar para que el administrador cree empleados
// desde WP-CLI o desde un script de setup.
//
// Uso con WP-CLI:
//   wp eval "humanperu_crear_empleado('Rolando Salazar', 'Presidente', 'clave123');"
//
// ═════════════════════════════════════════════════════════════════

/**
 * Inserta un empleado en la tabla wp_hp_empleados.
 * La contraseña se hashea con password_hash() (bcrypt).
 *
 * @param  string  $nombre   Nombre completo del empleado
 * @param  string  $cargo    Cargo en la organización
 * @param  string  $password Contraseña en texto plano (se hashea antes de guardar)
 * @return int|false          ID del empleado insertado o false si falló
 */
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


// ═════════════════════════════════════════════════════════════════
// FIN DE functions.php
// ═════════════════════════════════════════════════════════════════
// Próximos archivos a crear:
//   - style.css       → Variables CSS + reset + componentes globales
//   - header.php      → Navbar fija con menú responsive
//   - footer.php      → Footer de 3 columnas + barra inferior
//   - front-page.php  → Página de inicio
//   - template-parts/ → cta-banner.php, press-badge.php
// ═════════════════════════════════════════════════════════════════
