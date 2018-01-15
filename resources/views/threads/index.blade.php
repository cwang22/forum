@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._list')
            </div>

            <div class="col-md-4">
                <form action="/threads/search">
                    <div class="form-group">
                        <input type="text" class="form-control" name="q">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-default">Search</button>
                    </div>
                </form>

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
