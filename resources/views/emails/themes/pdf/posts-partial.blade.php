@foreach($posts as $post)
    <div class="{{ $post->shared_user != null ? 'user-shared-post' : 'post-details-bg mb-3' }}">
        @if($post->shared_user != null)
            <div class="post-shared d-flex align-items-center">
                <div class="shared-profile">
                    <img class="start-a-post-profile" src="{{ getS3File($post->shared_user->profile_image) }}" alt="profile">
                </div>
                <div class="d-flex flex-column ms-3">
                    <h5>{{ availableField($post->shared_user->user_name, $post->shared_user->user_name_english, $post->shared_user->user_name_urdu, $post->shared_user->user_name_arabic) }}</h5>
                    <p>{{ $post->created_at->diffForHumans() }}</p>
                    @switch($post->post_type)
                        @case(2 && $post->job_type == 2) <span class="ribbon">{{ __('app.looking-for-job') }}</span> @break;
                        @case(2 && $post->job_type == 1) <span class="ribbon">{{ __('app.we-are-hiring') }}</span> @break;
                        @case(5)
                            <span class="ribbon">{{ __('app.blood-required') }}</span>
                        @break;
                    @endswitch
                </div>
            </div>
        @endif
        <div class="col-12  mb-3">
            <div>
                <div class="post-header">
                    <div class="d-flex align-items-center">
                        <figure class="mb-0 me-lg-3 me-2">
                            <img class="start-a-post-profile" src="{{ getS3File($post->user->profile_image ?? $post->admin->profile) }}" alt="" class="img-fluid">
                        </figure>
                        <div class="post-creator-profile w-100">
                            <h2 class="person-name text-capitalize">
                                <a style="color: black" href="{{ ($post->user_id != null || $post->user_id != '') ? route('user.profile', hashEncode($post->user_id)) : '#' }}">
                                    {{ $post->admin->name ?? availableField($post->user->user_name, $post->user->user_name_english, $post->user->user_name_urdu, $post->user->user_name_arabic) }}
                                </a>
                            </h2>
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex">
                                    <figure class="mb-0 me-2">
                                        <img src="./images/public-icon.png" alt="" class="img-flud">
                                    </figure>
                                    <span>{{ $post->user != null && $post->user->is_public == 0 ? __('app.private') : __('app.public') }}</span>
                                </div>
                                <div class="d-flex">
                                    @if($post->user_id == auth()->user()->id && $post->shared_user == null)
                                        @if(have_permission('Edit-News-Feed-Posts'))
                                            <span data-toggle="modal" data-target="#editPostModal" data-post-id="{{ $post->id }}" style="cursor: pointer" class="edit_post"><strong>...</strong></span>
                                        @endif
                                    @endif
                                    @switch($post->post_type)
                                        @case(2 && $post->job_type == 2) <span class="ribbon">{{ __('app.looking-for-job') }}</span> @break;
                                        @case(2 && $post->job_type == 1) <span class="ribbon">{{ __('app.we-are-hiring') }}</span> @break;
                                        @case(5) <span class="ribbon">{{ __('app.blood-required') }}</span> @break;
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="mb-lg-3 mb-2 post_title {{ $post->lang=='urdu' ? 'ur-direction'  : 'en-direction' }}" data-post-title-id="{{ $post->id }}" data-post-detail="{{ $post->title }}" data-splitted-post-detail="{{ Str::length($post->title) >=350 ? Str::limit($post->title, 350, '') : '' }}">{!! Str::length($post->title) >=350 ? Str::limit($post->title, 350, ' ...<a href="javascript:void(0)" onclick="togglePostTitle(this)" id="'.$post->id.'">'.__('app.read-more').'</a>') : $post->title !!}</p>
                <!-- if post is product post-->
                @if($post->post_type == '4')
                    <div class="d-flex justify-content-end align-items-end mb-3"><a data-product-id="{{ $post->product_id }}" href="{{ route('user.add-cart',['quantity' => '1','product_id'=>$post->product_id,'type'=> 'product_shop']) }}" type="submit" class="btn chat-user-name">{{ __('app.shop_now') }}</a>
                    </div>
                @endif

                <!--if post is job hiring post-->
                @if ($post->post_type == '2' && $post->job_type=='1')
                    <div class="d-flex justify-content-end align-items-end mb-3"><a
                            data-post-id="{{ $post->id }}" href="javascript:void(0)"
                            class="btn chat-user-name" data-toggle="modal" data-target="#applyJobModal"
                            onclick="applyNow(this)">{{ __('app.apply-now') }}</a>
                    </div>
                @endif

                @switch($post->post_type)
                    @case($post->post_type == '2' && $post->job_type=='1') <x-posts.show-job-hiring-post-component :post="$post" /> @break
                    @case($post->post_type == '2' && $post->job_type=='2') <x-posts.show-job-seeking-post-component :post="$post" /> @break
                    @case($post->post_type == '5') <x-posts.show-blood-post-component :post="$post" /> @break
                @endswitch

                <figure class="mb-0 post-img">
                    {{-- post media --}}
                    <x-posts.show-post-media-component :post="$post" />
                </figure>
                <div class="media-icon">
                    <ul>
                        @if(have_permission('Like-News-Feed-Posts'))
                            <li>
                                <a href="javascript:void(0)">
                                    <div class="d-flex align-items-center">
                                        <i data-likes-counter="{{ hashEncode($post->id) }}"
                                        class="fa fa-thumbs-up me-2 like_post {{ $post->is_like != null ? 'text-green' : '' }}"
                                        aria-hidden="true">
                                            <span>{{ $post->likes_count }} {{ ($post->likes_count>1)?__('app.likes'):__('app.like')}}</span>
                                        </i>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(have_permission('Add-Comment-News-Feed-Posts'))
                            <li>
                                <a href="javascript:void(0)">
                                    <div class="d-flex align-items-center">
                                        <i data-post-id="{{ hashEncode($post->id) }}"
                                        class="fa fa-comment me-2 comment_button read_comments" aria-hidden="true">
                                            <span
                                                data-comments-counter="{{ hashEncode($post->id) }}">{{ $post->comments_count }} {{ ($post->comments_count>1)?__('app.comments'):__('app.comment')}}
                                        </span>
                                        </i>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(have_permission('Share-News-Feed-Posts'))
                            <li>
                                <a href="">
                                    <div class="news-feed-share drop-down">
                                        <div id="dropDown_{{ $post->id }}" class="drop-down__button">
                                            <a>
                                                <span><i class="fa fa-share me-2" aria-hidden="true"></i></span>{{__('app.share')}}
                                            </a>
                                        </div>
                                        <div class="drop-down__menu-box">
                                            <ul class="drop-down__menu">
                                                <li data-name="profile">
                                                    <span class="fa fa-user-plus" aria-hidden="true"></span>
                                                    <a data-post-id="{{ hashEncode($post->id) }}" class="drop-down__item mustafai_timeline_share" href="javascript:void(0)">{{ __('app.profile') }}</a>
                                                </li>
                                                @if(have_permission('Share-Twitter-News-Feed-Posts'))
                                                    <li data-name="profile">
                                                        <span class="fa fa-twitter"></span>
                                                        <a class="drop-down__item" target="_blank" href="https://twitter.com/intent/tweet?text={{ route('user.specific-post', hashEncode($post->id)) }}">{{ __('app.twitter') }}</a>
                                                    </li>
                                                @endif
                                                @if(have_permission('Share-Facebook-News-Feed-Posts'))
                                                    <li data-name="profile">
                                                        <span class="fa fa-facebook"></span>
                                                        <a class="drop-down__item" target="_blank" href="https://www.facebook.com/dialog/share?app_id=656385503002340&display=popup&href={{ route('user.specific-post', hashEncode($post->id)) }}&redirect_uri={{ route('user.specific-post', $post->id) }}">{{ __('app.facebook') }}</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div data-comment-div="{{ hashEncode($post->id) }}" class="addcomment mt-3 comment_div"
                    style="display: none">
                    {{-- <a data-post-id="{{ $post->id }}" class="small-text graish-color cri-pointer read_comments">{{__('app.read-comments')}}</a>--}}
                    <ul data-comments-list="{{ hashEncode($post->id) }}" class="comments_list" style="display: none">
                        @if($post->comments_count > 0)
                            @foreach($post->comments as $comment)
                                <li data-comment-element="{{ $comment->id }}" class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <img class="start-a-post-profile" src="{{ getS3File($comment->user->profile_image) }}" alt="" class="img-fluid"> <b>{{ $comment->user->user_name ?? 'guest'  }}</b> {{ $comment->body }}
                                    </div>
                                    @can('update', $comment->user)
                                        <i data-comment-id="{{ hashEncode($comment->id) }}" onclick="deleteComment(this)" class="fa fa-trash dell-btn"></i>
                                    @endcan
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <div class="d-flex relative mt-3 add-comments">
                        <input data-comment-id-input="{{ hashEncode($post->id) }}"
                            class="form-control form-control-lg  comment_input" type="text"
                            placeholder="{{__('app.add-a-comment')}}..." aria-label=".form-control-lg example"/>
                        <span data-post-id="{{ hashEncode($post->id) }}" class="send-msg btn-primary send_comment">
                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
