<!-- get in touch -->
<section class="get-in-touch" style="background: url({{asset('assets/home/images/get-in-touch-bgs.jpg')}})">
    <div class="container-fluid container-width">
        <div class="row">
            <div class="col-lg-7 d-flex justify-content-center align-items-center">
                <div class="get-in-left">
                    <div class="map-lottie">
                        <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_tdnhlnff.json" speed="1" loop autoplay></lottie-player>
                    </div>
                    <div class="small-logo-mustafai">
                        <img loading="lazy" src="{{asset('assets/home/images/small-logo.png')}}" />
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-flex justify-content-lg-start justify-content-center">
                <div class="contact-form-wraper">
                    <form class="contact-form" id="contact-formm">

                        <h3 class="text-center mb-4">{{ __('app.get-in-touch') }}</h3>
                        <label id="name-error" class="error" for="name"></label>
                        <div class="form-floating mb-4">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" value="{{ optional(auth()->user())->user_name}}" required />
                            <label for="name">{{ __('app.your-name') }}</label>
                        </div>
                        <label id="email-error" class="error" for="email"></label>
                        <div class="form-floating mb-4">
                            <input type="email" name="email" id="email" class="form-control"  placeholder="Your email" value="{{ optional(auth()->user())->email}}" required/>
                            <label for="email">{{__ ('app.enter-email')}}</label>
                        </div>
                        <label id="subject-error" class="error" for="subject"></label>
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" name="subject" id="subject" required placeholder="Subject:" />
                            <label for="subject">{{__('app.your-subject')}}</label>
                        </div>
                        <label id="message-error" class="error" for="message"></label>
                        <div class="form-floating">
                            <textarea class="form-control" name="message" placeholder="Leave a comment here" id="message" style="height: 100px" required></textarea>
                            <label for="message">{{__('app.how-may-we-help-you?')}}</label>
                        </div>
                        <div class="d-flex mt-md-5 mt-3 justify-content-end">
                            <button class="green-hover-bg theme-btn" id="contact_form_btn">{{__('app.send-message')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
