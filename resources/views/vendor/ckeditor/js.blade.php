@if($instanceCount == 1)
    <script src="{{ asset('vendor/jeroennoten/laravel-ckeditor/resources/assets/ckeditor.js') }}"></script>
@endif
@if($name)
    <script>CKEDITOR.replace({!! json_encode($name) !!}, {!! json_encode($config) !!});</script>
@endif