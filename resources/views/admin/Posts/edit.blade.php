@extends('layouts.master')
@section('content')
<!--style-->
@section('style')
<link rel="stylesheet" href="{{asset('assets/vendors/iconly/bold.css')}}">
@stop
<!--/style-->
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Posts</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->previous() }}">Posts</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Post</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('posts.update', $post) }}" method="POST" class="form">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    
                                    <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="title-column">Title</label>
                        
                    <input type="text" id="title-column" value="{{ old('title', $post->title) }}" class="form-control @error('title') is-invalid @enderror" placeholder="Title" name="title">
                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="description-column">Description</label>
                        
                    <textarea name="description" id="description-column" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ old('description', $post->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                        <a href="{{ url()->previous() }}" class="btn btn-light-secondary me-1 mb-1">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>
@section('scripts')

@stop
@endsection