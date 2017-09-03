@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Threads</div>

                    <div class="panel-body">
                        @forelse($threads as $thread)
                            <h2><a href="{{$thread->path()}}">
                                    @if($thread->hasUpdateForUser(auth()->user()))
                                        <strong>{{$thread->title}}</strong>
                                    @else
                                        {{$thread->title}}
                                    @endif
                                </a></h2>
                            <p>{{ $thread->body }}</p>
                        @empty
                            <p>There are no relevant results at this time.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
