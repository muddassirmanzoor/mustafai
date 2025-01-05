<ul>
    @foreach($notifications as $notification)
        <li>
            <a href="{{ $notification->link }}" class="dropdown-item" data-notification-id="{{ $notification->id }}" data-notification-link="{{ $notification->link }}" onclick="readNotification(this)">
                {{ $notification->title }}
                <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
            </a>
        </li>
    @endforeach
</ul>
