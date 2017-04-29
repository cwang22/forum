@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ route('profile', $thread->owner) }}">{{ $thread->owner->name }}</a> posted:
                                {{ $thread->title }}
                            </span>

                            <form action="{{ $thread->path() }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button class="btn btn-link">Delete Thread</button>
                            </form>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p>{{$thread->body}}</p>
                    </div>
                </div>

                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                {{$replies->links()}}

                @if(auth()->check())
                    <h4>New Reply</h4>
                    <form class="form" action="{{ $thread->path() . '/replies' }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="body" class="form-control" cols="30" rows="10"
                                      placeholder="do you have something to say?"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="post" class="btn pull-right">
                        </div>
                    </form>
                @endif
            </div>
            <div class="col-md-4">
                <p>This thread was published at {{ $thread->created_at->diffForHumans() }} by {{ $thread->owner->name }}
                    .</p>
                <p>{{ $thread->replies_count }} {{ str_plural('comment',$thread->replies_count) }} left.</p>
            </div>
        </div>
    </div>
@endsection
