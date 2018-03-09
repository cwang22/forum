@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="page-header">
            <avatar-form :user="{{ $profileUser }}"></avatar-form>
        </div>

        @forelse ($activities as $date => $activity)
            <h4 class="mt-4"> {{$date}}</h4>
            @foreach($activity as $record)
                @if(view()->exists("profiles.activities.{$record->type}"))
                    @include("profiles.activities.{$record->type}", ['activity' => $record])
                @endif
            @endforeach
        @empty
            <p>There are no relevant results at this time.</p>
        @endforelse
    </div>
@endsection