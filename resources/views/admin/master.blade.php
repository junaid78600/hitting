@include('admin/layouts/header')

@include('admin/layouts/sidebar')

@include($content)

@include('admin/layouts/footer')

@stack('scripts')