@php
    $cols = array_merge(getQuery(App::getLocale(), ['title']), ['notifications.id', 'notifications.created_at', 'notifications.link']);
@endphp

@forelse(auth()->user()->notifications()->select($cols)->take(5)->get() as $notification)
    <li class="{{ $notification->pivot->is_read == 0 ? 'item unread' : 'read' }}">
        <a class="dropdown-item" href="{{ $notification->link }}">
            <div class="d-flex justify-content-between">
                <span class="details title">{{ $notification->title }}.</span>
                <span class="date">
                    {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>
            @if(auth()->user()->notifications()->count() > 0)
                <a class="d-flex justify-content-end align-items-end" href="javascript:void(0)"
                   data-notification-id="{{ $notification->id }}" onclick="readNotification(this)">{{ __('app.mark-as-read') }}</a>
                </a>
            @endif
    </li>
@empty
    <p class="w-100 text-center">{{ __('app.no-notification') }}</p>
@endforelse
