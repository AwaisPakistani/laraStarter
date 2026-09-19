@extends('layout.master')
@section('content')
<!--style-->
@section('style')
<link rel="stylesheet" href="{{asset('assets/vendors/iconly/bold.css')}}">
@stop
<!--/style-->

<div class="page-heading">
                <h3>Profile Statistics</h3>
</div>
<div class="page-content">
    <section class="row">

    </section>
</div>
@section('scripts')
 <script src="{{asset('assets/vendors/apexcharts/apexcharts.js')}}"></script>
<script src="{{asset('assets/js/pages/dashboard.js')}}"></script>
@stop
@endsection
