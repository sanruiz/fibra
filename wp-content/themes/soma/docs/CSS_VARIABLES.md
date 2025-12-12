# CSS Variables System - SOMA Theme v3.0.0

## Overview

El sistema de CSS Variables centraliza todos los design tokens del theme SOMA, permitiendo un mantenimiento más fácil, consistencia visual, y la base para futuras mejoras como dark mode.

**Total de Variables**: 200+ tokens CSS  
**Naming Convention**: `--soma-[category]-[property]-[variant]`  
**Archivo Principal**: `assets/css/variables.css`

---

## Categorías de Variables

### 1. Brand Colors

Colores principales de la marca y palette de colores.

```css
/* Primary Colors */
--soma-color-primary: #171717;
--soma-color-primary-light: #7E7E87;
--soma-color-primary-dark: #000000;

/* Secondary Colors */
--soma-color-secondary: #FFFFFF;
--soma-color-secondary-light: #F5F5F5;
--soma-color-secondary-dark: #D8D8D8;

/* Accent Colors */
--soma-color-accent: #dc3232;
--soma-color-accent-hover: #c32929;
```

**Uso en SCSS**:
```scss
.button {
    background-color: var(--soma-color-accent, #dc3232);
    &:hover {
        background-color: var(--soma-color-accent-hover, #c29 29);
    }
}
```

---

### 2. Text Colors

Colores para textos en diferentes contextos.

```css
--soma-color-text-primary: #171717;
--soma-color-text-secondary: #7E7E87;
--soma-color-text-light: #FFFFFF;
--soma-color-text-muted: #999999;
```

**Uso**:
```scss
p {
    color: var(--soma-color-text-primary);
}

.muted {
    color: var(--soma-color-text-muted);
}
```

---

### 3. Background Colors

```css
--soma-color-bg-white: #FFFFFF;
--soma-color-bg-light: #F5F5F5;
--soma-color-bg-dark: #171717;
--soma-color-bg-black: #000000;
```

---

### 4. Border Colors

```css
--soma-color-border-light: #D8D8D8;
--soma-color-border-dark: #7E7E87;
--soma-color-border-white: #FFFFFF;
--soma-color-border-accent: #dc3232;
```

---

### 5. Typography System

#### Font Families
```css
--soma-font-family-primary: 'Roboto', sans-serif;
--soma-font-family-secondary: 'Roboto';
--soma-font-family-fallback: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
```

#### Font Sizes (Desktop)
```css
--soma-font-size-h1: 74px;
--soma-font-size-h2: 74px;
--soma-font-size-h3: 34px;
--soma-font-size-h4: 56px;
--soma-font-size-h5: 30px;
--soma-font-size-h6: 25px;
--soma-font-size-body: 20px;
--soma-font-size-small: 16px;
--soma-font-size-tiny: 14px;
```

#### Font Sizes (Mobile)
```css
--soma-font-size-h1-mobile: 44px;
--soma-font-size-h2-mobile: 44px;
--soma-font-size-h3-mobile: 25px;
--soma-font-size-body-mobile: 18px;
```

**Responsive Typography Example**:
```scss
h2 {
    font-size: var(--soma-font-size-h2, 74px);
    
    @media (max-width: 767px) {
        font-size: var(--soma-font-size-h2-mobile, 44px);
    }
}
```

#### Font Weights
```css
--soma-font-weight-normal: 400;
--soma-font-weight-medium: 500;
--soma-font-weight-semibold: 600;
--soma-font-weight-bold: 700;
```

#### Line Heights
```css
--soma-line-height-h2: 72px;
--soma-line-height-h2-mobile: 44px;
--soma-line-height-body: 27px;
--soma-line-height-body-mobile: 24px;
```

#### Letter Spacing
```css
--soma-letter-spacing-tight: -1.5px;
--soma-letter-spacing-medium: -1.4px;
--soma-letter-spacing-small: -0.37px;
--soma-letter-spacing-normal: 0;
```

---

### 6. Spacing System

Sistema de spacing basado en múltiplos de 10px para consistencia.

```css
--soma-spacing-xs: 10px;
--soma-spacing-sm: 20px;
--soma-spacing-md: 30px;
--soma-spacing-lg: 40px;
--soma-spacing-xl: 50px;
--soma-spacing-2xl: 60px;
--soma-spacing-3xl: 70px;
--soma-spacing-4xl: 80px;
--soma-spacing-5xl: 90px;
```

**Valores Comunes Adicionales**:
```css
--soma-spacing-5: 5px;
--soma-spacing-15: 15px;
--soma-spacing-25: 25px;
--soma-spacing-45: 45px;
--soma-spacing-55: 55px;
```

**Uso**:
```scss
.section {
    padding-top: var(--soma-spacing-5xl);
    padding-bottom: var(--soma-spacing-2xl);
    margin-bottom: var(--soma-spacing-lg);
}
```

---

### 7. Layout - Containers

```css
--soma-container-max-width: 1440px;
--soma-container-padding-desktop: 80px;
--soma-container-padding-tablet-landscape: 40px;
--soma-container-padding-tablet-portrait: 30px;
--soma-container-padding-mobile: 20px;
```

**Uso en `.container` class**:
```scss
.container {
    max-width: var(--soma-container-max-width);
    padding: 0 var(--soma-container-padding-desktop);
    
    @media (max-width: 1024px) {
        padding: 0 var(--soma-container-padding-tablet-landscape);
    }
    
    @media (max-width: 767px) {
        padding: 0 var(--soma-container-padding-mobile);
    }
}
```

---

### 8. Layout - Borders

```css
--soma-border-width-thin: 1px;
--soma-border-width-medium: 2px;
--soma-border-radius-none: 0;
--soma-border-radius-sm: 4px;
--soma-border-radius-md: 8px;
--soma-border-radius-lg: 12px;
--soma-border-radius-full: 9999px;
```

---

### 9. Layout - Shadows

```css
--soma-shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
--soma-shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
--soma-shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.15);
--soma-shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.2);
```

---

### 10. Layout - Transitions

```css
--soma-transition-fast: 150ms ease;
--soma-transition-base: 300ms ease;
--soma-transition-slow: 500ms ease;
--soma-transition-all: all 0.3s ease;
```

**Uso**:
```scss
a {
    transition: var(--soma-transition-base);
    
    &:hover {
        color: var(--soma-color-accent);
    }
}
```

---

### 11. Layout - Z-Index

```css
--soma-z-index-dropdown: 1000;
--soma-z-index-sticky: 1020;
--soma-z-index-fixed: 1030;
--soma-z-index-modal-backdrop: 1040;
--soma-z-index-modal: 1050;
--soma-z-index-popover: 1060;
--soma-z-index-tooltip: 1070;
```

---

### 12. Breakpoints

Para uso con JavaScript:

```css
--soma-breakpoint-mobile: 767px;
--soma-breakpoint-tablet-portrait: 768px;
--soma-breakpoint-tablet-landscape: 1024px;
--soma-breakpoint-desktop: 1440px;
--soma-breakpoint-wide: 1920px;
```

**Acceso desde JavaScript**:
```javascript
const mobileBreakpoint = getComputedStyle(document.documentElement)
    .getPropertyValue('--soma-breakpoint-mobile');
```

---

### 13. Component-Specific Variables

#### Footer
```css
--soma-footer-padding-top: 90px;
--soma-footer-padding-bottom: 60px;
--soma-footer-padding-top-mobile: 80px;
--soma-footer-padding-bottom-mobile: 55px;
--soma-footer-logo-width: 152px;
--soma-footer-logo-width-mobile: 127px;
--soma-footer-logo-width-fibrasoma: 292px;
--soma-footer-logo-width-fibrasoma-mobile: 244px;
```

---

## Convención de Nombres

### Estructura
```
--soma-[category]-[property]-[variant]
```

### Ejemplos
- `--soma-color-text-primary` → categoria: color, propiedad: text, variante: primary
- `--soma-font-size-h2-mobile` → categoria: font, propiedad: size, variante: h2-mobile
- `--soma-spacing-xl` → categoria: spacing, variante: xl
- `--soma-footer-padding-top` → categoria: footer, propiedad: padding, variante: top

---

## Uso en SCSS

### Sintaxis Básica
```scss
.element {
    color: var(--soma-color-text-primary);
}
```

### Con Fallback
```scss
.element {
    // Si la variable no existe, usa el fallback
    color: var(--soma-color-text-primary, #171717);
}
```

### Reemplazando Variables SCSS
```scss
// ❌ Antes (hardcoded)
.element {
    font-family: 'Roboto', sans-serif;
    color: #171717;
    font-size: 20px;
}

// ✅ Después (usando CSS variables)
.element {
    font-family: var(--soma-font-family-primary);
    color: var(--soma-color-text-primary);
    font-size: var(--soma-font-size-body);
}
```

---

## Integración con Elementor

Los widgets de Elementor pueden usar las CSS variables directamente:

```php
// En el método render() de un widget
echo '<div style="color: var(--soma-color-text-primary)">Content</div>';
```

**Typography Controls**:
Los Elementor typography controls pueden integrarse con las variables:

```php
$this->add_control(
    'title_color',
    [
        'label' => __( 'Title Color', 'soma' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'default' => 'var(--soma-color-text-primary)',
        'selectors' => [
            '{{WRAPPER}} .title' => 'color: {{VALUE}}',
        ],
    ]
);
```

---

## Mantenimiento

### Agregar Nueva Variable

1. Editar `assets/css/variables.css`
2. Agregar en la categoría apropiada
3. Seguir convención de nombres
4. Documentar en este archivo
5. Actualizar componentes que la usen

**Ejemplo**:
```css
/* En assets/css/variables.css */
:root {
    /* ... otras variables ... */
    
    /* Nueva variable en categoría apropiada */
    --soma-color-link-hover: #0052CC;
}
```

### Modificar Variable Existente

⚠️ **Precaución**: Cambiar el valor de una variable afecta **todos** los componentes que la usan.

1. Identificar todos los usos con búsqueda global
2. Hacer cambio en `variables.css`
3. Probar visualmente todos los componentes afectados
4. Documentar el cambio

---

## Testing

### Visual Testing Checklist

Después de modificar variables CSS:

- [ ] Homepage sections (todas las variantes)
- [ ] Footer (ambos estilos: default y fibrasoma)
- [ ] Navbar (ambos estilos)
- [ ] Widgets de Elementor (8 widgets)
- [ ] Post types (portfolio, news, careers, team_members)
- [ ] Responsive (mobile, tablet portrait/landscape, desktop)
- [ ] Dark mode variants (si aplica)

### Automated Testing

```bash
# Build CSS
npm run prod

# Validar PHP (no debería afectar)
vendor/bin/phpcs
vendor/bin/phpstan analyse

# Probar en navegador
# Abrir http://localhost:10004
```

---

## Dark Mode (Futuro)

El sistema está preparado para dark mode. Para activar:

1. Descomentar bloque en `variables.css`:
```css
[data-theme="dark"] {
    --soma-color-primary: #FFFFFF;
    --soma-color-secondary: #171717;
    /* ... más overrides ... */
}
```

2. Agregar toggle JavaScript:
```javascript
document.documentElement.setAttribute('data-theme', 'dark');
```

3. Probar todos los componentes en ambos modos

---

## Beneficios

### Mantenimiento
- ✅ Un solo lugar para cambiar valores
- ✅ Naming consistente y descriptivo
- ✅ Fácil identificar qué afecta cada cambio

### Performance
- ✅ CSS nativo (sin preprocessor overhead)
- ✅ Cacheable
- ✅ Sin JavaScript requerido

### Escalabilidad
- ✅ Preparado para dark mode
- ✅ Preparado para theming
- ✅ Fácil agregar más tokens

### Developer Experience
- ✅ Autocompletado en IDE
- ✅ Documentación centralizada
- ✅ Fallbacks para compatibilidad

---

## Compatibilidad de Navegadores

CSS Variables soportadas en:
- ✅ Chrome 49+
- ✅ Firefox 31+
- ✅ Safari 9.1+
- ✅ Edge 15+
- ✅ iOS Safari 9.3+
- ✅ Android Chrome 49+

**IE11**: ❌ No soporta CSS Variables. Los fallbacks en `var()` se aplican automáticamente.

---

## Archivos Migrados

Estado de migración de archivos SCSS:

### Archivos Core
- ✅ `sass/_general.scss` - Tipografía, containers, headings (MIGRADO)
- ⏳ `sass/partials/_Footer.scss` - Footer styles (PENDIENTE)
- ⏳ `sass/partials/_Navbar.scss` - Navbar styles (PENDIENTE)
- ⏳ 52 partials más (PENDIENTE)

### Prioridad de Migración
1. Core files (`_general.scss`) ✅
2. Layout partials (Footer, Navbar)
3. Content partials (Portfolio, News, etc.)
4. Special partials (FibrasomaHome*, etc.)

---

## Referencias

- **Archivo Principal**: `assets/css/variables.css`
- **Theme Config**: `inc/theme-config.php` (carga variables.css primero)
- **Migration Plan**: `docs/MIGRATION_PLAN.md` (Fase 5)
- **Architecture**: `docs/ARCHITECTURE_VISION.md`

---

**Última Actualización**: December 12, 2025  
**Versión**: 1.0  
**Estado**: In Progress (Fase 5)
