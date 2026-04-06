<!-- footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <h5>About Us</h5>
                <p>{{ $headerFooter->footer_content ?? 'We are passionate about providing high-quality gardening products and solutions to help you create beautiful, thriving green spaces.' }}</p>
                <div class="social-links">
                    @if($headerFooter->facebook_link) <a href="{{ $headerFooter->facebook_link }}" target="_blank"><i class="fab fa-facebook-f"></i></a> @endif
                    @if($headerFooter->twitter_link) <a href="{{ $headerFooter->twitter_link }}" target="_blank"><i class="fab fa-twitter"></i></a> @endif
                    @if($headerFooter->insta_link) <a href="{{ $headerFooter->insta_link }}" target="_blank"><i class="fab fa-instagram"></i></a> @endif
                    @if($headerFooter->linkedin_link) <a href="{{ $headerFooter->linkedin_link }}" target="_blank"><i class="fab fa-linkedin-in"></i></a> @endif
                    @if($headerFooter->youtube_link) <a href="{{ $headerFooter->youtube_link }}" target="_blank"><i class="fab fa-youtube"></i></a> @endif
                </div>
            </div>

            <!-- Second Column: Quick Links -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Quick Links</h5>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ url('about') }}">About Us</a></li>
                    <li><a href="{{ url('combo-packs') }}">Combo Packs</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ url('terms-conditions') }}">Terms & Conditions</a></li>
                    <li><a href="{{ url('refund-policy') }}">Refund Policy</a></li>

                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Categories</h5>
                <ul>
                    @foreach($headerCategories->take(6) as $category)
                    <li><a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>{{ $headerFooter->footer_contact_title ?? 'Contact Info' }}</h5>

                <div class="contact-info">
                    @if($headerFooter->address)
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <div class="contact-text">
                            <strong>Address</strong>
                            <p>{{ $headerFooter->address }}</p>
                        </div>
                    </div>
                    @endif

                    @if($headerFooter->mobile_no)
                    <div class="contact-item">
                        <i class="fas fa-phone-alt mr-2"></i>
                        <div class="contact-text">
                            <strong>Phone</strong>
                            <p>{{ $headerFooter->mobile_no }}</p>
                        </div>
                    </div>
                    @endif

                    @if($headerFooter->email)
                    <div class="contact-item">
                        <i class="fas fa-envelope mr-2"></i>
                        <div class="contact-text">
                            <strong>Email</strong>
                            <p>{{ $headerFooter->email }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom py-3">
            <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">

                        <!-- Left Side -->
                        <p class="mb-2 mb-md-0">
                            &copy; 2026 Plantsware. All rights reserved.
                        </p>

                        <!-- <div class="footer-bottom-payment d-flex justify-content-center">
                            <div class="payment-link">
                                <img src="/assets/images/visa.png" alt="payment">
                            </div>
                            <div class="payment-link">
                                <img src="/assets/images/rupay.png" alt="payment">
                            </div>
                            <div class="payment-link">
                                <img src="/assets/images/gpay.png" alt="payment">
                            </div>
                            <div class="payment-link">
                                <img src="/assets/images/paytm.png" alt="payment">
                            </div>
                            <div class="payment-link">
                                <img src="/assets/images/upi.png" alt="payment">
                            </div>
                        </div> -->

                        <!-- Right Side -->
                        <p class="mb-0">
                            <a href="https://jayamwebsolutions.com/" class="text-dark"> Developed by Jayam Web Solutions</a>
                        </p>

                    </div>
                </div>
            </div>
        </div>

    </div>
</footer>
<!-- footer -->
<!-- footer end -->
<!-- Floating WhatsApp Button -->
@if($headerFooter->whatsapp_no)
<a href="https://wa.me/{{ $headerFooter->whatsapp_no }}?text=Hello! I have a question about your products." class="whatsapp-float shadow-lg" target="_blank">
    <div class="whatsapp-message-container">
        <div class="whatsapp-message">{{ $headerFooter->whatsapp_msg_1 ?? 'Chat with us' }}</div>
        <div class="whatsapp-message">{{ $headerFooter->whatsapp_msg_2 ?? 'Enquire and Order' }}</div>
        <div class="whatsapp-message">{{ $headerFooter->whatsapp_msg_3 ?? 'Green World' }}</div>
    </div>
    <i class="fab fa-whatsapp"></i>
</a>
@endif

<style>
    .whatsapp-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        text-decoration: none !important;
        transition: all 0.3s ease;
        animation: pulse-whatsapp 2s infinite;
    }

    .whatsapp-float:hover {
        background-color: #128c7e;
        transform: scale(1.1);
        color: #FFF;
    }

    .whatsapp-message-container {
        position: absolute;
        right: 80px;
        background: #333;
        color: white;
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        white-space: nowrap;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        min-width: 160px;
        /* Stable width for longest message */
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
        visibility: visible;
        pointer-events: none;
    }

    .whatsapp-message {
        position: absolute;
        opacity: 0;
        text-align: center;
        width: 100%;
        animation: fade-sequence 6s infinite ease-in-out;
    }

    /* Sequential timing: 2s per message in a 6s total cycle */
    .whatsapp-message:nth-child(1) {
        animation-delay: 0s;
    }

    .whatsapp-message:nth-child(2) {
        animation-delay: 2s;
    }

    .whatsapp-message:nth-child(3) {
        animation-delay: 4s;
    }

    @keyframes fade-sequence {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }

        5% {
            opacity: 1;
            transform: translateY(0);
        }

        28% {
            opacity: 1;
            transform: translateY(0);
        }

        33% {
            opacity: 0;
            transform: translateY(-10px);
        }

        100% {
            opacity: 0;
        }
    }

    @keyframes pulse-whatsapp {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
        }

        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
        }
    }

    @media (max-width: 768px) {
        .whatsapp-float {
            width: 50px;
            height: 50px;
            font-size: 28px;
            bottom: 20px;
            right: 20px;
        }

        .whatsapp-message-container {
            display: none;
        }
    }
</style>

<!-- scroll -->
<a href="#" id="scroll"></a>
<!-- jquery-3.4.1 -->
<script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/owl.carousel.js') }}"></script>
<script src="{{ asset('assets/js/wow.min.js') }}"></script>
<script src="{{ asset('assets/js/all.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";

        if (successMessage) {
            Toast.fire({
                icon: 'success',
                title: successMessage
            });
        }

        if (errorMessage) {
            Toast.fire({
                icon: 'error',
                title: errorMessage
            });
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiperElements = document.querySelectorAll('.product-swiper');

        swiperElements.forEach(function(element) {
            new Swiper(element, {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 10,

                // ⭐ AUTOPLAY SETTINGS
                autoplay: {
                    delay: 99000, // Time between slides (2500ms = 2.5 seconds)
                    disableOnInteraction: false, // Keep autoplay after user swipes
                },

                pagination: {
                    el: element.querySelector('.swiper-pagination'),
                    clickable: true,
                },
                navigation: {
                    nextEl: element.querySelector('.swiper-button-next'),
                    prevEl: element.querySelector('.swiper-button-prev'),
                },

                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 15,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 20,
                    },
                },
            });
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.testimonial-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            speed: 600,
            grabCursor: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next1',
                prevEl: '.swiper-button-prev1',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Initialize the carousel
        $('#plantCategoriesCarousel').slick({
            dots: false,
            arrows: false,
            infinite: true,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 400,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        // Custom navigation buttons
        $('#prevBtn').click(function() {
            $('#plantCategoriesCarousel').slick('slickPrev');
        });

        $('#nextBtn').click(function() {
            $('#plantCategoriesCarousel').slick('slickNext');
        });
    });
</script>

<!-- blog sharing section -->
<script>
    function copyBlogLink() {
        navigator.clipboard.writeText(window.location.href)
            .then(() => {
                alert('Blog link copied! You can paste it on Instagram.');
            });
    }
</script>

<!-- blog sharing section end  -->

</body>

</html>