/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — attendance.js
 * ═══════════════════════════════════════════════════════════════════
 *
 * Archivo: assets/js/attendance.js
 *
 * Controla la página de asistencia del personal:
 *   1. Reloj digital en tiempo real
 *   2. Fecha en español
 *   3. Envío AJAX de entrada/salida
 *
 * Solo se carga en /asistencia/ (encolado condicional en functions.php).
 * Usa hp_ajax.ajax_url y hp_ajax.nonce (inyectados por wp_localize_script).
 *
 * @package HumanPeru
 */

document.addEventListener('DOMContentLoaded', function () {

    'use strict';

    var relojEl     = document.getElementById('hp-reloj');
    var fechaEl     = document.getElementById('hp-fecha');
    var form        = document.getElementById('hp-asistencia-form');
    var btnEntrada  = document.getElementById('hp-btn-entrada');
    var btnSalida   = document.getElementById('hp-btn-salida');
    var resultadoEl = document.getElementById('hp-asistencia-resultado');

    // ═════════════════════════════════════════════════════════════
    // 1. RELOJ DIGITAL — Actualiza cada segundo
    // ═════════════════════════════════════════════════════════════

    var meses = [
        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    ];
    var dias = [
        'Domingo', 'Lunes', 'Martes', 'Miércoles',
        'Jueves', 'Viernes', 'Sábado'
    ];

    function actualizarReloj() {
        var ahora = new Date();
        var h = String(ahora.getHours()).padStart(2, '0');
        var m = String(ahora.getMinutes()).padStart(2, '0');
        var s = String(ahora.getSeconds()).padStart(2, '0');

        if (relojEl) {
            relojEl.textContent = h + ':' + m + ':' + s;
        }

        if (fechaEl) {
            fechaEl.textContent =
                dias[ahora.getDay()] + ', ' +
                ahora.getDate() + ' de ' +
                meses[ahora.getMonth()] + ' de ' +
                ahora.getFullYear();
        }
    }

    // Iniciar inmediatamente y luego cada segundo
    actualizarReloj();
    setInterval(actualizarReloj, 1000);


    // ═════════════════════════════════════════════════════════════
    // 2. MARCACIÓN DE ASISTENCIA — AJAX
    // ═════════════════════════════════════════════════════════════

    if (!form || !btnEntrada || !btnSalida) return;

    /**
     * Envía la marcación de asistencia al servidor.
     * @param {string} tipo — 'entrada' o 'salida'
     */
    function marcarAsistencia(tipo) {
        var empleadoId = form.querySelector('[name="empleado_id"]').value;
        var password   = form.querySelector('[name="password"]').value;
        var nonce      = form.querySelector('[name="hp_asistencia_security"]').value;

        // Validación básica en el cliente
        if (!empleadoId) {
            mostrarResultado('error', 'Selecciona tu nombre.');
            return;
        }
        if (!password) {
            mostrarResultado('error', 'Ingresa tu contraseña.');
            return;
        }

        // Estado de carga
        bloquearBotones(true);
        resultadoEl.innerHTML = '';

        // Enviar via AJAX
        fetch(hp_ajax.ajax_url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action:                'marcar_asistencia',
                empleado_id:           empleadoId,
                password:              password,
                tipo:                  tipo,
                security:              nonce,
            }),
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Error de red: ' + res.status);
            return res.json();
        })
        .then(function (data) {
            if (data.success) {
                mostrarResultado('ok', data.data.message);
                // Limpiar contraseña tras éxito
                form.querySelector('[name="password"]').value = '';
            } else {
                mostrarResultado('error', data.data.message || 'Ocurrió un error.');
            }
        })
        .catch(function (err) {
            mostrarResultado('error', 'No se pudo conectar con el servidor. Intenta de nuevo.');
            console.error('HP Asistencia — Error:', err);
        })
        .finally(function () {
            bloquearBotones(false);
        });
    }

    // Event listeners en los botones
    btnEntrada.addEventListener('click', function () {
        marcarAsistencia('entrada');
    });

    btnSalida.addEventListener('click', function () {
        marcarAsistencia('salida');
    });


    // ═════════════════════════════════════════════════════════════
    // FUNCIONES AUXILIARES
    // ═════════════════════════════════════════════════════════════

    function bloquearBotones(bloqueado) {
        btnEntrada.disabled = bloqueado;
        btnSalida.disabled  = bloqueado;
        btnEntrada.style.opacity = bloqueado ? '0.6' : '1';
        btnSalida.style.opacity  = bloqueado ? '0.6' : '1';
    }

    function mostrarResultado(tipo, mensaje) {
        var clase = tipo === 'ok'
            ? 'asistencia-resultado asistencia-resultado--ok'
            : 'asistencia-resultado asistencia-resultado--error';

        resultadoEl.innerHTML = '<div class="' + clase + '">' + mensaje + '</div>';
    }

});
