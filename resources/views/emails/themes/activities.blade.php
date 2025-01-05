@extends('user.layouts.layout')

@section('content')
    @forelse($activities as $activity)
       <div class="dash-common-card mt-2 p-3 d-flex justify-content-between">
            <a href="{{ $activity->link }}"><strong >{{ ucfirst($activity->body) }}</strong></a> <br>
           <span>{{ $activity->created_at->diffForHumans() }}</span>
       </div>
    @empty
        <h4>No Activity yet!</h4>
    @endforelse
@endsection
