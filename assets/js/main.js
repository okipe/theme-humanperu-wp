/**
 * ═══════════════════════════════════════════════════════════════════
 * HUMAN PERÚ — main.js
 * ═══════════════════════════════════════════════════════════════════
 *
 * JavaScript principal del theme. Vanilla JS puro (sin jQuery).
 *
 * Módulos:
 *   1. MENÚ MÓVIL — Hamburguesa, panel lateral, overlay
 *   2. NAVBAR AL SCROLL — Clase .navbar--scrolled al bajar >50px
 *   3. INTERSECTION OBSERVER — Animaciones fade-in al scroll
 *   4. SMOOTH SCROLL — Scroll suave a anclas (#) con offset del navbar
 *
 * Peso objetivo: < 3 KB minificado
 *
 * @package HumanPeru
 */

document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    // ═════════════════════════════════════════════════════════════
    // 1. MENÚ MÓVIL
    // ═════════════════════════════════════════════════════════════
    // Controla el botón hamburguesa y el panel lateral derecho.
    // Las 3 barras se animan a una X con CSS (.is-active).
    // El panel se desliza con CSS (.is-open).
    // El overlay oscuro se muestra con CSS (.is-visible).
    // ═════════════════════════════════════════════════════════════

    var hamburger = document.getElementById("navbar-hamburger");
    var mobileMenu = document.getElementById("mobile-menu");
    var mobileOverlay = document.getElementById("mobile-overlay");
    var closeBtn = document.getElementById("mobile-close");

    if (hamburger && mobileMenu && mobileOverlay && closeBtn) {
        /**
         * Abre el menú móvil:
         * - Activa las clases CSS en hamburguesa, panel y overlay
         * - Bloquea el scroll del body
         * - Actualiza atributos ARIA (accesibilidad)
         * - Mueve el foco al botón de cerrar
         */
        function abrirMenu() {
            hamburger.classList.add("is-active");
            hamburger.setAttribute("aria-expanded", "true");

            mobileMenu.classList.add("is-open");
            mobileMenu.setAttribute("aria-hidden", "false");

            mobileOverlay.classList.add("is-visible");
            mobileOverlay.setAttribute("aria-hidden", "false");

            document.body.classList.add("mobile-menu-open");

            // Mover foco al botón cerrar (accesibilidad con teclado)
            closeBtn.focus();
        }

        /**
         * Cierra el menú móvil:
         * - Remueve todas las clases de estado
         * - Desbloquea el scroll del body
         * - Devuelve el foco a la hamburguesa
         */
        function cerrarMenu() {
            hamburger.classList.remove("is-active");
            hamburger.setAttribute("aria-expanded", "false");

            mobileMenu.classList.remove("is-open");
            mobileMenu.setAttribute("aria-hidden", "true");

            mobileOverlay.classList.remove("is-visible");
            mobileOverlay.setAttribute("aria-hidden", "true");

            document.body.classList.remove("mobile-menu-open");

            hamburger.focus();
        }

        /**
         * Comprueba si el menú está abierto.
         * @returns {boolean}
         */
        function menuEstaAbierto() {
            return mobileMenu.classList.contains("is-open");
        }

        // ── Eventos del menú ─────────────────────────────────────

        // Clic en hamburguesa → toggle abrir/cerrar
        hamburger.addEventListener("click", function () {
            if (menuEstaAbierto()) {
                cerrarMenu();
            } else {
                abrirMenu();
            }
        });

        // Clic en el botón X → cerrar
        closeBtn.addEventListener("click", cerrarMenu);

        // Clic en el overlay oscuro → cerrar
        mobileOverlay.addEventListener("click", cerrarMenu);

        // Cerrar con tecla Escape (accesibilidad)
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape" && menuEstaAbierto()) {
                cerrarMenu();
            }
        });

        // Cerrar al hacer clic en cualquier link dentro del menú móvil.
        // Esto permite que la navegación sea fluida: el usuario
        // toca un link, el menú se cierra y la página carga.
        var mobileLinks = mobileMenu.querySelectorAll("a");
        for (var i = 0; i < mobileLinks.length; i++) {
            mobileLinks[i].addEventListener("click", cerrarMenu);
        }

        // Cerrar al hacer clic fuera del menú (en el documento).
        // Solo actúa si el menú está abierto Y el clic fue fuera
        // tanto del panel como de la hamburguesa.
        document.addEventListener("click", function (e) {
            if (!menuEstaAbierto()) return;

            var dentroDelMenu = mobileMenu.contains(e.target);
            var enHamburguesa = hamburger.contains(e.target);

            if (!dentroDelMenu && !enHamburguesa) {
                cerrarMenu();
            }
        });

        // Cerrar automáticamente si la ventana se agranda a desktop.
        // Ejemplo: rotar tablet a landscape, o redimensionar el navegador.
        var mediaDesktop = window.matchMedia("(min-width: 1024px)");

        function alCambiarTamano(e) {
            if (e.matches && menuEstaAbierto()) {
                cerrarMenu();
            }
        }

        if (mediaDesktop.addEventListener) {
            mediaDesktop.addEventListener("change", alCambiarTamano);
        } else if (mediaDesktop.addListener) {
            mediaDesktop.addListener(alCambiarTamano);
        }
    }

    // ═════════════════════════════════════════════════════════════
    // 2. NAVBAR AL SCROLL
    // ═════════════════════════════════════════════════════════════
    // Agrega .navbar--scrolled cuando el usuario baja más de 50px.
    //
    // Rendimiento: usamos requestAnimationFrame para agrupar las
    // actualizaciones del DOM en un solo frame (~16ms a 60fps).
    // El flag 'ticking' evita acumular múltiples rAF en cola.
    //
    // Resultado: el listener de scroll se dispara cientos de veces,
    // pero el DOM solo se toca 1 vez por frame.
    // ═════════════════════════════════════════════════════════════

    var navbar = document.getElementById("navbar");

    if (navbar) {
        var SCROLL_UMBRAL = 50;
        var estaScrolled = false;
        var ticking = false;

        /**
         * Lee la posición del scroll y actualiza la clase del navbar.
         * Solo modifica el DOM cuando el estado realmente cambia.
         */
        function actualizarNavbar() {
            var scrollY =
                window.pageYOffset || document.documentElement.scrollTop;

            if (scrollY > SCROLL_UMBRAL && !estaScrolled) {
                navbar.classList.add("navbar--scrolled");
                estaScrolled = true;
            } else if (scrollY <= SCROLL_UMBRAL && estaScrolled) {
                navbar.classList.remove("navbar--scrolled");
                estaScrolled = false;
            }

            ticking = false;
        }

        window.addEventListener(
            "scroll",
            function () {
                if (!ticking) {
                    requestAnimationFrame(actualizarNavbar);
                    ticking = true;
                }
            },
            { passive: true },
        );

        // Ejecutar inmediatamente: la página puede cargar con scroll > 0
        // (ej: el usuario recargó a mitad del contenido, o usó "volver")
        actualizarNavbar();
    }
    // ═════════════════════════════════════════════════════════════
    // 2.5 SEARCH OVERLAY — Abrir y cerrar búsqueda
    // ═════════════════════════════════════════════════════════════
    // La lupa del navbar abre un overlay de búsqueda a pantalla
    // completa. Se cierra con el botón X, Escape, o clic fuera.
    // ═════════════════════════════════════════════════════════════

    var searchBtn = document.getElementById("navbar-search-btn");
    var searchOverlay = document.getElementById("search-overlay");
    var searchClose = document.getElementById("search-overlay-close");
    var searchInput = document.getElementById("search-overlay-input");

    if (searchBtn && searchOverlay && searchClose && searchInput) {
        function abrirBusqueda() {
            searchOverlay.classList.add("is-open");
            searchOverlay.setAttribute("aria-hidden", "false");
            searchBtn.setAttribute("aria-expanded", "true");
            document.body.classList.add("search-open");

            // Enfocar el input tras la transición (300ms)
            setTimeout(function () {
                searchInput.focus();
            }, 100);
        }

        function cerrarBusqueda() {
            searchOverlay.classList.remove("is-open");
            searchOverlay.setAttribute("aria-hidden", "true");
            searchBtn.setAttribute("aria-expanded", "false");
            document.body.classList.remove("search-open");
            searchBtn.focus();
        }

        // Clic en la lupa → abrir
        searchBtn.addEventListener("click", abrirBusqueda);

        // Clic en el botón X → cerrar
        searchClose.addEventListener("click", cerrarBusqueda);

        // Escape → cerrar
        document.addEventListener("keydown", function (e) {
            if (
                e.key === "Escape" &&
                searchOverlay.classList.contains("is-open")
            ) {
                cerrarBusqueda();
            }
        });

        // Clic en el fondo oscuro (fuera del formulario) → cerrar
        searchOverlay.addEventListener("click", function (e) {
            // Solo cerrar si el clic fue en el overlay mismo, no en su contenido
            if (e.target === searchOverlay) {
                cerrarBusqueda();
            }
        });
    }

    // ═════════════════════════════════════════════════════════════
    // 3. INTERSECTION OBSERVER — Animaciones al scroll
    // ═════════════════════════════════════════════════════════════
    // Observa elementos con .hp-animate o .animate-on-scroll.
    // Al entrar al viewport, agrega las clases de visibilidad
    // y deja de observar (la animación ocurre una sola vez).
    //
    // Clases CSS disponibles en style.css:
    //   .hp-animate              → fade-in desde abajo
    //   .hp-animate--left        → fade-in desde la izquierda
    //   .hp-animate--right       → fade-in desde la derecha
    //   .hp-animate--scale       → zoom-in
    //   .hp-animate--delay-1..4  → delays escalonados para grillas
    //
    // Uso en templates PHP:
    //   <div class="hp-animate">Contenido</div>
    //   <div class="hp-animate hp-animate--delay-1">Primer item</div>
    //   <div class="animate-on-scroll">También funciona</div>
    // ═════════════════════════════════════════════════════════════

    var elementosAnimados = document.querySelectorAll(
        ".hp-animate, .animate-on-scroll",
    );

    if (elementosAnimados.length > 0) {
        if ("IntersectionObserver" in window) {
            var observerAnimaciones = new IntersectionObserver(
                function (entries) {
                    for (var i = 0; i < entries.length; i++) {
                        if (entries[i].isIntersecting) {
                            var el = entries[i].target;

                            // Agregar ambas clases para compatibilidad
                            // con los dos sistemas de nombres
                            el.classList.add("hp-animate--visible");
                            el.classList.add("is-visible");

                            // Dejar de observar (anima una sola vez)
                            observerAnimaciones.unobserve(el);
                        }
                    }
                },
                {
                    // rootMargin negativo: el elemento debe entrar al menos
                    // un 10% desde el borde inferior del viewport
                    rootMargin: "0px 0px -10% 0px",
                    threshold: 0.1,
                },
            );

            for (var i = 0; i < elementosAnimados.length; i++) {
                observerAnimaciones.observe(elementosAnimados[i]);
            }
        } else {
            // Fallback para navegadores sin IntersectionObserver:
            // mostrar todo sin animación (contenido accesible siempre).
            for (var i = 0; i < elementosAnimados.length; i++) {
                elementosAnimados[i].classList.add("hp-animate--visible");
                elementosAnimados[i].classList.add("is-visible");
            }
        }
    }

    // ═════════════════════════════════════════════════════════════
    // 4. SMOOTH SCROLL — Links internos con anclas (#)
    // ═════════════════════════════════════════════════════════════
    // Intercepta clics en links href="#seccion" y hace scroll
    // suave al destino, compensando la altura del navbar fijo.
    //
    // ¿Por qué no basta con CSS scroll-behavior: smooth?
    // Porque CSS no compensa el navbar fixed. Sin JS, el
    // contenido queda tapado debajo del navbar al llegar.
    // ═════════════════════════════════════════════════════════════

    var linksAncla = document.querySelectorAll('a[href^="#"]');

    if (linksAncla.length > 0) {
        /**
         * Obtiene la altura actual del navbar más un margen.
         * Es dinámica porque el navbar se reduce al hacer scroll.
         * @returns {number} Offset en píxeles
         */
        function obtenerOffsetNavbar() {
            var nav = document.getElementById("navbar");
            return nav ? nav.offsetHeight + 16 : 80;
        }

        for (var i = 0; i < linksAncla.length; i++) {
            linksAncla[i].addEventListener("click", function (e) {
                var href = this.getAttribute("href");

                // Ignorar links vacíos o que son solo "#"
                if (!href || href === "#" || href === "#0") return;

                // Intentar encontrar el elemento destino
                var destino;
                try {
                    destino = document.querySelector(href);
                } catch (err) {
                    // querySelector lanza error si href no es un selector
                    // CSS válido (ej: "#123" empieza con número)
                    return;
                }

                if (!destino) return;

                // Prevenir salto instantáneo del navegador
                e.preventDefault();

                // Calcular posición final = posición del elemento - navbar
                var rect = destino.getBoundingClientRect();
                var scrollActual = window.pageYOffset;
                var offset = obtenerOffsetNavbar();
                var posicionFinal = rect.top + scrollActual - offset;

                // Scroll suave nativo del navegador
                window.scrollTo({
                    top: posicionFinal,
                    behavior: "smooth",
                });

                // Actualizar la URL con el hash (para historial del navegador)
                if (history.pushState) {
                    history.pushState(null, null, href);
                }

                // Accesibilidad: mover el foco al destino para que los
                // lectores de pantalla anuncien el contenido correcto
                destino.setAttribute("tabindex", "-1");
                destino.focus({ preventScroll: true });
            });
        }
    }
}); // Fin DOMContentLoaded
