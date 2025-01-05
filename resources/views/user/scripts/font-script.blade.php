<script>
$(document).ready(function() {
    pdfMake.fonts = {
        Arial: {
            normal: "{{ asset('assets/home/ttf/arial.ttf') }}",
            bold: "{{ asset('assets/home/ttf/arialbd.ttf') }}",
            italics: "{{ asset('assets/home/ttf/ariali.ttf') }}",
            bolditalics: "{{ asset('assets/home/ttf/arialbi.ttf') }}"
        }
    };
});
</script>
