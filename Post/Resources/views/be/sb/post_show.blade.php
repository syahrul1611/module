@extends(config('app.be_layout').'.main')
@section('content')
    @if(session('error'))
        <div class="card shadow bg-danger text-white" style="margin-bottom:20px;">
            <div class="card-body" style="overflow-x:auto;padding:10px;">{!! session('error') !!}</div>
        </div>
    @endif
    @if(session('success'))
        <div class="card shadow bg-success text-white" style="margin-bottom:20px;">
            <div class="card-body" style="overflow-x:auto;padding:10px;">{!! session('success') !!}</div>
        </div>
    @endif
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $data->title }}</h1>
        <div class="d-none d-sm-inline-block">
            <a href="{{ route('admin.v1.access.post.index') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-fw fa-chevron-left"></i> Back
            </a>
        </div>
    </div>
    <!-- Content Row -->
    <div class="row p-2">
    <!-- Area Chart -->
        <div class="d-flex flex-column align-items-center">
            <img src="{{ route('admin.v1.access.post.file',$data->image) }}" class="w-50">
        </div>
        <span class="mt-3 mb-1">Category : {{ $data->category->name }}</span>
        <div class="container">
            {!! $data->content !!}
        </div>
    </div>
@endsection
