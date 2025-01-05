<!-- DONATION FOR THE NOBEL CAUSES -->
<section class="donations">
    <div class="container-fluid container-width">
        <div class="row">
            <div class="col-lg-9">
                <h3>{{ __('app.donation-for-the-nobel-causes') }}</h3>
                <div class="row mt-4">
                    <div class="col-lg-4">
                        <div class="charity-box-lottie">
                            <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_le5ncsdc.json" speed="1" loop autoplay></lottie-player>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="charity-content" id="feature-magzine-section">
                            <div class="charity-head d-flex">
                                <h4 class="text-red">
                                    "Give charity without delay, for it stands in the way of calamity."
                                    <span class="graish-color">( AL-TIRMIDHI, HADITH 589 )</span>
                                </h4>
                            </div>
                            <p class="text-donars-1 graish-color">Nam fermentum, ipsum in suscipit pharetra, mi odio
                                aliquet neque, non iaculis augue elit et libero. Phasellus tempor faucibus faucibus. Sed
                                eu mauris sem. Etiam et varius felis. Maecenas interdum lorem eleifend orci aliquam
                                mollis. Aliquam non rhoncus magna.</p>
                            <div class="avg-amounts">
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex flex-column">
                                        <p class="graish-color">Amount Funded</p>
                                        <h5 class="text-blue">PKR 245,465</h5>
                                    </div>
                                    <div class="d-flex flex-column d-none">
                                        <p class="graish-color">Total Amount Required</p>
                                        <h5 class="text-blue text-center">PKR 1,000,000</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-md-start justify-content-center align-items-md-start align-items-center mt-xl-4 mt-2">
                                <button class="blue-hover-bg theme-btn" href="/">donate now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="magazine" style="display: none" id="feature-product-section">
                    {{-- <h4 class="text-captilize mb-4">our magazine</h4>
                    <div class="download-book">
                        <div class="image-contain">
                            <img src="{{ asset('assets/home/images/book-look.png') }}" alt="image not found" class="img-fluid" />
                </div>
                <div class="team-info d-flex justify-content-center align-items-center">
                    <button class="theme-btn" href="/">download</button>
                </div>
            </div> --}}
        </div>
    </div>
    </div>
    </div>
</section>
@include('home.modals.donate-now')
@include('home.modals.order-product')
{{--@push('footer-scripts')
    @include('home.scripts.store-script')
    <script>

    </script>
@endpush--}}