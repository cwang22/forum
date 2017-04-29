@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="page-header">
            {{$profileUser->name}}
            <small>
                Since {{$profileUser->created_at->diffForHumans()}}
            </small>
        </h1>
        @forelse ($activities as $date => $activity)
            <h4 class="page-header"> {{$date}}</h4>
            @foreach($activity as $record)
                @include("profiles.activities.{$record->type}", ['activity' => $record])
            @endforeach
        @empty
            <p>There are no relevant results at this time.</p>
        @endforelse
    </div>
@endsection