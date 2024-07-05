@include('Superadmin.layouts.sidebar')
@include('Superadmin.layouts.header')
{{-- @include('Superadmin.layouts_baru.content') --}}

<main>
    @yield('container') <!-- Ini adalah tempat untuk konten yang akan digantikan -->
</main>
@include('Superadmin.layouts.footer')
