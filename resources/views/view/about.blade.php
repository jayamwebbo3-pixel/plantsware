@include('view.layout.header')

<section class="about-section" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left Side - Content -->
            <div class="col-lg-6 mb-5 mb-lg-0 order-2 order-lg-1" data-aos="fade-right" data-aos-delay="200">
                <div class="about-content-area pe-lg-4">
                    <h2 class="plantsware-section-title">About Us</h2>
                    <div class="about-text-wrapper mt-4">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
            <!-- Right Side - Image -->
            <div class="col-lg-6 order-1 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left" data-aos-delay="400">
                <div class="about-image-wrapper">
                    <div class="about-image glassmorphic-border">
                        @if($page->image)
                        <img src="{{ asset('storage/' . $page->image) }}" class="img-fluid rounded-4 shadow-lg w-100 object-fit-cover" alt="{{ $page->title }}" loading="eager">
                        @else
                        <img src="{{ asset ('assets/images/about.jpg') }}" class="img-fluid rounded-4 shadow-lg w-100 object-fit-cover" alt="PlantsWare - Bringing Nature to Your Home" loading="eager">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="plantsware-why" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
        <div class="text-center mb-5" data-aos="zoom-in">
            <h2 class="plantsware-section-title">Why Choose PlantsWare?</h2>
            <p class="plantsware-section-subtitle">Discover what makes us the preferred choice for plant enthusiasts.</p>
        </div>
        <div class="row g-4 justify-content-center">
            @php
            $features = $page->extra_content['features'] ?? [];
            @endphp

            @if(count($features) > 0)
            @foreach($features as $index => $feature)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
                <div class="plantsware-feature glassmorphic-card text-center h-100">
                    <div class="plantsware-feature-icon-wrapper mx-auto mb-4">
                        <div class="plantsware-feature-icon">
                            <i class="{{ $feature['icon'] ?? 'fas fa-check' }}"></i>
                        </div>
                    </div>
                    <h3 class="plantsware-feature-title">{{ $feature['title'] ?? '' }}</h3>
                    <p class="plantsware-feature-desc text-muted">{{ $feature['description'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
            @else
            <!-- Fallback content if no features are set -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="plantsware-feature glassmorphic-card text-center h-100">
                    <div class="plantsware-feature-icon-wrapper mx-auto mb-4">
                        <div class="plantsware-feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                    </div>
                    <h3 class="plantsware-feature-title">Eco-Friendly Products</h3>
                    <p class="plantsware-feature-desc text-muted">All our products are carefully selected for their environmental sustainability and minimal ecological impact.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="plantsware-feature glassmorphic-card text-center h-100">
                    <div class="plantsware-feature-icon-wrapper mx-auto mb-4">
                        <div class="plantsware-feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                    </div>
                    <h3 class="plantsware-feature-title">Premium Quality</h3>
                    <p class="plantsware-feature-desc text-muted">We source only the highest quality products that meet our rigorous standards for performance and durability.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="plantsware-feature glassmorphic-card text-center h-100">
                    <div class="plantsware-feature-icon-wrapper mx-auto mb-4">
                        <div class="plantsware-feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                    </div>
                    <h3 class="plantsware-feature-title">Fast Shipping</h3>
                    <p class="plantsware-feature-desc text-muted">Free shipping on orders over ₹50. Most orders ship within 24 hours and arrive within 3-5 business days.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>


<!-- Newsletter -->
@php
$ctaBgUrl = isset($page->extra_content['cta_bg_image']) ? asset('storage/'.$page->extra_content['cta_bg_image']) : asset('assets/images/cta-bg.jpg');
@endphp
<section class="plantsware-cta" data-cta-bg="{{ $ctaBgUrl }}" data-aos="fade-in" data-aos-duration="1500">
    <div class="plantsware-cta-overlay"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="zoom-in" data-aos-delay="200">
                <div class="plantsware-cta-content glassmorphic-card-dark p-5 rounded-4">
                    <h2 class="plantsware-cta-title text-white mb-4">{{ $page->extra_content['cta_title'] ?? 'Ready to Transform Your Space?' }}</h2>
                    <p class="plantsware-cta-subtitle text-white-50 mb-5 fs-5">{{ $page->extra_content['cta_subtitle'] ?? 'Join thousands of satisfied customers who trust PlantsWare for all their gardening and natural product needs. Start your green journey today!' }}</p>

                    <div class="plantsware-cta-buttons">
                        @php
                        $btnLink = $page->extra_content['cta_btn_link'] ?? 'products';
                        $btnUrl = str_starts_with($btnLink, 'http') ? $btnLink : url($btnLink);
                        @endphp
                        <a href="{{ $btnUrl }}" class="btn plantsware-cta-btn plantsware-cta-btn-primary btn-lg rounded-pill px-5 shadow-lg position-relative overflow-hidden group">
                            <span class="position-relative z-1"><i class="fas fa-shopping-cart me-2"></i>{{ $page->extra_content['cta_btn_text'] ?? 'Shop Now' }}</span>
                            <div class="btn-hover-effect"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('view.layout.footer')