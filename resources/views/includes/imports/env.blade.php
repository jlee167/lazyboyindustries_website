<script>
    window.env = Object.freeze({
        CSRF_TOKEN      : "{{ csrf_token() }}",
        STREAM_URL      : "{{ env('APP_URL', null) }}",
        STREAM_PORT     : "{{ env('STREAM_PORT', null) }}",
        GOOGLE_APP_KEY  : "{{ env('GOOGLE_APP_KEY', '') }}",
        KAKAO_APP_KEY   : "{{ env('KAKAO_JAVASCRIPT_KEY', '') }}",
        REDIRECT_URL    : "{{ redirect()->intended()->getTargetUrl() }}",
    });
</script>
