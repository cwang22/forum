@extends('layouts.app')

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
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

                                @can('update', $thread)
                                    <form action="{{ $thread->path() }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button class="btn btn-link">Delete Thread</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        <div class="panel-body">
                            <p>{{$thread->body}}</p>
                        </div>
                    </div>

                    <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>

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
                    <p><span v-text="repliesCount"></span> comments left.</p>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
