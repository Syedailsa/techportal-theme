# Tech Portal Pakistan - Style Guide

## Color Palette

### Primary Colors
| Token | Hex | Usage |
|-------|-----|-------|
| `--bg-primary` | `#0B1120` | Page background |
| `--bg-secondary` | `#111827` | Footer, secondary surfaces |
| `--bg-card` | `#1A1F36` | Card backgrounds |
| `--bg-surface` | `#1E2440` | Elevated surfaces, inputs |

### Accent Colors
| Token | Hex | Usage |
|-------|-----|-------|
| `--accent` | `#00D4AA` | Primary accent (teal-green) |
| `--accent-blue` | `#00A3FF` | Secondary accent |
| `--accent-hover` | `#00E8BB` | Hover states |

### Text Colors
| Token | Hex | Usage |
|-------|-----|-------|
| `--text-primary` | `#E8ECF1` | Headings, primary text |
| `--text-secondary` | `#9BA3B5` | Body text, descriptions |
| `--text-muted` | `#6B7394` | Timestamps, meta info |

### Border Colors
| Token | Hex | Usage |
|-------|-----|-------|
| `--border-subtle` | `#2A3152` | Default borders |
| `--border-hover` | `#3B4575` | Hover borders |

### Category Tag Colors
| Category | Background | Text |
|----------|-----------|------|
| IT News | `rgba(0,163,255,0.15)` | `#00A3FF` |
| Startups | `rgba(245,158,11,0.15)` | `#F59E0B` |
| Cybersecurity | `rgba(139,92,246,0.15)` | `#8B5CF6` |
| AI | `rgba(0,212,170,0.15)` | `#00D4AA` |
| Live Shows | `rgba(239,68,68,0.15)` | `#EF4444` |

---

## Typography

### Font Stack
```css
--font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
--font-mono: 'JetBrains Mono', 'Fira Code', monospace;
```

### Type Scale
| Token | Size | Usage |
|-------|------|-------|
| `--text-xs` | `0.75rem` (12px) | Badges, timestamps |
| `--text-sm` | `0.875rem` (14px) | Card body, nav links |
| `--text-base` | `1rem` (16px) | Body text |
| `--text-lg` | `1.125rem` (18px) | Article body |
| `--text-xl` | `1.25rem` (20px) | Section headers |
| `--text-2xl` | `1.5rem` (24px) | Hero title |
| `--text-3xl` | `1.875rem` (30px) | Single post title |
| `--text-4xl` | `2.25rem` (36px) | Category headers |

### Font Weights
| Weight | Usage |
|--------|-------|
| 400 | Body text |
| 500 | Nav links, secondary text |
| 600 | Card titles, labels |
| 700 | Section headers, badges |
| 800 | Hero titles, page titles |

---

## Spacing Scale

| Token | Value |
|-------|-------|
| `--space-1` | `0.25rem` (4px) |
| `--space-2` | `0.5rem` (8px) |
| `--space-3` | `0.75rem` (12px) |
| `--space-4` | `1rem` (16px) |
| `--space-5` | `1.25rem` (20px) |
| `--space-6` | `1.5rem` (24px) |
| `--space-8` | `2rem` (32px) |
| `--space-10` | `2.5rem` (40px) |
| `--space-12` | `3rem` (48px) |
| `--space-16` | `4rem` (64px) |

---

## Border Radius

| Token | Value | Usage |
|-------|-------|-------|
| `--radius-sm` | `6px` | Buttons, inputs, badges |
| `--radius-md` | `10px` | Cards |
| `--radius-lg` | `14px` | Hero cards, featured images |
| `--radius-xl` | `20px` | Large containers |

---

## Shadows

| Token | Usage |
|-------|-------|
| `--shadow-sm` | Subtle elevation |
| `--shadow-md` | Card hover state |
| `--shadow-lg` | Modal/overlay |
| `--shadow-glow` | Accent glow effect |

---

## Transitions

| Token | Duration | Usage |
|-------|----------|-------|
| `--transition-fast` | `150ms` | Color changes, opacity |
| `--transition-base` | `250ms` | Transform, border |
| `--transition-slow` | `400ms` | Image zoom, slide |

---

## Layout

### Max Width
```css
--max-width: 1200px;
```

### Header Height
```css
--header-height: 72px; /* 60px on mobile */
```

### Grid System
- **Article cards**: 3-column grid, `1fr 1fr 1fr`
- **Video cards**: 4-column grid, `1fr 1fr 1fr 1fr`
- **Hero**: 2-column, `1fr 1fr`

### Responsive Breakpoints
| Breakpoint | Layout |
|-----------|--------|
| `> 1024px` | Full desktop layout |
| `768px - 1024px` | 2-column grids, stacked hero |
| `< 768px` | Single column, mobile nav |
| `< 480px` | Single column video cards |

---

## Components

### Header
- Sticky, glassmorphism backdrop (`blur(20px)`)
- Logo: SVG with gradient accent
- Nav: Horizontal links with active state
- Mobile: Slide-in overlay nav

### Article Card
- 16:10 aspect ratio image area
- Title: 2-line clamp
- Description: 3-line clamp
- Footer: Source + timestamp
- Hover: translateY(-2px), border accent

### Video Card
- 16:9 aspect ratio thumbnail
- Play icon overlay on hover
- Duration badge (bottom-right)
- Compact body: title + meta

### Hero Section
- Main card: 16:9 with gradient overlay
- Sidebar: 4 compact cards in column
- Badge: Source label

### Breaking Ticker
- Gradient background
- Red "Breaking" badge with pulse
- Infinite horizontal scroll

### Footer
- 4-column grid
- Brand + social icons
- Quick links (2 columns)
- Newsletter signup form
- Bottom bar: Copyright + legal links

---

## Logo Usage

### Files
- `assets/logo.svg` - Full logo (dark background)
- `assets/logo-dark.svg` - Full logo (light background)
- `assets/logo-mobile.svg` - TP mark only (mobile)
- `assets/logo.png` - PNG export

### Minimum Size
- Desktop: 160px wide
- Mobile: 40px (mark only)

---

## Animations

### Scroll Reveal
- Element fades in + slides up 20px
- Triggered by IntersectionObserver
- Threshold: 0.1 (10% visible)
- Staggered: 80ms delay per card

### Hover States
- Cards: `translateY(-2px)` + shadow
- Links: Color transition 150ms
- Images: `scale(1.04)` zoom
- Play icon: Fade in 150ms

### Breaking Ticker
- `ticker-scroll` animation: 40s linear infinite
- Seamless loop (content duplicated)

---

## File Structure
```
techportal-child/
  style.css           # Main stylesheet (28KB)
  functions.php       # Theme functions
  header.php          # Site header
  footer.php          # Site footer
  front-page.php      # Homepage template
  page.php            # Generic page template
  single.php          # Single post template
  index.php           # Blog/archive template
  assets/
    logo.svg          # Primary logo
    logo-dark.svg     # Light background logo
    logo-mobile.svg   # Mobile logo mark
    logo.png          # PNG export
    main.js           # JavaScript (scroll, menu, newsletter)
  page-templates/
    template-videos.php  # Videos page template
```
