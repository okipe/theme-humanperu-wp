/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — contact.js
 * ═══════════════════════════════════════════════════════════════════
 *
 * Archivo: assets/js/contact.js
 *
 * Maneja el formulario de contacto vía AJAX.
 * Solo se carga en la página de contacto (encolado condicional
 * en functions.php con is_page('contacto')).
 *
 * Flujo:
 *   1. Usuario completa los campos y envía el formulario
 *   2. Validación en el cliente (campos requeridos, email válido)
 *   3. Si pasa: enviar petición AJAX al servidor
 *   4. El servidor valida, sanitiza y envía el email con wp_mail()
 *   5. Mostrar resultado (éxito o error) en la página
 *
 * @package HumanPeru
 */

document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    var form = document.getElementById("hp-contacto-form");
    var btnSubmit = document.getElementById("hp-contacto-btn");
    var resultado = document.getElementById("hp-contacto-resultado");

    if (!form || !btnSubmit) return;

    // ── Textos del botón ──────────────────────────────────────
    var BTN_TEXTO_NORMAL = btnSubmit.innerHTML;
    var BTN_TEXTO_ENVIANDO =
        '<span class="contacto-spinner"></span> Enviando...';

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // ── PASO 1: Validación en el cliente ─────────────────
        var nombre = form.querySelector('[name="nombre"]');
        var email = form.querySelector('[name="email"]');
        var asunto = form.querySelector('[name="asunto"]');
        var mensaje = form.querySelector('[name="mensaje"]');
        var honeypot = form.querySelector('[name="website"]');

        // Limpiar errores previos
        limpiarErrores();

        var errores = [];

        if (!nombre.value.trim()) {
            marcarError(nombre, "El nombre es obligatorio.");
            errores.push("nombre");
        }

        if (!email.value.trim()) {
            marcarError(email, "El correo es obligatorio.");
            errores.push("email");
        } else if (!validarEmail(email.value.trim())) {
            marcarError(email, "Ingresa un correo electrónico válido.");
            errores.push("email");
        }

        if (!asunto.value) {
            marcarError(asunto, "Selecciona un asunto.");
            errores.push("asunto");
        }

        if (!mensaje.value.trim()) {
            marcarError(mensaje, "El mensaje es obligatorio.");
            errores.push("mensaje");
        } else if (mensaje.value.trim().length < 10) {
            marcarError(
                mensaje,
                "El mensaje debe tener al menos 10 caracteres.",
            );
            errores.push("mensaje");
        }

        // Si hay errores, enfocar el primer campo con error y detener
        if (errores.length > 0) {
            var primerCampo = form.querySelector(
                ".contacto-form__field--error input, .contacto-form__field--error select, .contacto-form__field--error textarea",
            );
            if (primerCampo) primerCampo.focus();
            return;
        }

        // ── PASO 2: Mostrar estado de carga ──────────────────
        bloquearFormulario(true);
        resultado.innerHTML = "";

        // ── PASO 3: Enviar via AJAX ──────────────────────────
        var formData = new FormData(form);
        formData.append("action", "hp_enviar_contacto");

        // Después — convertir formData a parámetros GET
        var params = new URLSearchParams(formData);
        params.append("hp_action", "contacto");

        fetch(hp_contacto.ajax_url + "&" + params.toString(), {
            method: "GET",
        })
            .then(function (res) {
                if (!res.ok) throw new Error("Error de red: " + res.status);
                return res.json();
            })
            .then(function (data) {
                if (data.success) {
                    mostrarResultado("ok", data.data.message);
                    form.reset();
                } else {
                    mostrarResultado(
                        "error",
                        data.data.message ||
                            "Ocurrió un error. Intenta nuevamente.",
                    );
                }
            })
            .catch(function (err) {
                mostrarResultado(
                    "error",
                    "No se pudo conectar con el servidor. Verifica tu conexión e intenta de nuevo.",
                );
                console.error("HP Contacto — Error:", err);
            })
            .finally(function () {
                bloquearFormulario(false);
            });
    });

    // Limpiar error individual cuando el usuario empieza a escribir
    var campos = form.querySelectorAll(
        ".contacto-form__input, .contacto-form__select, .contacto-form__textarea",
    );
    for (var i = 0; i < campos.length; i++) {
        campos[i].addEventListener("input", function () {
            var field = this.closest(".contacto-form__field");
            if (field) field.classList.remove("contacto-form__field--error");
            var msgEl = field
                ? field.querySelector(".contacto-form__error-msg")
                : null;
            if (msgEl) msgEl.remove();
        });
    }

    // ═════════════════════════════════════════════════════════════
    // FUNCIONES AUXILIARES
    // ═════════════════════════════════════════════════════════════

    function validarEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function marcarError(campo, mensaje) {
        var field = campo.closest(".contacto-form__field");
        if (!field) return;
        field.classList.add("contacto-form__field--error");
        // Agregar mensaje de error debajo del campo
        var msgEl = document.createElement("span");
        msgEl.className = "contacto-form__error-msg";
        msgEl.textContent = mensaje;
        field.appendChild(msgEl);
    }

    function limpiarErrores() {
        var erroresVisibles = form.querySelectorAll(
            ".contacto-form__field--error",
        );
        for (var i = 0; i < erroresVisibles.length; i++) {
            erroresVisibles[i].classList.remove("contacto-form__field--error");
        }
        var msgs = form.querySelectorAll(".contacto-form__error-msg");
        for (var i = 0; i < msgs.length; i++) {
            msgs[i].remove();
        }
    }

    function bloquearFormulario(bloqueado) {
        btnSubmit.disabled = bloqueado;
        btnSubmit.innerHTML = bloqueado ? BTN_TEXTO_ENVIANDO : BTN_TEXTO_NORMAL;
        // Deshabilitar todos los campos durante el envío
        var inputs = form.querySelectorAll("input, select, textarea");
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].disabled = bloqueado;
        }
    }

    function mostrarResultado(tipo, mensaje) {
        var clase =
            tipo === "ok"
                ? "contacto-form__resultado--ok"
                : "contacto-form__resultado--error";
        var icono = tipo === "ok" ? "✅" : "⚠️";

        resultado.innerHTML =
            '<div class="' +
            clase +
            '">' +
            "<span>" +
            icono +
            "</span> " +
            mensaje +
            "</div>";

        // Scroll suave al resultado para que el usuario lo vea
        resultado.scrollIntoView({ behavior: "smooth", block: "nearest" });
    }
});
