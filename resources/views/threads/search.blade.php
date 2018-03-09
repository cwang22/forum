@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <ais-index app-id="{{ config('scout.algolia.id') }}" api-key="{{ config('scout.algolia.key') }}"
                       query="{{ request('q') }}"
                       index-name="threads">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <ul>
                                <ais-results>
                                    <template slot-scope="{ result }">
                                        <li>
                                            <a :href="result.path">
                                                <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                                            </a>
                                        </li>
                                    </template>
                                </ais-results>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Search
                        </div>
                        <div class="panel-body">
                            <ais-search-box>
                                <ais-input class="form-control" placeholder="Search..." autofocus="true"></ais-input>
                            </ais-search-box>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Filter By Channel
                        </div>
                        <div class="panel-body">
                            <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
                        </div>
                    </div>
                    <ul class="list-group">
                        @foreach($trending as $thread)
                            <li class="list-group-item">
                                <a href="{{ $thread->path }}">{{ $thread->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </ais-index>
        </div>
    </div>
@endsection
