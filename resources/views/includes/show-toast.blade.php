@if(session()->has('status'))
    <script src="{{ asset("assets/admin/js/pages/components/extended/toastr.js") }}" type="text/javascript"></script>
    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        @if( session('status') === 'success' )
        toastr.success("{{ session('message') }}");
        @elseif( session('status') === 'error')
        toastr.error("{{ session('message') }}");
        @endif
        {{ Session::forget('status') }}
    </script>
@endif
