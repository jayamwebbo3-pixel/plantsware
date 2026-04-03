@include('view.layout.header')


<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ url('/') }}" class="text-decoration-none">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="#" class="text-decoration-none">About</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="about-section">
    <div class="container">
        <h2 class="plantsware-section-title">About Us</h2>
        <div class="row">


            <!-- Right Side - Image -->
            <div class="col-lg-6">
                <div class="about-image">
                    @if($page->image)
                    <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title }}">
                    @else
                    <img src="{{ asset ('assets/images/about.jpg') }}" alt="PlantsWare - Bringing Nature to Your Home">
                    @endif
                </div>
            </div>
            <!-- Left Side - Content -->
            <div class="col-lg-6">
                <div class="about-content-area">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</section>


<section class="plantsware-why">
    <div class="container">
        <h2 class="plantsware-section-title">Why Choose PlantsWare?</h2>
        <div class="row">
            @php
            $features = $page->extra_content['features'] ?? [];
            @endphp

            @if(count($features) > 0)
            @foreach($features as $feature)
            <div class="col-md-4 mb-4">
                <div class="plantsware-feature">
                    <div class="plantsware-feature-icon">
                        <i class="{{ $feature['icon'] ?? 'fas fa-check' }}"></i>
                    </div>
                    <h3 class="plantsware-feature-title">{{ $feature['title'] ?? '' }}</h3>
                    <p>{{ $feature['description'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
            @else
            <!-- Fallback content if no features are set -->
            <div class="col-md-4">
                <div class="plantsware-feature">
                    <div class="plantsware-feature-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3 class="plantsware-feature-title">Eco-Friendly Products</h3>
                    <p>All our products are carefully selected for their environmental sustainability and minimal ecological impact.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="plantsware-feature">
                    <div class="plantsware-feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3 class="plantsware-feature-title">Premium Quality</h3>
                    <p>We source only the highest quality products that meet our rigorous standards for performance and durability.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="plantsware-feature">
                    <div class="plantsware-feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3 class="plantsware-feature-title">Fast Shipping</h3>
                    <p>Free shipping on orders over ₹50. Most orders ship within 24 hours and arrive within 3-5 business days.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>


<!-- Newsletter -->
<section class="plantsware-cta" style="{{ isset($page->extra_content['cta_bg_image']) ? 'background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('.asset('storage/'.$page->extra_content['cta_bg_image']).'); background-size: cover; background-position: center;' : '' }}">
    <div class="container">
        <div class="plantsware-cta-content">
            <h2 class="plantsware-cta-title">{{ $page->extra_content['cta_title'] ?? 'Ready to Transform Your Space?' }}</h2>
            <p class="plantsware-cta-subtitle">{{ $page->extra_content['cta_subtitle'] ?? 'Join thousands of satisfied customers who trust PlantsWare for all their gardening and natural product needs. Start your green journey today!' }}</p>

            <div class="plantsware-cta-buttons">
                @php
                $btnLink = $page->extra_content['cta_btn_link'] ?? 'products';
                $btnUrl = str_starts_with($btnLink, 'http') ? $btnLink : url($btnLink);
                @endphp
                <a href="{{ $btnUrl }}" class="plantsware-cta-btn plantsware-cta-btn-primary">
                    <i class="fas fa-shopping-cart me-2"></i>{{ $page->extra_content['cta_btn_text'] ?? 'Shop Now' }}
                </a>
            </div>
        </div>
    </div>
</section>

@include('view.layout.footer')