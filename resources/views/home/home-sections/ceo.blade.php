<!-- message from Ameer -->
@if (!empty($ceoMessage))
    <section class="message-ceo">
        <div class="container-fluid container-width">
            <div class="row message-from-ameer justify-content-center align-items-center">
                <div class="col-lg-9 order-md-1 order-2">
                    <div class="ameer-content">
                        {!! $ceoMessage->message !!}
                        <div class="read-more-btn text-center">
                            <a class="green-hover-bg theme-btn ms-sm-5"
                                href="{{ url('/ceo-message') }}">{{ __('app.read-more') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 order-md-2 order-1">
                    <div class="ameer-photo">
                        <div class="ameer-frame-photo">
                            {{-- <img loading="lazy" src="{{ asset('assets/home/images/image-fram.png') }}" /> --}}
                            <div class="ceo-img">
                                {{-- <img src="./images/ameer-ceo.png" alt="image not found" class="img-fluid" /> --}}
                                <img loading="lazy" src="{{ getS3File($ceoMessage->image) }}" class="img-fluid" />
                            </div>
                        </div>
                        <div class="ceo-star-lottie">
                            <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_dsu2bmxn.json"
                                speed="1" loop autoplay></lottie-player>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
