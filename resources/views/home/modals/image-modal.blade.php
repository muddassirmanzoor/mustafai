<!-- Modal -->
<div class="modal fade library-detail common-model-style" id="libImageModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-green">{{__('app.details')}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-img">
                <div class="d-flex justify-content-between img-model-div" id="img-model-div"  style="display : none ">
                    <div class= "d-flex justify-content-center align-items-center">
                       <a href="javascript:void(0)" class="move-lib lib-next-btn" id="prev" onclick="moveLib($(this),'prev')">
                        <!-- {{__('app.prev-lib')}} -->
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="d-flex ps-3 pe-3">
                        <div class="image-outer-wrap">
                            <img loading="lazy" style="width: 100%" src="" id="lib-modal-pic">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="javascript:void(0)" class="move-lib lib-prev-btn" id="next" onclick="moveLib($(this),'next')">
                            <!-- {{__('app.next-lib')}} -->
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>

                    </div>
                </div>

                <div class="" id="video_1" style="display: none">
                    <iframe allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" controls width="100%" height="245" id="video_tag" src="">
                    </iframe>
                </div>
                <div class="" id="audio_1" style="display: none">
                        <audio controls width="100%" id="audio_tag" src="">
                        </audio>
                </div>
                <div class="row mt-2 desc-model-div" style="display: none">
                    <div class="col-12">
                        <p class="text-center" id="lib-modal-desc">

                        </p>
                    </div>
                </div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="theme-btn" data-bs-dismiss="modal" aria-label="Close">{{__('app.close')}}</button>
            </div> --}}
        </div>
    </div>
</div>
