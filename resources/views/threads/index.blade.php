@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                @include('threads._list')
            </div>

            <div class="col-md-4">
                <h4 class="text-uppercase">Trending</h4>
                @if(count($trending))
                    <ul class="list-group">
                        @foreach($trending as $thread)
                            <li class="list-group-item">
                                <a href="{{ $thread->path }}">{{ $thread->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
