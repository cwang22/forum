@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="page-header">
        {{$profileUser->name}}
        <small>
            Since {{$profileUser->created_at->diffForHumans()}}
        </small>
    </h1>

    @forelse ($threads as $thread)
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="level">
                       <span class="flex">
                            <a href="#">{{ $thread->owner->name }}</a> posted:
                           {{ $thread->title }}
                       </span>

                    <span>{{ $thread->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="panel-body">
                {{ $thread->body }}
            </div>
        </div>
    @empty
        <p>There are no relevant results at this time.</p>
    @endforelse


</div>
@endsection