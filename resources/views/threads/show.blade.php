@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/vendor/jquery.atwho.css") }}">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-8">
                    @include('threads._body')
                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>

                </div>
                <div class="col-md-4">
                    <p>This thread was published at {{ $thread->created_at->diffForHumans() }}
                        by {{ $thread->owner->username }}
                        .</p>

                    <p><span v-text="repliesCount"></span> comments left.</p>

                    <subscribe-button :subscribed="{{ json_encode($thread->isSubscribed) }}"></subscribe-button>
                    <button class="btn" :class="[locked ? 'btn-primary' : 'btn-default']" v-if="authorize('isAdmin')"
                            @click="toggleLock"
                            v-text="locked ? 'Unlock' : 'Lock'">
                    </button>
                    <button class="btn btn-default" :class="[pinned ? 'btn-primary' : 'btn-default']"
                            v-if="authorize('isAdmin')" @click="togglePin"
                            v-text="pinned ? 'Unpin' : 'Pin'">
                    </button>
                </div>
            </div>
        </div>
    </thread-view>
@endsection