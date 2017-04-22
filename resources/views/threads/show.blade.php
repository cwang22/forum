@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{$thread->title}}</div>

                <div class="panel-body">
                    <p>{{$thread->body}}</p>
                </div>
            </div>
            @foreach($thread->replies as $reply)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#">{{$reply->owner->name}}</a> said {{$reply->created_at->diffForHumans()}}</div>

                <div class="panel-body">
                    <p>{{$reply->body}}</p>
                </div>
            </div>
            @endforeach
            
            @if(auth()->check())
                <h4>New Reply</h4>
                <form class="form" action="{{ $thread->path() . '/replies' }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <textarea name="body" class="form-control" cols="30" rows="10" placeholder="do you have something to say?"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="post" class="btn pull-right">
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
