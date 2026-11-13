{{-- Gaya khusus tampilan koran (dipakai halaman daftar & detail berita). Tidak butuh build ulang Tailwind. --}}
<style>
    .koran { background: #f7f3ea; color: #1c1917; font-family: Georgia, 'Times New Roman', Times, serif; }
    .koran .kicker {
        font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        font-size: .7rem; font-weight: 800; letter-spacing: .14em; text-transform: uppercase;
    }
    .koran .sans { font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif; }
    .koran .rule-double { border-top: 4px double #1c1917; border-bottom: 1px solid #1c1917; }
    .koran .headline { font-weight: 800; letter-spacing: -.01em; }
    .koran a.hl:hover, .koran .group:hover .hl { text-decoration: underline; text-decoration-thickness: 2px; text-underline-offset: 4px; }

    /* Huruf awal besar pada paragraf pertama */
    .koran .dropcap::first-letter,
    .koran-body > p:first-of-type::first-letter {
        float: left; font-size: 4.2rem; line-height: .8; padding: .4rem .6rem 0 0; font-weight: 800; color: #7f1d1d;
    }

    /* Isi artikel (menggantikan "prose" agar tidak bergantung plugin typography) */
    .koran-body { font-size: 1.125rem; line-height: 1.85; text-align: justify; hyphens: auto; color: #292524; }
    .koran-body p { margin: 0 0 1.1rem; }
    .koran-body h2, .koran-body h3 { font-weight: 800; line-height: 1.3; margin: 1.75rem 0 .6rem; color: #1c1917; }
    .koran-body h2 { font-size: 1.5rem; }
    .koran-body h3 { font-size: 1.25rem; }
    .koran-body ul { list-style: disc; padding-left: 1.5rem; margin: 0 0 1.1rem; }
    .koran-body ol { list-style: decimal; padding-left: 1.5rem; margin: 0 0 1.1rem; }
    .koran-body blockquote { border-left: 4px solid #7f1d1d; padding-left: 1rem; margin: 1.5rem 0; font-style: italic; color: #44403c; }
    .koran-body img { max-width: min(100%, 24rem); height: auto; margin: 1.25rem auto; display: block; }

    /* Foto utama berita: kecil dan berada di samping tulisan (teks mengalir mengelilinginya) */
    .koran-body .koran-photo { margin: 0 0 1.25rem; text-align: left; }
    .koran-body .koran-photo img { width: 100%; max-width: none; height: auto; max-height: 14rem; object-fit: cover; margin: 0; display: block; }
    .koran-body .koran-photo figcaption {
        font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        font-size: .7rem; font-style: italic; color: #78716c; line-height: 1.4; margin-top: .35rem;
    }
    @media (min-width: 640px) {
        .koran-body .koran-photo { float: right; width: 15rem; margin: .35rem 0 1rem 1.75rem; }
        .koran-body .koran-photo img { max-height: 18rem; }
    }
    .koran-body a { color: #0369a1; text-decoration: underline; }
    .koran-body table { width: 100%; border-collapse: collapse; margin-bottom: 1.1rem; font-size: .95rem; }
    .koran-body th, .koran-body td { border: 1px solid #d6d3d1; padding: .4rem .6rem; text-align: left; }
</style>
