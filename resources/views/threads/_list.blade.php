@forelse($threads as $thread)
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="h4">
                <a class="text-dark" href="{{ $thread->path() }}" >
                    @if($thread->pinned)
                        <span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>
                    @endif
                    @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                        <strong>
                            {{ $thread->title }}
                        </strong>
                    @else
                        {{ $thread->title }}
                    @endif
                </a>
            </h3>
            <div class="body">{!! $thread->body !!}</div>

            <a class="badge"></a>
        </div>

        <div class="card-footer">
            <span class="mr-2">
                 Posted By: <a href="{{ route('profile', $thread->owner) }}">{{ $thread->owner->username }}</a>
            </span>

            <span class="mr-2"  >
                {{ $thread->visits }}  {{ str_plural('visits', $thread->visits) }}
            </span>

            <span >
                <a href="{{ $thread->path() }}">
                {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
            </a>
            </span>
        </div>
    </div>
@empty
    <p>There are no relevant results at this time.</p>
@endforelse

{{ $threads->render() }}