@foreach($posts as $post)
    {{-- @if($post->job_type != 2) --}}
        @php
            $isLikeExists = $post->likes()->where('ip', \Request::getClientIp())->exists();
        @endphp
        <div class="post-1">
            <div class="profile-area d-flex align-items-center">
                <div class="profile-photo mx-1">
                    <img src="{{ getS3File($post->user->profile_image ?? $post->admin->profile) }}" alt="image not found" class="img-fluid" style="width: 40px;height: 40px"/>
                </div>
                <div class="pr-name">
                    <h6>{{ $post->admin->name ?? $post->user->user_name }}</h6>
                </div>
            </div>

            <div class="deatil-time mt-2">
                <p class="small-text graish-color post_title {{ $post->lang=='urdu' ? 'ur-direction'  : 'en-direction' }}" data-post-title-id="{{ $post->id }}" data-post-detail="{{ $post->title }}" data-splitted-post-detail="{{ Str::length($post->title) >=350 ? Str::limit($post->title, 350, '') : '' }}">{!! Str::length($post->title) >=350 ? Str::limit($post->title, 350, ' ...<a href="javascript:void(0)" onclick="togglePostTitle(this)" id="'.$post->id.'">'.__('app.read-more').'</a>') : $post->title !!}</p>
                <!-- if post is product post-->
                @if($post->post_type == '4')
                    <div class="d-flex justify-content-end align-items-end mb-3"><a data-product-id="{{ $post->product_id }}" href="javascript:void(0)" class="btn chat-user-name" data-toggle="modal" data-target="#shopNowModal" onclick="shopProduct(this)">{{ __('app.shop_now') }}</a>
                    </div>
                @endif

                <!--if post is job hiring post-->
                @if ($post->post_type == '2' && $post->job_type=='1')
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
                @if ($post->post_type == '2' && $post->job_type==1)
                <div class="d-flex justify-content-end align-items-end mb-3"><a
                        data-post-id="{{ $post->id }}" href="javascript:void(0)"
                        class="btn chat-user-name" data-toggle="modal" data-target="#applyJobModal"
                        onclick="applyNow(this)">{{ __('app.apply-now') }}</a>
                </div>
            @endif
            <!--if post is job seeking post-->
            @if ($post->post_type == '2' && $post->job_type==2)
            <table class="table" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <th>{{__('app.occupation')}}</th>
                        <td>{{ $post->occupation }}</td>
                    </tr>
                    <tr>
                        <th>{{__('app.experience')}}</th>
                        <td>{{ $post->experience }}</td>
                    </tr>
                    <tr>
                        <th>{{__('app.skills')}} </th>
                        <td>{{ $post->skills }}</td>
                    </tr>
                    <tr>
                        <th>{{__('app.cv-or-resume')}}</th>
                        <td><a href="{{ getS3File($post->resume) }}" target="_blank">{{ __('app.download-resume') }}</a></td>
                    </tr>
                    <tr>
                        <th>{{__('app.description')}}</th>
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
                            <th>{{__('app.city')}}:</th>
                            <td>{{ $post->citi ? $post->citi->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{__('app.hospital')}}</th>
                            <td>{{ $post->hospital }}</td>
                        </tr>
                        <tr>
                            <th>{{__('app.address')}}</th>
                            <td>{{ $post->address }}</td>
                        </tr>
                    </tbody>
                </table>
                @endif
                @if(count($post->images))
                    <div id="owl-six" class="post-slider owl-carousel owl-theme dynamic_owl">
                        @foreach($post->images as $image)
                        @php
                        $image_extensions = ["jpg","jpeg","png","bmp","svg","gif","webp"];
                        $video_extensions = ["flv","mp4","m3u8","ts","3gp","mov","avi","wmv"];
                        $get_mimes=\File::extension($image->file)
                        @endphp
                        @if (in_array($get_mimes , $image_extensions))
                        <div class="item">
                        <img src="{{ getS3File($image->file) }}" class="img-fluid" alt="image">
                        </div>
                        @endif
                        @if (in_array($get_mimes , $video_extensions))
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
                    <span><i style="cursor: pointer" data-post-id="{{ hashEncode($post->id) }}"class="fa fa-thumbs-up me-2 like-icon {{ $isLikeExists ? 'text-green' : 'text-red' }}"aria-hidden="true"></i></span>
                    <p data-likes-counter="{{ hashEncode($post->id) }}"class="small-text graish-color">{{ $post->likes_count }} {{ ($post->likes_count>1)?__('app.likes'):__('app.like')}}</p>
                </div>
                <div class="d-flex align-items-center">
                    <a data-post-id="{{ hashEncode($post->id) }}" class="small-text graish-color cri-pointer show comment_button read_comments" id="show">
                        <span><i class="fa fa-comment text-blue me-2"aria-hidden="true"></i></span>
                        <span data-comments-counter="{{ hashEncode($post->id) }}">{{ $post->comments_count }} {{ ($post->comments_count>1)?__('app.comments'):__('app.comment')}}</span>
                    </a>
                </div>
                <div class="d-flex align-items-center">
                    <div data-share-div="{{ $post->id }}" class="drop-down">
                        <div id="dropDown" class="drop-down__button">
                            <a data-post-id="{{ $post->id }}"class="share_button small-text graish-color"><span><i class="fa fa-share text-green me-2" aria-hidden="true"></i></span>{{ __('app.share') }}</a>
                        </div>
                        <div class="drop-down__menu-box">
                            <ul class="drop-down__menu">
                                {{-- <li data-name="profile" class="drop-down__item">Your Profile</li>--}}
                                <li id="shareFacebookBtn" data-name="dashboard" class="drop-down__item shareFacebookBtn">
                                    <span class="fa fa-facebook m-2"></span>
                                    <a class="twitter-share-button" href="https://www.facebook.com/dialog/share?app_id=656385503002340&display=popup&href={{ route('user.specific-post', hashEncode($post->id)) }}&redirect_uri={{ route('user.specific-post', hashEncode($post->id)) }}">{{ __('app.facebook') }}</a>
                                </li>
                                <li data-name="activity" class="drop-down__item">
                                    <span class="fa fa-twitter"></span>
                                    <a class="twitter-share-button m-2"
                                       href="https://twitter.com/intent/tweet?text={{ route('user.specific-post', hashEncode($post->id)) }}">{{ __('app.twitter') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div data-comment-div="{{ hashEncode($post->id) }}" class="addcomment mt-3 comment_div"
                 style="display: none">
                {{-- <a data-post-id="{{ $post->id }}" class="small-text graish-color cri-pointer read_comments">read comments</a>--}}
                <ul data-comments-list="{{ hashEncode($post->id )}}" class="comments_list"
                    style="display: none">
                    @if($post->comments_count > 0)
                        @foreach($post->comments as $comment)
                            <li>
                                <b>{{ $comment->user->user_name ?? 'guest'  }}</b> {{ $comment->body }}
                            </li>
                        @endforeach
                    @endif
                </ul>
                <div class="d-flex relative mt-3">
                    <input data-comment-id="{{ hashEncode($post->id) }}" class="form-control form-control-lg  comment_input" type="text"placeholder="add a comment..." aria-label=".form-control-lg example"/>
                    <span data-comment-id="{{ hashEncode($post->id) }}"class="send-msg btn-primary send_comment"><i class="fa fa-paper-plane" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
        <hr/>
    {{-- @endif --}}
@endforeach
