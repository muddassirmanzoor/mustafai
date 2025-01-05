<!-- events -->
<section class="events">
    <div class="container-fluid container-width">
        <div class="row">
            <div class="absolute-frame-image">
                <img loading="lazy" src="{{ getS3File('assets/home/images/event-bg.png') }}" />
            </div>
            @include('home.home-sections.prayer-time')
            <div class="col-xl-6 upcoming-functions">
                <div class="d-flex justify-content-between px-4 mb-5">
                    <h4>{{ __('app.mustafai-events') }}</h4>
                </div>
                <div class="upcoming-event-slider">
                    <div class="navigation-button text-right">
                        @if ($events->count() > 1)
                            <button class="owl-prev prev"></button>
                            <button class="owl-next next"></button>
                        @endif
                    </div>
                    <div class="event-slides">
                        @forelse($events as $event)
                            <div class="event-item" data-id-="1">
                                <h4>{{ $event->title }}</h4>
                                <hr />
                                <div class="d-flex justify-content-between align-items-center ramadan-workshop-time">
                                    <div class="d-flex flex-sm-row flex-column">
                                        <div class="d-flex align-items-center calander-area">
                                            <i class="fa fa-calendar m-2 text-blue" aria-hidden="true"></i>
                                            <p class="small-text">{{ __('app.date') }}</p>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <strong>
                                                {{-- <p class="graish-color">{{ \Illuminate\Support\Carbon::parse($event->start_date_time)->format('d M Y') }}</p> --}}
                                                <p class="graish-color">
                                                    @php
                                                        if (lang() == 'urdu') {
                                                            setlocale(LC_TIME, 'ur_PK.UTF-8');
                                                            $date = \Illuminate\Support\Carbon::parse($event->start_date_time);
                                                            $urdu_date = strftime('%e %B %Y', $date->timestamp);
                                                            setlocale(LC_TIME, null);
                                                            echo $urdu_date;
                                                        } else {
                                                            echo \Illuminate\Support\Carbon::parse($event->start_date_time)->format('d M Y');
                                                        }
                                                    @endphp
                                                </p>
                                            </strong>
                                            {{-- <p class="small-text">Dhuʻl-Qiʻdah 3, 1443 AH</p> --}}
                                            <p class="small-text">{{ new \App\Http\Controllers\Hijri\HijriDateTime($event->start_date_time) }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center flex-sm-row flex-column justify-content-sm-end justify-content-center flex- me-3 event-location-detail">
                                        <div class="d-flex at-locate align-items-center">
                                            <i class="fa fa-map-marker m-2 text-blue" aria-hidden="true"></i>
                                            <p>{{ __('app.location') }}</p>
                                        </div>
                                        <div class="d-flex event-name-detail">
                                            <strong>
                                                <p class="small-text graish-color">{{ $event->location }}</p>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="inner-content event_btn_row">
                                    <div id="owl-eventslider" class="post-slider owl-carousel owl-theme">
                                        @foreach($event->images as $image)
                                            <div class="event-item-wraper">
                                                <a href="{{getS3File($image->image)}}" target="_blank">
                                                    <img src="{{getS3File($image->image)}}" alt="event image" class="img-fluid">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- <p class="graish-color">{!! $event->content !!}.</p> -->
                                    {{-- <p class="graish-color ">{{ mb_strimwidth($event->content, 0, 350, '.......') }}. </p> --}}
                                </div>

                                {{-- <div class="d-flex justify-content-end align-items-end">
                                <a href="{{ route('event.detail', hashEncode($event->id)) }}">{{__('app.read-more')}}</a>
                            </div> --}}

                                <div class="inner-content">
                                </div>
                                @if (date('Y-m-d H:i:s') < date($event->start_date_time))
                                    <div id="countdown" class="wrap-countdown mercado-countdown mt-2" data-countdown="{{ date($event->start_date_time) }}"></div>
                                @endif
                                <div class="d-flex justify-content-end align-items-end event_btn_row mt-lg-2 mt-3">
                                    <button data-event-id="{{ $event->id }}" data-toggle="modal" data-target="#eventModal" type="button" class="green-hover-bg theme-btn event_button join_event_button">{{ __('app.join-this-event') }}
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p>{{ __('app.no-event') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mt-xl-0 mt-5 posts_section">
                <div class="d-flex justify-content-center mb-lg-5 mb-3 time-lines-head">
                    <h4 class="text-center">{{ __('app.mustafai-timelines') }}</h4>
                </div>
                <div class="time-lines">
                    <!-- <div class="time-lines-head">
                        <h5 class="text-center">{{ __('app.mustafai-timelines') }}</h5>
                    </div> -->
                    <div class="time-lines-body timeline_posts" id="timeline_posts">
                        <input id="user-id" type="hidden" value="{{ Auth::check() ? Auth::id() : '' }}">
                        @forelse($posts as $post)
                            {{-- @if ($post->job_type != 2) --}}
                            @php
                                $isLikeExists = $post
                                    ->likes()
                                    ->where('ip', \Request::getClientIp())
                                    ->exists();
                            @endphp
                            <div class="post-1">
                                <div class="profile-area d-flex align-items-center">
                                    <div class="profile-photo mx-1">
                                        <img loading="lazy" src="{{ getS3File($post->user->profile_image ?? $post->admin->profile) }}" alt="image not found" class="start-a-post-profile img-fluid" style="width: 35px;height: 35px" />
                                    </div>
                                    <div class="pr-name">
                                        <strong>{{ $post->admin->name ?? $post->user->{'user_name_'.app()->getLocale()} }}</strong>
                                    </div>
                                </div>
                                <div class="deatil-time mt-2">
                                    <p class="small-text graish-color post_title {{ $post->lang == 'urdu' ? 'ur-direction' : 'en-direction' }}" data-post-title-id="{{ $post->id }}" data-post-detail="{{ $post->title }}" data-splitted-post-detail="{{ Str::length($post->title) >= 350 ? Str::limit($post->title, 350, '') : '' }}">{!! Str::length($post->title) >= 350 ? Str::limit($post->title, 350, ' ...<a href="javascript:void(0)" onclick="togglePostTitle(this)" id="' . $post->id . '">' . __('app.read-more') . '</a>') : $post->title !!}</p>
                                    <!-- if post is product post-->
                                    @if ($post->post_type == '4')
                                        <div class="d-flex justify-content-end align-items-end mb-3"><a data-product-id="{{ $post->product_id }}" href="javascript:void(0)" class="btn chat-user-name" data-toggle="modal" data-target="#shopNowModal" onclick="shopProduct(this)">{{ __('app.shop_now') }}</a>
                                        </div>
                                    @endif

                                    <!--if post is job hiring post-->
                                    @if ($post->post_type == '2' && $post->job_type == '1')
                                        <table class="table" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __('app.company-name') }} :</th>
                                                    <td>{{ $post->hiring_company_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('app.experience') }} :</th>
                                                    <td>{{ $post->experience ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('app.profession') }} :</th>
                                                    <td>{{ $post->occupation ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('app.skills') }} :</th>
                                                    <td>{{ $post->skills ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('app.description') }} :</th>
                                                    <td>{{ $post->description_english ?? '---' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                    <!--if post is job hiring post-->
                                    @if ($post->post_type == '2' && $post->job_type == 1)
                                        <div class="d-flex justify-content-end align-items-end mb-3"><a data-post-id="{{ $post->id }}" href="javascript:void(0)" class="btn chat-user-name" data-toggle="modal" data-target="#applyJobModal" onclick="applyNow(this)">{{ __('app.apply-now') }}</a>
                                        </div>
                                    @endif
                                    <!--if post is job seeking post-->
                                    @if ($post->post_type == '2' && $post->job_type == 2)
                                        <table class="table" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __('app.occupation') }}:</th>
                                                    <td>{{ $post->occupation }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('app.experience') }}:</th>
                                                    <td>{{ $post->experience }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('app.skills') }}:</th>
                                                    <td>{{ $post->skills }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('app.cv-resume') }}:</th>
                                                    <td><a href="{{ getS3File($post->resume) }}" target="_blank">{{ __('app.download-resume') }}</a></td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('app.description') }}:</th>
                                                    <td>{{ $post->description_english }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                    <!--if post is Blood post-->
                                    @if ($post->post_type == '5')
                                        <table class="table" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __('app.city') }}</th>
                                                    <td>{{ $post->citi->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('app.hospital') }}</th>
                                                    <td>{{ $post->hospital }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('app.address') }}</th>
                                                    <td>{{ $post->address }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                    @if (count($post->images))
                                        <div class="post-slider owl-carousel owl-theme timeline_carousal">
                                            @foreach ($post->images as $image)
                                                @php
                                                    $image_extensions = ['jpg', 'jpeg', 'png', 'bmp', 'svg', 'gif', 'webp'];
                                                    $video_extensions = ['flv', 'mp4', 'm3u8', 'ts', '3gp', 'mov', 'avi', 'wmv'];
                                                    $get_mimes = \File::extension($image->file);
                                                @endphp
                                                @if (in_array($get_mimes, $image_extensions))
                                                    <div class="item">
                                                        <img loading="lazy" src="{{ getS3File($image->file) }}" alt="image">
                                                    </div>
                                                @endif
                                                @if (in_array($get_mimes, $video_extensions))
                                                    <div class="item">
                                                        <video id="myvideo" preload="none" width="100%" height="230px" type="video/{{ $get_mimes }}" muted controls>
                                                            <source src="{{ getS3File($image->file) }}">
                                                        </video>
                                                    </div>
                                                @endif
                                            @endforeach

                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3 social-system">
                                    <div class="d-flex align-items-center">
                                        <span><i style="cursor: pointer" data-post-id="{{ hashEncode($post->id) }}" class="fa fa-thumbs-up me-2 like-icon {{ $isLikeExists ? 'text-green' : 'text-secondary' }}" aria-hidden="true"></i></span>
                                        <p data-likes-counter="{{ hashEncode($post->id) }}" class="small-text graish-color">{{ $post->likes_count }} {{ $post->likes_count > 1 ? __('app.likes') : __('app.like') }}</p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a data-post-id="{{ hashEncode($post->id) }}" class="small-text graish-color cri-pointer show comment_button read_comments" id="show">
                                            <span> <i class="fa fa-comment text-blue me-2" aria-hidden="true"></i></span>
                                            <span data-comments-counter="{{ hashEncode($post->id) }}">{{ $post->comments_count }} {{ $post->comments_count > 1 ? __('app.comments') : __('app.comment') }}</span>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div data-share-div="{{ $post->id }}" class="drop-down">
                                            <div id="dropDown" class="drop-down__button">
                                                <a data-post-id="{{ $post->id }}" class="share_button small-text graish-color">
                                                    <span><i class="fa fa-share text-green me-2" aria-hidden="true"></i></span>{{ __('app.share') }}</a>
                                            </div>
                                            <div class="drop-down__menu-box">
                                                <ul class="drop-down__menu">
                                                    {{-- <li data-name="profile" class="drop-down__item">Your Profile</li> --}}
                                                    <li id="shareFacebookBtn" data-name="dashboard" class="drop-down__item shareFacebookBtn">
                                                        <span class="fa fa-facebook m-2"></span>
                                                        <a class="twitter-share-button" target="_blank" href="https://www.facebook.com/dialog/share?app_id=656385503002340&display=popup&href={{ route('user.specific-post', hashEncode($post->id)) }}&redirect_uri={{ route('user.specific-post', hashEncode($post->id)) }}">{{ __('app.facebook') }}</a>
                                                    </li>
                                                    <li data-name="activity" class="drop-down__item">
                                                        <span class="fa fa-twitter m-2"></span>
                                                        <a class="twitter-share-button" target="_blank" href="https://twitter.com/intent/tweet?text={{ route('user.specific-post', hashEncode($post->id)) }}">{{ __('app.twitter') }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div data-comment-div="{{ hashEncode($post->id) }}" class="addcomment mt-3 comment_div" style="display: none">
                                    {{-- <a data-post-id="{{ $post->id }}" class="small-text graish-color cri-pointer read_comments">read comments</a> --}}
                                    <ul data-comments-list="{{ hashEncode($post->id) }}" class="comments_list" style="display: none">
                                        @if ($post->comments_count > 0)
                                            @foreach ($post->comments as $comment)
                                                <li>
                                                    <b>{{ $comment->user->user_name ?? 'guest' }}</b> {{ $comment->body }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    <div class="d-flex relative mt-3 add-comments">
                                        <input data-comment-id="{{ hashEncode($post->id) }}" class="form-control form-control-lg  comment_input" type="text" placeholder="add a comment..." aria-label=".form-control-lg example" />
                                        <span data-comment-id="{{ hashEncode($post->id) }}" class="send-msg btn-primary send_comment">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            {{-- @endif --}}
                        @empty
                            <p class="text-center">No posts yet!</p>
                        @endforelse
                        <!-- shows loader after page reaches at bottom -->
                        <div class="ajax-load text-center" style="display:none">
                            <h5>Loading More post</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade library-detail common-model-style" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-green" id="exampleModalLabel">{{ __('app.join-this-event') }}</h5>
                <button type="button" class="btn-close close close_event_modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <input type="email" value="{{ optional(auth()->user())->email }}" placeholder="{{ __('app.your-email') }}" class="emailEvent form-control attende_email" name="user_email">
                <input type="hidden" class="attende_id" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="submit_event green-hover-bg theme-btn">{{ __('app.join-this-event') }}</button>
            </div>
        </div>
    </div>
</div>


@include('home.partial.shop-product-modal')
@include('home.partial.apply-job-modal')
@push('footer-scripts')
    {{--    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    @include('home.home-sections.event-section-script')
    @include('home.partial.shop-product-script')
    @include('home.partial.apply-job-script')

    <script>
        function togglePostTitle(_this, hide = false) {
            let id = $(_this).attr('id')
            let titleElement = $('[data-post-title-id="' + id + '"]')

            if (hide) {
                titleElement
                    .text(titleElement.attr('data-splitted-post-detail'))
                    .append(`<a href='javascript:void(0)' onclick='togglePostTitle(this, false)' id=${id}>{{ __('app.read-more') }}</a>`);
                return 1;
            }

            titleElement.
            text(titleElement.attr('data-post-detail')).
            append(`<a href='javascript:void(0)' onclick='togglePostTitle(this, true)' id=${id}>{{ __('app.read-less') }}</a>`)
            return 1
        }
    </script>
@endpush
