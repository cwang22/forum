<div class="card mb-4">
    <ul class="list-group list-group-flush">
        @forelse($threads as $thread)

            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-10">
                        <h3 class="h4">
                            <a class="text-dark" href="{{ $thread->path() }}">
                                @if($thread->pinned)
                                    <i class="fas fa-thumbtack"></i>
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
                    </div>
                    <div class="col-sm-2 d-flex">
                        <a class="ml-auto text-muted" href="{{ $thread->path() }}">
                            <i class="far fa-comment"></i> {{ $thread->replies_count }}
                        </a>
                    </div>
                </div>

                <div>{!! $thread->body !!}</div>
                <div class="mt-2 text-muted">
                    <a href="{{ route('profile', $thread->owner) }}">{{ $thread->owner->username }}</a>
                    posted {{ $thread->created_at->diffForHumans() }}.
                </div>
            </li>
        @empty
            <li>There are no relevant results at this time.</li>
        @endforelse
    </ul>
</div>
{{ $threads->render() }}