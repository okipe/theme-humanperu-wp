<?php
/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — page-asistencia.php (Asistencia del Personal)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Template Name: Asistencia
 *
 * Sistema de marcación de asistencia para el equipo de Human Perú.
 * Los empleados seleccionan su nombre, ingresan su contraseña y
 * marcan entrada o salida. Se registra en la tabla wp_hp_asistencia.
 *
 * Componentes:
 *   1. Reloj digital en tiempo real (JS)
 *   2. Selector de empleado (cargado desde wp_hp_empleados)
 *   3. Campo de contraseña
 *   4. Botones de Entrada / Salida
 *   5. Resultado de la marcación (éxito/error)
 *
 * Seguridad:
 *   - Nonce para CSRF
 *   - password_verify() en el servidor
 *   - 1 marcación por tipo por día
 *   - IP y user_agent registrados para auditoría
 *
 * @package HumanPeru
 */

get_header();

// ── Obtener lista de empleados activos ───────────────────────
global $wpdb;
$tabla_empleados = $wpdb->prefix . 'hp_empleados';
$empleados = $wpdb->get_results(
    "SELECT id, nombre, cargo FROM {$tabla_empleados} WHERE activo = 1 ORDER BY nombre ASC"
);
?>

<div class="hero-bar" aria-hidden="true"></div>

<!-- ════════════════════════════════════════════════════════════════
     ENCABEZADO
     ════════════════════════════════════════════════════════════════ -->

<section class="asistencia-page">
    <div class="container">

        <div class="verificar-page__header hp-animate">
            <span class="tag tag--blue">Control interno</span>
            <h1>Asistencia del personal</h1>
            <p>Registra tu entrada y salida diaria.</p>
        </div>

        <!-- ════════════════════════════════════════════════════
             TARJETA DE ASISTENCIA
             ════════════════════════════════════════════════════ -->

        <div class="asistencia-card hp-animate">

            <!-- Reloj digital (actualizado cada segundo por JS) -->
            <div class="asistencia-reloj" id="hp-reloj" aria-live="polite" aria-label="Hora actual">
                --:--:--
            </div>
            <p class="asistencia-fecha" id="hp-fecha">
                Cargando fecha...
            </p>

            <!-- Formulario de asistencia -->
            <form id="hp-asistencia-form" autocomplete="off">

                <?php wp_nonce_field( 'hp_asistencia_nonce', 'hp_asistencia_security' ); ?>

                <!-- Selector de empleado -->
                <label for="hp-empleado" class="sr-only">Selecciona tu nombre</label>
                <select id="hp-empleado"
                        name="empleado_id"
                        class="asistencia-select"
                        required>
                    <option value="" disabled selected>Selecciona tu nombre</option>
                    <?php foreach ( $empleados as $emp ) : ?>
                        <option value="<?php echo esc_attr( $emp->id ); ?>">
                            <?php echo esc_html( $emp->nombre ); ?> — <?php echo esc_html( $emp->cargo ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Contraseña -->
                <label for="hp-password" class="sr-only">Contraseña</label>
                <input type="password"
                       id="hp-password"
                       name="password"
                       class="asistencia-password"
                       placeholder="Tu contraseña"
                       required
                       maxlength="50">

                <!-- Botones de entrada y salida -->
                <div class="asistencia-actions">
                    <button type="button"
                            class="asistencia-btn asistencia-btn--entrada"
                            id="hp-btn-entrada"
                            data-tipo="entrada">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
                             style="display:inline; vertical-align:middle; margin-right:4px;">
                            <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        Marcar entrada
                    </button>

                    <button type="button"
                            class="asistencia-btn asistencia-btn--salida"
                            id="hp-btn-salida"
                            data-tipo="salida">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
                             style="display:inline; vertical-align:middle; margin-right:4px;">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Marcar salida
                    </button>
                </div>

            </form>

            <!-- Resultado de la marcación -->
            <div id="hp-asistencia-resultado"></div>

        </div>

        <!-- Nota de privacidad -->
        <p class="asistencia-note hp-animate">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
            </svg>
            Tu contraseña se transmite de forma segura y no se almacena en texto plano.
        </p>

    </div>
</section>


<?php get_footer(); ?>
