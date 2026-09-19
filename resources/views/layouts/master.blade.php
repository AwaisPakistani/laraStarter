<!DOCTYPE html>
<html lang="en">
<head>
   @include('layouts.head')
   @yield('style')
</head>
<body>
    <div id="app">

       @include('layouts.sidebar')
        <div id="main" class='layout-navbar'>
            @include('layouts.navbar')
            <div id="main-content">
                @yield('content')
                @include('layouts.footer')
            </div>
        </div>
       
    </div>
    @include('layouts.scripts')
    @yield('scripts')

</body>

</html>
