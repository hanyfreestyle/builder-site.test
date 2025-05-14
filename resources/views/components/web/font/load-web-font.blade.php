<script>
    @if($arFont)
    async function loadarfont() {
        const font_ar = new FontFace('Tajawal', 'url({{ defPublicUrl('Fonts','lang-ar/TajawalRegular.woff2') }}');
        await font_ar.load();
        document.fonts.add(font_ar);
        document.body.classList.add('Tajawal');
    };
    loadarfont();

    @endif

    @if($enFont )
    async function loadarfont_en() {
        const font_en = new FontFace('Poppins', 'url({{ defPublicUrl('Fonts','lang-en/Poppins-Regular.woff2') }}');
        await font_en.load();
        document.fonts.add(font_en);
        document.body.classList.add('Poppins');
    };
    loadarfont_en();

    @endif

    @if($fontawesome)
    async function fontawesome() {
        const fontawesome = new FontFace('FontAwesome', 'url({{ defPublicUrl('Fonts','fontawesome/webfonts/fa-solid-900.woff2') }}');
        await fontawesome.load();
        document.fonts.add(fontawesome);
        document.body.classList.add('FontAwesome');
    };
    fontawesome();

    async function fontawesomeB() {
        const fontawesomeB = new FontFace('FontAwesomeB', 'url({{ defPublicUrl('Fonts','fontawesome/webfonts/fa-brands-400.woff2') }}');
        await fontawesomeB.load();
        document.fonts.add(fontawesomeB);
        document.body.classList.add('FontAwesomeB');
    };
    fontawesomeB();

    @endif

    @if($bootstrapIcons)
    async function bootstrapIcons() {
        const bootstrapIcons = new FontFace('bootstrapIcons', 'url({{ defPublicUrl('Fonts','bootstrap-icons/bootstrap-icons.woff2') }}');
        await bootstrapIcons.load();
        document.fonts.add(bootstrapIcons);
        document.body.classList.add('bootstrapIcons');
    };
    bootstrapIcons();
    @endif

</script>
