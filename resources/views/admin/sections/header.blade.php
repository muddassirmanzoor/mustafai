@php
     $admin= \App\Models\Admin\Admin::find(1);
@endphp
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Notifications Dropdown Menu -->
    @if(have_right('In-App-Notification'))
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">{{ $admin->unreadNotifications()->count() }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
    {{--        <span class="dropdown-item dropdown-header">{{ auth()->user()->notifications()->count() }} Notifications</span>--}}
            <div class="dropdown-divider"></div>
            @forelse($admin->notifications()->take(10)->get() as $notification)
                <a href="{{ $notification->link }}" class="dropdown-item" data-notification-id="{{ $notification->id }}" data-notification-link="{{ $notification->link }}" onclick="readNotification(this)">
                    {{ $notification->title }}
                    <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                </a>
                <div class="dropdown-divider"></div>
            @empty
                <p class="w-100 text-center">{{ __('app.no-notification') }}</p>
            @endforelse
            <a href="{{ route('admin.notifications') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
        </li>
    @endif
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <!-- <i class="far fa-bell"></i> -->
        <i class="fas fa-user-cog"></i>
      </a>
      <div class="dropdown-menu  site-settings-dropdown dropdown-menu-right">
        @if(have_right('View-Admin-Profile'))
          <a href="{{ URL('admin/profile') }}" class="dropdown-item">
            <i class="fas fa-user-alt mr-2"></i> Profile
          </a>
        @endif
        @if(have_right('Edit-Site-Setting'))
          <div class="dropdown-divider"></div>
          <a href="{{ URL('admin/site-setting') }}" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> Site Setting
          </a>
        @endif
        <div class="dropdown-divider"></div>
        <a href="{{ URL('admin/logout') }}" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Log Out
        </a>
      </div>
    </li>
  </ul>
</nav>
  <!-- /.navbar -->

@push('footer-scripts')
    <script>
        function readNotification(_this)
        {
            const notificationId = $(_this).attr('data-notification-id');
            const notificationLink = $(_this).attr('data-notification-link');

            $.ajax({
                type: "POST",
                url: "{{route('admin-notification.read')}}",
                data: {
                    '_token': "{{csrf_token()}}",
                    'id': notificationId,
                },
                success: function(result) {

                }
            });
        }
    </script>
@endpush
