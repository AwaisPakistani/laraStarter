<!DOCTYPE html>
<html lang="en">
<head>
   @include('layout.head')
   @yield('style')
</head>
<body>
    <div id="app">

       @include('layout.sidebar')
        <div id="main" class='layout-navbar'>
            @include('layout.navbar')
            <div id="main-content">
                @yield('content')
                @include('layout.footer')
            </div>
        </div>
       
    </div>
    @include('layout.scripts')
    @yield('scripts')

</body>

</html>
