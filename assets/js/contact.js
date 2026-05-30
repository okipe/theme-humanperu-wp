/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — contact.js (EmailJS)
 * ═══════════════════════════════════════════════════════════════════
 *
 * Archivo: assets/js/contact.js
 *
 * Maneja el formulario de contacto vía EmailJS.
 * Reemplaza el sistema AJAX anterior que era bloqueado por el
 * firewall mod_security del hosting Yachay.
 *
 * Flujo:
 *   1. Usuario completa los campos y envía el formulario
 *   2. Validación en el cliente (campos requeridos, email válido)
 *   3. Si pasa: enviar con emailjs.send()
 *   4. EmailJS lo reenvía a servicios@humanperu.org.pe vía Gmail
 *   5. Mostrar resultado (éxito o error) en la página
 *
 * @package HumanPeru
 */

document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    // ── Credenciales de EmailJS ───────────────────────────────────
    var EMAILJS_PUBLIC_KEY = "XEMa1KFIjgMpFaEBM";
    var EMAILJS_SERVICE_ID = "service_9coeehr";
    var EMAILJS_TEMPLATE_ID = "template_r7lhsvr";

    // Inicializar EmailJS
    emailjs.init(EMAILJS_PUBLIC_KEY);

    var form = document.getElementById("hp-contacto-form");
    var btnSubmit = document.getElementById("hp-contacto-btn");
    var resultado = document.getElementById("hp-contacto-resultado");

    if (!form || !btnSubmit) return;

    // ── Textos del botón ──────────────────────────────────────────
    var BTN_TEXTO_NORMAL = btnSubmit.innerHTML;
    var BTN_TEXTO_ENVIANDO =
        '<span class="contacto-spinner"></span> Enviando...';

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // ── PASO 1: Validación en el cliente ──────────────────────
        var nombre = form.querySelector('[name="nombre"]');
        var email = form.querySelector('[name="email"]');
        var asunto = form.querySelector('[name="asunto"]');
        var mensaje = form.querySelector('[name="mensaje"]');
        var honeypot = form.querySelector('[name="website"]');

        // Si el honeypot tiene contenido, es un bot — ignorar silenciosamente
        if (honeypot && honeypot.value.trim() !== "") {
            mostrarResultado(
                "ok",
                "¡Mensaje enviado correctamente! Nuestro equipo te responderá en un plazo máximo de 24 horas.",
            );
            return;
        }

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

        // ── PASO 2: Mostrar estado de carga ───────────────────────
        bloquearFormulario(true);
        resultado.innerHTML = "";

        // ── PASO 3: Obtener teléfono (opcional) ───────────────────
        var telefonoEl = form.querySelector('[name="telefono"]');
        var telefono = telefonoEl ? telefonoEl.value.trim() : "";

        // ── PASO 4: Enviar con EmailJS ────────────────────────────
        var templateParams = {
            from_name: nombre.value.trim(),
            email: email.value.trim(),
            reply_to: email.value.trim(),
            phone: telefono || "No proporcionado",
            subject: asunto.value,
            message: mensaje.value.trim(),
        };

        emailjs
            .send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, templateParams)
            .then(function () {
                mostrarResultado(
                    "ok",
                    "¡Mensaje enviado correctamente! Nuestro equipo te responderá en un plazo máximo de 24 horas.",
                );
                form.reset();
            })
            .catch(function (error) {
                console.error("HP Contacto — EmailJS error:", error);
                mostrarResultado(
                    "error",
                    "No se pudo enviar el mensaje. Por favor contáctanos directamente a servicios@humanperu.org.pe o por WhatsApp al +51 923 322 521.",
                );
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

        resultado.scrollIntoView({ behavior: "smooth", block: "nearest" });
    }
});
