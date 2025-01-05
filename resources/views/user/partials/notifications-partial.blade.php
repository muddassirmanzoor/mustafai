@foreach($notifications as $notification)
    <a href="{{ $notification->link }}">
        <div class="notifications-item"> <img src="{{ (!empty($notification->profile)) ? url($notification->profile) : asset('images/avatar.png') }}" alt="img">
            <div class="text d-flex flex-column flex-grow-1 mr2">
                <h4>{{ $notification->title }}</h4>
            </div>
            <div class="d-flex align-items-center">
                <span class="date">{{ $notification->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </a>
@endforeach
