# 🧠 Human Perú — WordPress Theme

**Theme personalizado para [humanperu.org.pe](https://humanperu.org.pe)**
ONG peruana dedicada a la promoción de la salud mental.

---

## Índice

1. [Resumen](#resumen)
2. [Stack tecnológico](#stack-tecnológico)
3. [Estructura de archivos](#estructura-de-archivos)
4. [Arquitectura del theme](#arquitectura-del-theme)
5. [Sistema de diseño](#sistema-de-diseño)
6. [Templates y páginas](#templates-y-páginas)
7. [Modelo de contenido](#modelo-de-contenido)
8. [JavaScript](#javascript)
9. [Plugin verificador de diplomas](#plugin-verificador-de-diplomas)
10. [Sistema de asistencia](#sistema-de-asistencia)
11. [Formulario de contacto](#formulario-de-contacto)
12. [Imágenes y tamaños](#imágenes-y-tamaños)
13. [Configuración en wp-admin](#configuración-en-wp-admin)
14. [Hosting y deploy](#hosting-y-deploy)
15. [Troubleshooting](#troubleshooting)
16. [Créditos](#créditos)

---

## Resumen

Theme clásico de WordPress (PHP) desarrollado para la Asociación Human Perú.
Reemplaza el theme anterior basado en Divi Builder con un theme custom
que prioriza rendimiento, accesibilidad y mantenibilidad.

### Características principales

- **Theme clásico PHP** — No usa Gutenberg Full Site Editing ni page builders
- **Mobile-first responsive** — 4 breakpoints: móvil, 768px, 1024px, 1280px
- **Zero jQuery** — JavaScript vanilla puro (~3KB minificado)
- **Enfoque híbrido** — Estructura fija en PHP + contenido dinámico desde wp-admin
- **Accesibilidad** — ARIA attributes, focus-visible, prefers-reduced-motion
- **Rendimiento** — Google Fonts con preconnect, lazy loading, limpieza de WP head
- **Blog completo** — Archive, single, search, sidebar con fallback manual
- **Formulario AJAX** — Contacto sin Contact Form 7, con nonce + honeypot + rate limiting
- **Sistema de asistencia** — Marcación de entrada/salida para el personal
- **Verificador de diplomas** — Plugin independiente integrado via shortcode

---

## Stack tecnológico

| Componente     | Tecnología                                      |
|----------------|--------------------------------------------------|
| CMS            | WordPress 6.9+ (classic theme)                   |
| PHP            | 8.1+ (compatible con Yachay hosting)             |
| CSS            | Vanilla CSS con custom properties (variables)     |
| JavaScript     | Vanilla JS (ES5 compatible, sin transpiler)       |
| Fuentes        | Google Fonts (Poppins + Nunito) via `wp_enqueue`  |
| Íconos         | SVG inline (estilo Lucide/Feather)                |
| Base de datos  | MariaDB (tablas custom para empleados/asistencia) |
| Hosting        | Yachay (shared hosting, Lima, Perú)               |

---

## Estructura de archivos

```
humanperu/
│
├── style.css                        ← CSS unificado (~4,500 líneas, 16 secciones)
├── screenshot.png                   ← Preview del theme en wp-admin (1200×900)
├── functions.php                    ← Setup, enqueue, AJAX handlers, helpers
│
├── header.php                       ← Navbar + search overlay + menú móvil
├── footer.php                       ← CTA banner + footer 3 columnas + wp_footer
├── sidebar.php                      ← Sidebar del blog (widgets o fallback manual)
│
├── index.php                        ← Fallback obligatorio de WordPress
├── front-page.php                   ← Página de inicio (7 secciones)
├── archive.php                      ← Listado del blog (/blog/)
├── single.php                       ← Post individual con compartir + relacionados
├── search.php                       ← Resultados de búsqueda
├── 404.php                          ← Página de error 4♥4
│
├── page-servicios.php               ← 8 servicios con índice + bloques alternados
├── page-nosotros.php                ← Identidad + equipo directivo
├── page-contacto.php                ← Formulario AJAX + sidebar de contacto
├── page-cooperacion.php             ← Tipos de cooperación + pasos
├── page-verificar.php               ← Wrapper del plugin verificador
├── page-asistencia.php              ← Sistema de marcación de asistencia
│
├── template-parts/
│   ├── cta-banner.php               ← Banner CTA (::before/::after circles)
│   ├── press-badge.php              ← Mención en El Comercio
│   └── service-card.php             ← Tarjeta de servicio reutilizable
│
├── assets/
│   ├── js/
│   │   ├── main.js                  ← Menú, scroll, search overlay, animaciones
│   │   ├── contact.js               ← Formulario de contacto AJAX
│   │   └── attendance.js            ← Reloj + marcación de asistencia
│   │
│   ├── images/
│   │   ├── logo2026.svg             ← Logo a color (navbar)
│   │   ├── logo2026-light.svg       ← Logo blanco (footer)
│   │   ├── hero-placeholder.jpg     ← Placeholder del hero
│   │   ├── historia-humanperu.jpg
│   │   ├── vision-humanperu.jpg
│   │   ├── nosotros-hero.jpg
│   │   ├── servicios-hero.jpg
│   │   ├── cooperacion-hero.jpg
│   │   ├── cooperacion-beneficios.jpg
│   │   ├── equipo/                  ← Fotos 300×300 del equipo
│   │   └── servicios/              ← 8 imágenes de servicios 540×380
│   │
│   └── icons/                       ← SVGs para hp_icon() (opcional)
│
└── notas-migracion.md               ← Notas para el deploy a producción
```

---

## Arquitectura del theme

### Flujo de una petición

```
Visitante → WordPress → Jerarquía de templates → header.php + template + footer.php
```

### Jerarquía de templates

WordPress selecciona el template en este orden de prioridad:

| URL                          | Template usado         |
|------------------------------|------------------------|
| `/`                          | `front-page.php`       |
| `/servicios/`               | `page-servicios.php`   |
| `/nosotros/`                | `page-nosotros.php`    |
| `/contacto/`                | `page-contacto.php`    |
| `/cooperacion/`             | `page-cooperacion.php` |
| `/verificar/`       | `page-verificar.php`   |
| `/asistencia/`              | `page-asistencia.php`  |
| `/blog/`                    | `archive.php`          |
| `/blog/titulo-del-post/`    | `single.php`           |
| `/category/nombre/`         | `archive.php`          |
| `/?s=término`               | `search.php`           |
| URL inexistente              | `404.php`              |
| Cualquier otro               | `index.php`            |

### Dependencias entre archivos

```
header.php ─────────────────────────────────────────────────┐
  ├── wp_head() → style.css, Google Fonts                   │
  ├── wp_nav_menu('primary') → Menú Principal               │
  ├── search overlay (HTML en header, JS en main.js)        │
  └── mobile menu panel                                     │
                                                             │
[template].php ─────────────────────────────────────────────┤
  ├── get_template_part('template-parts/cta-banner')        │
  ├── get_template_part('template-parts/press-badge')       │
  ├── get_template_part('template-parts/service-card', $args)│
  └── get_sidebar() → sidebar.php                           │
                                                             │
footer.php ─────────────────────────────────────────────────┘
  ├── CTA banner (condicional: no en /contacto/)
  ├── Footer 3 columnas
  └── wp_footer() → main.js, contact.js, attendance.js
```

---

## Sistema de diseño

### Paleta de colores

| Variable            | Hex       | Uso                              |
|---------------------|-----------|----------------------------------|
| `--hp-orange`       | `#E8833A` | CTAs, botones, highlights        |
| `--hp-orange-dark`  | `#D4732E` | Hover de botones naranja         |
| `--hp-yellow`       | `#F5B731` | Acentos, press badge border      |
| `--hp-blue`         | `#2B5F8A` | Títulos, navbar, botones sec.    |
| `--hp-dark-blue`    | `#1B3A5C` | Footer, fondos oscuros           |
| `--hp-navy`         | `#0F2A44` | Footer barra inferior            |
| `--hp-cream`        | `#FFF8F0` | Fondos cálidos, CTA banner       |
| `--hp-light`        | `#F5F3EF` | Fondos alternos, bordes          |

### Tipografía

| Fuente   | Pesos       | Uso                    |
|----------|-------------|------------------------|
| Poppins  | 400,600,700,800 | Headings (h1-h6)   |
| Nunito   | 400,500,600,700 | Body text, botones  |

Cargadas via Google Fonts con `display=swap` para evitar FOIT.

### Breakpoints (mobile-first)

| Breakpoint | Ancho    | Cambios principales                    |
|------------|----------|----------------------------------------|
| Base       | < 768px  | 1 columna, menú hamburguesa            |
| Tablet     | ≥ 768px  | 2 columnas, grillas expandidas         |
| Desktop    | ≥ 1024px | Navbar completo, 3 columnas, sidebar sticky |
| XL         | ≥ 1280px | Tipografía más grande, gaps mayores    |

### Componentes CSS reutilizables

| Clase               | Función                                    |
|----------------------|--------------------------------------------|
| `.container`         | Max-width 1200px centrado con padding      |
| `.section`           | Padding vertical estándar                  |
| `.section--alt`      | Fondo gris claro (`--hp-light`)            |
| `.btn-primary`       | Botón naranja con sombra                   |
| `.btn-secondary`     | Botón outline azul                         |
| `.card`              | Tarjeta con sombra y hover                 |
| `.tag--orange/blue`  | Etiqueta pequeña en mayúsculas             |
| `.two-col`           | Layout 2 columnas (imagen + texto)         |
| `.grid-2/3/4`        | Grillas responsive de N columnas           |
| `.hp-animate`        | Fade-in al scroll (Intersection Observer)  |

### Sombras

| Variable          | Valor                              | Uso          |
|-------------------|------------------------------------|--------------|
| `--shadow-sm`     | `0 2px 8px rgba(0,0,0,0.06)`     | Widgets      |
| `--shadow-md`     | `0 4px 20px rgba(0,0,0,0.08)`    | Tarjetas     |
| `--shadow-hover`  | `0 8px 30px rgba(0,0,0,0.14)`    | Hover        |
| `--shadow-lg`     | `0 8px 30px rgba(0,0,0,0.12)`    | Hero images  |

---

## Templates y páginas

### front-page.php (7 secciones)

1. **Hero** — Título + imagen destacada + 4 stats + 2 CTAs
2. **Introducción** — Texto centrado de bienvenida
3. **Historia** — Two-col (imagen + texto)
4. **Servicios** — 6 tarjetas con `get_template_part('service-card')`
5. **Press Badge** — Mención en El Comercio
6. **Visión** — Two-col (imagen + texto)
7. **Blog** — 3 posts recientes con WP_Query

### page-servicios.php

- Hero con imagen destacada
- Índice rápido: 8 links ancla + tarjeta "Tipos de atención" (sticky)
- 8 bloques alternados (texto izq/der) con número decorativo + objetivo

### page-nosotros.php

- Hero con imagen destacada
- 3 tarjetas de identidad (Visión, Misión, Historia)
- 4 miembros del equipo con foto circular + avatar fallback con iniciales

### page-contacto.php

- Hero centrado sin imagen
- Grid 2 columnas: formulario AJAX (izq) + datos de contacto (der)
- Sidebar con WhatsApp, emails, dirección, Google Maps
- CTA banner oculto en esta página (`is_page('contacto')`)

### Sistema de blog

- `archive.php` — Lista horizontal 70/30 con sidebar
- `single.php` — Post con prosa, tags, compartir, 3 relacionados
- `search.php` — Resultados con formulario integrado y estado vacío
- `sidebar.php` — Widgets de WP o fallback (búsqueda, recientes, categorías, CTA)

### template-parts/

- `cta-banner.php` — Banner con `::before`/`::after` circles en CSS
- `press-badge.php` — Cita editorial con borde amarillo
- `service-card.php` — Recibe `$args` (icon, title, description, delay, link)

---

## Modelo de contenido

### Contenido fijo en PHP (editar el archivo)

| Contenido                    | Archivo                  | Buscar              |
|------------------------------|--------------------------|----------------------|
| Título y texto del hero      | `front-page.php`         | `hero__title`        |
| Estadísticas (500+, 20+...) | `front-page.php`         | `hero__stat`         |
| 6 servicios del inicio       | `front-page.php`         | `$servicios`         |
| 8 servicios detallados       | `page-servicios.php`     | `$servicios`         |
| Visión, Misión, Historia     | `page-nosotros.php`      | `$pilares`           |
| 4 miembros del equipo        | `page-nosotros.php`      | `$equipo`            |
| Datos de contacto            | `footer.php`, `cta-banner.php` | `wa.me`, `mailto:` |
| URLs de redes sociales       | `header.php`, `footer.php` | `facebook.com/humanperu` |
| Cita del press badge         | `press-badge.php`        | `press-badge__quote` |
| Opciones del select contacto | `page-contacto.php`      | `<option value=`     |
| FAQs del verificador         | `page-verificar.php`     | `$faqs`              |
| Pasos de cooperación         | `page-cooperacion.php`   | `$pasos`             |

### Contenido editable desde wp-admin

| Contenido             | Dónde editarlo                          |
|-----------------------|-----------------------------------------|
| Posts del blog        | Entradas → Añadir nueva                 |
| Imágenes destacadas   | Páginas → [página] → Imagen destacada   |
| Menú de navegación    | Apariencia → Menús                      |
| Widgets del sidebar   | Apariencia → Widgets → Sidebar del Blog |
| Categorías del blog   | Entradas → Categorías                   |

---

## JavaScript

### main.js (~300 líneas)

5 módulos dentro de un único `DOMContentLoaded`:

1. **Menú móvil** — Abrir/cerrar hamburguesa, panel lateral, overlay. Cierra con: X, Escape, clic fuera, clic en link, resize a desktop.
2. **Navbar scroll** — Agrega `.navbar--scrolled` al bajar >50px. Usa `requestAnimationFrame` para rendimiento.
3. **Search overlay** — Abre/cierra el panel de búsqueda fullscreen desde la lupa del navbar.
4. **Intersection Observer** — Anima `.hp-animate` al entrar al viewport. Fallback para navegadores sin soporte.
5. **Smooth scroll** — Links `#ancla` con compensación del navbar fijo.

### contact.js (~170 líneas)

Solo se carga en `/contacto/` (condicional en functions.php).

Flujo: validación cliente → spinner → `fetch()` a `wp_ajax` → resultado ok/error.

### attendance.js (~160 líneas)

Solo se carga en `/asistencia/`.

Reloj digital con `setInterval(1000)` + fecha en español + envío AJAX de entrada/salida.

---

## Plugin verificador de diplomas

**Archivo:** `human-verificador.php` (plugin independiente, NO parte del theme)

El plugin gestiona:
- Tabla `wp_human_diplomas`
- Shortcode `[verificador_diplomas]`
- AJAX handler `verificar_diploma`
- Rate limiting y su propio CSS/JS

El theme solo provee el wrapper en `page-verificar.php` con `do_shortcode()`.

**No modificar el plugin desde el theme.**

---

## Sistema de asistencia

### Tablas en la base de datos

Creadas automáticamente al activar el theme (`after_switch_theme` → `dbDelta`).

**`wp_hp_empleados`**
| Columna        | Tipo          | Descripción                     |
|----------------|---------------|---------------------------------|
| id             | BIGINT PK     | Auto-increment                  |
| nombre         | VARCHAR(150)  | Nombre completo                 |
| cargo          | VARCHAR(100)  | Cargo en la organización        |
| password_hash  | VARCHAR(255)  | Hash bcrypt (password_hash)     |
| activo         | TINYINT(1)    | 1=activo, 0=desactivado         |

**`wp_hp_asistencia`**
| Columna      | Tipo          | Descripción                     |
|--------------|---------------|---------------------------------|
| id           | BIGINT PK     | Auto-increment                  |
| empleado_id  | BIGINT FK     | Referencia a wp_hp_empleados    |
| tipo         | ENUM          | 'entrada' o 'salida'           |
| fecha        | DATE          | Fecha de la marcación           |
| hora         | TIME          | Hora de la marcación            |
| ip           | VARCHAR(45)   | IP del dispositivo              |
| user_agent   | VARCHAR(255)  | Navegador usado                 |

### Crear empleados

```bash
wp eval "humanperu_crear_empleado('Nombre Completo', 'Cargo', 'contraseña123');"
```

### Seguridad

- Nonce CSRF en cada petición
- `password_verify()` contra hash bcrypt
- Máximo 1 marcación por tipo por día
- IP y user_agent registrados para auditoría

---

## Formulario de contacto

### Seguridad (7 capas)

1. **Nonce** — `wp_nonce_field('hp_contacto_nonce')` verificado en el servidor
2. **Honeypot** — Campo oculto `website` que los bots llenan (respuesta falsa de éxito)
3. **Rate limiting** — Máx 5 envíos por IP por hora (WordPress transients)
4. **Sanitización** — `sanitize_text_field()`, `sanitize_email()`, `sanitize_textarea_field()`
5. **Validación de asunto** — Solo acepta valores del array `$asuntos_validos`
6. **Largo mínimo** — Mensaje ≥ 10 caracteres
7. **Validación client-side** — JS valida antes de enviar (UX, no seguridad)

### Email enviado

```
De: Human Perú Web <noreply@humanperu.org.pe>
Para: mesadepartes@humanperu.org.pe
Reply-To: [nombre del visitante] <[email del visitante]>
Asunto: [Web Human Perú] Consulta sobre servicios — Juan Pérez
```

Si `wp_mail()` falla, considerar instalar WP Mail SMTP con las credenciales de Yachay.

---

## Imágenes y tamaños

### Tamaños registrados en functions.php

| Nombre    | Dimensiones | Crop | Uso                            |
|-----------|-------------|------|--------------------------------|
| `hp-hero` | 1200×600    | Sí   | Heros de páginas               |
| `hp-card` | 400×260     | Sí   | Blog grid, posts relacionados  |
| `hp-team` | 300×300     | Sí   | Fotos del equipo               |

### Tamaños de WordPress usados

| Tamaño          | Dónde se usa                        |
|-----------------|-------------------------------------|
| `large`         | Blog list (archive, search)         |
| `thumbnail`     | Sidebar posts recientes             |

### Regenerar thumbnails

Las imágenes subidas antes de activar el theme no tienen los tamaños custom.
Instalar el plugin **Regenerate Thumbnails**, ejecutar, y desinstalar.

---

## Configuración en wp-admin

### Páginas a crear

| Página            | Slug                | Template          |
|-------------------|---------------------|--------------------|
| Inicio            | `inicio`            | (Front Page)       |
| Servicios         | `servicios`         | Servicios          |
| Nosotros          | `nosotros`          | Nosotros           |
| Contacto          | `contacto`          | Contacto           |
| Cooperación       | `cooperacion`       | Cooperación        |
| Blog              | `blog`              | (archivo)          |
| Verificar diploma | `verificar` | Verificar Diploma  |
| Asistencia        | `asistencia`        | Asistencia         |

### Ajustes críticos

| Ajuste                    | Ruta                          | Valor                   |
|---------------------------|-------------------------------|-------------------------|
| Página de inicio          | Ajustes → Lectura             | Página estática: Inicio |
| Página de entradas        | Ajustes → Lectura             | Blog                    |
| Permalinks                | Ajustes → Enlaces permanentes | Nombre de la entrada    |
| Zona horaria              | Ajustes → Generales           | Lima (UTC-5)            |
| Tamaño medio              | Ajustes → Medios              | 600×600 (recomendado)   |

### Menú principal

Crear en Apariencia → Menús → "Menú Principal":
Inicio, Servicios, Cooperación, Nosotros, Contacto, Blog

Asignar a la ubicación: **Menú Principal**

---

## Hosting y deploy

### Requisitos del servidor

- PHP 8.1+
- MariaDB 10.3+ o MySQL 5.7+
- WordPress 6.5+
- Soporte para `wp_mail()` o plugin SMTP

### Deploy via FTP

1. Conectar a Yachay via FTP (FileZilla)
2. Navegar a `public_html/wp-content/themes/`
3. Subir la carpeta `humanperu/` completa
4. Activar en wp-admin → Apariencia → Temas
5. Crear páginas y configurar Lectura/Menús/Permalinks
6. Verificar con el checklist de la guía de implementación

### Post-deploy

- Desactivar Divi (dejarlo 1 semana como respaldo, luego borrar)
- Verificar que `wp_mail()` funciona (enviar un formulario de prueba)
- Correr Regenerate Thumbnails si hay imágenes antiguas

---

## Troubleshooting

### Imágenes del blog se ven pixeladas o diminutas

Las imágenes subidas antes del theme no tienen el tamaño `hp-card`. Solución:
1. Instalar plugin Regenerate Thumbnails
2. Ejecutar regeneración completa
3. Verificar que `archive.php` y `search.php` usen `'large'` (no `'hp-card'`)

### El formulario de contacto no envía emails

1. Verificar que `wp_mail()` funciona: instalar WP Mail SMTP
2. Configurar con credenciales SMTP de Yachay
3. El handler AJAX no necesita cambios — `wp_mail()` usa el plugin automáticamente

### El menú no aparece

1. Verificar que existe un menú asignado a "Menú Principal" en Apariencia → Menús
2. La ubicación `primary` debe estar registrada en functions.php (`register_nav_menus`)

### Las animaciones no funcionan

1. Verificar que `main.js` se carga (ver consola del navegador)
2. Los elementos necesitan la clase `.hp-animate`
3. En `prefers-reduced-motion: reduce`, las animaciones se desactivan intencionalmente

### Página en blanco (White Screen of Death)

1. Activar WP_DEBUG en `wp-config.php`:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```
2. Revisar `wp-content/debug.log`
3. Causa más común: error de sintaxis PHP en functions.php

### Las tablas de asistencia no existen

Se crean al activar el theme (`after_switch_theme`). Si el theme ya estaba activo:
1. Desactivar el theme momentáneamente
2. Reactivarlo → las tablas se crean

---

## Créditos

| Rol                   | Nombre / Herramienta              |
|-----------------------|-----------------------------------|
| Organización          | Asociación Human Perú             |
| Desarrollo del theme  | Claude (Anthropic) + equipo HP    |
| Fuentes               | Google Fonts (Poppins, Nunito)    |
| Íconos                | Lucide Icons (SVG inline)         |
| Hosting               | Yachay (Lima, Perú)               |

---

**Versión:** 1.0.0
**Última actualización:** Mayo 2026
**WordPress mínimo:** 6.5
**PHP mínimo:** 8.1
