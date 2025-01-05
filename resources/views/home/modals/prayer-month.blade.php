<div class="modal fade common-model-style namz-widget-timetable" id="monthly-model-div" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row w-100 align-items-center">
                    <div class="col-lg-6 col-4">
                        <h4 class="modal-title text-white" id="exampleModalLabel">{{ __('app.monthly-timetable') }}</h4>
                    </div>
                    <div class="col-lg-3 col-2">
                        <div class="namz-wdg-logo-">
                            <img loading = "lazy" src="{{asset('assets/home/images/namz-logo.png')}}" alt="image not found" class="img-fluid" />
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="d-flex justify-content-end">
                            <div class="d-flex flex-column">
                                <div class="img-sun-for-time d-flex flex-column align-items-center">
                                    <img loading = "lazy" src="{{asset('assets/home/images/morning-sun.png')}}" alt="image not found" class="img-fluid" />
                                    <p class="size-13 text-white text-center" id="monthly-sunrise" style="direction: ltr;">7:00 am</p>
                                </div>
                            </div>
                            <div class="d-flex flex-column ms-3">
                                <div class="img-sun-for-time d-flex flex-column align-items-center">
                                    <img loading = "lazy" src="{{asset('assets/home/images/evening-sun.png')}}" alt="image not found" class="img-fluid" />
                                    <p class="size-13 text-white text-center" id="monthly-sunset" style="direction: ltr;">5:00 pm</p>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="namaz-monthly">

            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="green-hover-bg theme-btn" data-bs-dismiss="modal" aria-label="Close">{{__('app.close')}}</button>
            </div> -->
        </div>
    </div>
</div>
