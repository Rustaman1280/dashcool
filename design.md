# Design — Dashcool (Sistem Informasi Sekolah & Portal SPMB)

A locked design system for Dashcool. Every page redesign in this project reads this file before emitting code.

/* Hallmark · genre: modern-minimal · tone: soft · macrostructure: Workbench (Admin) / Focus-Flow (Portal) · design-system: design.md · designed-as-app */

## Genre
`modern-minimal` (Soft Humanist / Approachable Enterprise) — Dirancang agar nyaman digunakan oleh staf admin/guru setiap hari dan mudah dipahami oleh orang tua/wali murid dari berbagai rentang usia di perangkat mobile & desktop.

## Macrostructure Family
- **Admin / App Pages**: **Workbench** — Navigasi presisi, metric summary strip fungsional, tata letak data berstruktur jelas tanpa banner gelap yang berlebihan.
- **Public SPMB Portal**: **Focus Flow / Stepper** — Formulir pendaftaran terstruktur langkah-demi-langkah, bidang sentuh lega (min 48px), tipografi jelas (min 14px) yang ramah mata orang tua.

## Theme & Palette (OKLCH)
- `--color-paper`: `oklch(98.5% 0.006 80)` — Background dasar hangat (Warm Alabaster).
- `--color-paper-card`: `oklch(100% 0 0)` — Permukaan kartu putih bersih.
- `--color-paper-subtle`: `oklch(96% 0.008 80)` — Background secondary / input field background.
- `--color-ink`: `oklch(22% 0.02 60)` — Teks utama warm charcoal (kontras tinggi, tidak menyilaukan).
- `--color-ink-muted`: `oklch(48% 0.02 60)` — Teks pendukung / label keterangan.
- `--color-rule`: `oklch(91% 0.01 75)` — Garis batas hairline halus.
- `--color-accent`: `oklch(52% 0.13 245)` — Soft Slate Blue (warna identitas sekolah yang tepercaya).
- `--color-accent-soft`: `oklch(94% 0.03 245)` — Highlight latar aksen lembut.
- `--color-success`: `oklch(56% 0.12 155)` — Calming Sage Green (status diterima/berhasil).
- `--color-warning`: `oklch(68% 0.13 75)` — Warm Honey Amber (status pending/proses verifikasi).
- `--color-danger`: `oklch(55% 0.15 25)` — Soft Crimson (status ditolak/peringatan).

## Typography
- **Display**: `'Plus Jakarta Sans', 'Instrument Sans', sans-serif`, weight 600–700 (Ramah, berbobot jelas).
- **Body**: `'Instrument Sans', 'Plus Jakarta Sans', sans-serif`, weight 400–500 (Legibilitas tinggi).
- **Numbers / Metrics**: `font-variant-numeric: tabular-nums;` (Semua angka tersusun rapi).
- **Minimum Font Size**: 12px untuk badge status, 14px untuk teks biasa, 16px untuk input formulir di perangkat mobile.

## Spacing & Radius
- Menggunakan skala 4-pt Tailwind (`p-4`, `p-6`, `gap-5`).
- Radius kartu & kontainer: `rounded-2xl` (16px) untuk estetika soft & modern.
- Radius tombol & input: `rounded-xl` (12px) dengan padding nyaman (`px-4 py-2.5`).

## Motion & Tactile Feedback
- **Duration**: `150ms–220ms` dengan `--ease-out: cubic-bezier(0.16, 1, 0.3, 1)`.
- **Interaksi**: Hindari `hover:-translate-y-1` melayang berlebihan. Gunakan perubahan warna border lembut atau background tint seketika.
- **Accessibility**: Mendukung `prefers-reduced-motion: reduce`.

## Exports

### tokens.css
```css
:root {
  --color-paper: oklch(98.5% 0.006 80);
  --color-paper-card: oklch(100% 0 0);
  --color-paper-subtle: oklch(96% 0.008 80);
  --color-ink: oklch(22% 0.02 60);
  --color-ink-muted: oklch(48% 0.02 60);
  --color-rule: oklch(91% 0.01 75);
  --color-accent: oklch(52% 0.13 245);
  --color-accent-soft: oklch(94% 0.03 245);
  --color-success: oklch(56% 0.12 155);
  --color-warning: oklch(68% 0.13 75);
  --color-danger: oklch(55% 0.15 25);

  --font-display: 'Plus Jakarta Sans', 'Instrument Sans', sans-serif;
  --font-body: 'Instrument Sans', 'Plus Jakarta Sans', sans-serif;

  --radius-card: 1rem;
  --radius-button: 0.75rem;
  --radius-input: 0.75rem;
  --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
}
```
