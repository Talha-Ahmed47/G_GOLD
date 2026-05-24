@extends('website.master')
@section('content')


    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="dot"></span>
                        Market Open — Trading Live
                    </div>
                    <h1 class="hero-title">
                        Invest in <span class="gold">Gold</span><br>
                        Secure Your Future
                    </h1>
                    <p class="hero-desc">
                        Experience premium gold trading with competitive rates, secure storage, and expert guidance. Join thousands of investors who trust us with their precious metals portfolio.
                    </p>
                    <div class="hero-actions">
                        <a href="#products" class="btn btn-primary">Explore Products</a>
                        <a href="#contact" class="btn btn-outline">Get Free Consultation</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-value">$2.5B+</div>
                            <div class="stat-label">Assets Under Management</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">50K+</div>
                            <div class="stat-label">Satisfied Clients</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">15+</div>
                            <div class="stat-label">Years Experience</div>
                        </div>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="price-card">
                        <div class="price-header">
                            <span class="price-label">Gold Spot Price (XAU/USD)</span>
                            <span class="price-live">Live</span>
                        </div>
                        <div class="price-main">
                            <div class="price-value" id="live-gold-price">
                                <span class="currency">$</span>{{ number_format((int)$metalPrices['gold']) }}<span style="font-size: 36px;">.{{ sprintf('%02d', ($metalPrices['gold'] - (int)$metalPrices['gold']) * 100) }}</span>
                            </div>
                            <span class="price-change">Current Price</span>
                        </div>
                        <div class="price-metals">
                            <div class="metal-item">
                                <div class="metal-name">Silver (XAG)</div>
                                <div class="metal-price" id="live-silver-price">${{ number_format($metalPrices['silver'], 2) }}</div>
                                <div class="metal-change">Current Price</div>
                            </div>
                            <div class="metal-item">
                                <div class="metal-name">Platinum (XPT)</div>
                                <div class="metal-price" id="live-platinum-price">${{ number_format($metalPrices['platinum'], 2) }}</div>
                                <div class="metal-change">Current Price</div>
                            </div>
                            <div class="metal-item">
                                <div class="metal-name">Palladium (XPD)</div>
                                <div class="metal-price" id="live-palladium-price">${{ number_format($metalPrices['palladium'], 2) }}</div>
                                <div class="metal-change">Current Price</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-header">
                <div class="section-label">Our Services</div>
                <h2 class="section-title">Complete Gold Investment Solutions</h2>
                <p class="section-desc">From purchasing your first gold coin to managing a diversified precious metals portfolio, we provide end-to-end services tailored to your needs.</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="8" width="18" height="12" rx="1"/>
                            <path d="M7 8V6a5 5 0 0110 0v2"/>
                        </svg>
                    </div>
                    <h3 class="service-title">Buy Bullion</h3>
                    <p class="service-desc">Purchase gold bars and coins from certified mints worldwide with competitive premiums and guaranteed authenticity.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <h3 class="service-title">Sell Your Gold</h3>
                    <p class="service-desc">Get the best market rates for your gold items. Free appraisals, instant quotes, and same-day payment available.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="4" y="4" width="16" height="16" rx="2"/>
                            <path d="M4 10h16"/>
                            <path d="M10 4v16"/>
                        </svg>
                    </div>
                    <h3 class="service-title">Secure Storage</h3>
                    <p class="service-desc">State-of-the-art vault facilities with 24/7 security, full insurance coverage, and allocated storage options.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5"/>
                            <path d="M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <h3 class="service-title">Gold IRA</h3>
                    <p class="service-desc">Diversify your retirement portfolio with precious metals. Tax-advantaged gold IRA accounts with expert guidance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Gold Section -->
    <section class="why-gold" id="why-gold">
        <div class="container">
            <div class="why-grid">
                <div class="why-content">
                    <div class="section-label">Why Gold</div>
                    <h2 class="section-title">The Timeless Standard of Wealth Preservation</h2>
                    <ul class="why-list">
                        <li class="why-item">
                            <span class="why-number">01</span>
                            <div class="why-text">
                                <h4>Inflation Hedge</h4>
                                <p>Gold has historically maintained its purchasing power over centuries, protecting wealth against currency devaluation and inflation.</p>
                            </div>
                        </li>
                        <li class="why-item">
                            <span class="why-number">02</span>
                            <div class="why-text">
                                <h4>Portfolio Diversification</h4>
                                <p>Gold's low correlation with stocks and bonds makes it an effective tool for reducing overall portfolio risk and volatility.</p>
                            </div>
                        </li>
                        <li class="why-item">
                            <span class="why-number">03</span>
                            <div class="why-text">
                                <h4>Safe Haven Asset</h4>
                                <p>During economic uncertainty and geopolitical tensions, gold serves as a reliable store of value when other assets decline.</p>
                            </div>
                        </li>
                        <li class="why-item">
                            <span class="why-number">04</span>
                            <div class="why-text">
                                <h4>Tangible Ownership</h4>
                                <p>Unlike digital assets, physical gold provides true ownership without counterparty risk or dependency on electronic systems.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="why-visual">
                    <div class="why-image-grid">
                        <div class="why-thumb">
                            <img src="{{ asset('website/images/tm-gold-03.jpg')}}" alt="Gold bars stacked">
                        </div>
                        <div class="why-thumb">
                            <img src="{{ asset('website/images/tm-gold-04.jpg')}}" alt="Gold coins">
                        </div>
                        <div class="why-thumb">
                            <img src="{{ asset('website/images/tm-gold-01.jpg')}}" alt="Gold investment">
                        </div>
                        <div class="why-thumb">
                            <img src="{{ asset('website/images/tm-silver-03.jpg')}}" alt="Secure vault">
                        </div>
                        <div class="why-thumb">
                            <img src="{{ asset('website/images/tm-silver-02.jpg')}}" alt="Silver bars">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@php
    function calculatePrice($product, $metalPrices) {
        $base = $metalPrices[$product->metal_type] ?? 0;
        $cost = $base * $product->weight_oz;
        return $cost * (1 + ($product->premium_percentage / 100));
    }

    $goldCoins = $products->filter(fn($p) => $p->metal_type === 'gold' && !str_contains($p->name, 'Bar'));
    $goldBars = $products->filter(fn($p) => $p->metal_type === 'gold' && str_contains($p->name, 'Bar'));
    $silverProducts = $products->filter(fn($p) => $p->metal_type === 'silver');
    $platinumProducts = $products->filter(fn($p) => $p->metal_type === 'platinum');
@endphp

    <!-- Products Section -->
    <section class="products" id="products">
        <div class="container">
            <div class="section-header">
                <div class="section-label">Our Products</div>
                <h2 class="section-title">Premium Gold Selection</h2>
                <p class="section-desc">Explore our curated collection of gold bars and coins from the world's most prestigious mints and refiners.</p>
            </div>
            <div class="products-tabs">
                <button class="tab-btn active" data-tab="coins">Gold Coins</button>
                <button class="tab-btn" data-tab="bars">Gold Bars</button>
                <button class="tab-btn" data-tab="silver">Silver</button>
                <button class="tab-btn" data-tab="platinum">Platinum</button>
            </div>

            <!-- Gold Coins Tab -->
            <div class="products-tab-content active" id="tab-coins">
                @foreach($goldCoins as $product)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-weight">{{ $product->weight_oz }} oz · {{ rtrim(number_format($product->purity * 1000, 1), '.0') }} Fine</div>
                        <div class="product-footer">
                            <span class="product-price" data-product-id="{{ $product->id }}">${{ number_format(calculatePrice($product, $metalPrices), 2) }}</span>
                            <button class="product-btn">+</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Gold Bars Tab -->
            <div class="products-tab-content" id="tab-bars">
                @foreach($goldBars as $product)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-weight">{{ $product->weight_oz }} oz · {{ rtrim(number_format($product->purity * 1000, 1), '.0') }} Fine</div>
                        <div class="product-footer">
                            <span class="product-price" data-product-id="{{ $product->id }}">${{ number_format(calculatePrice($product, $metalPrices), 2) }}</span>
                            <button class="product-btn">+</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Silver Tab -->
            <div class="products-tab-content" id="tab-silver">
                @foreach($silverProducts as $product)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-weight">{{ $product->weight_oz }} oz · {{ rtrim(number_format($product->purity * 1000, 1), '.0') }} Fine</div>
                        <div class="product-footer">
                            <span class="product-price" data-product-id="{{ $product->id }}">${{ number_format(calculatePrice($product, $metalPrices), 2) }}</span>
                            <button class="product-btn">+</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Platinum Tab -->
            <div class="products-tab-content" id="tab-platinum">
                @foreach($platinumProducts as $product)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-weight">{{ $product->weight_oz }} oz · {{ rtrim(number_format($product->purity * 1000, 1), '.0') }} Fine</div>
                        <div class="product-footer">
                            <span class="product-price" data-product-id="{{ $product->id }}">${{ number_format(calculatePrice($product, $metalPrices), 2) }}</span>
                            <button class="product-btn">+</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-header">
                <div class="section-label">Client Stories</div>
                <h2 class="section-title">Trusted by Investors Worldwide</h2>
            </div>
            <div class="testimonials-wrapper">
                <div class="testimonials-slider">
                    <div class="testimonials-track" id="testimonialsTrack">
                        <div class="testimonial-item">
                            <p class="testimonial-text">"Aurum Gold has been instrumental in diversifying my retirement portfolio. Their expertise in precious metals and transparent pricing gave me confidence in making informed investment decisions."</p>
                            <div class="testimonial-author">
                                <span class="author-name">Robert Fitzgerald</span>
                                <span class="author-title">Retired Financial Analyst</span>
                            </div>
                        </div>
                        <div class="testimonial-item">
                            <p class="testimonial-text">"The team's knowledge and professionalism exceeded my expectations. From my first purchase to setting up secure storage, every step was handled with care. Highly recommend for serious investors."</p>
                            <div class="testimonial-author">
                                <span class="author-name">Margaret Chen</span>
                                <span class="author-title">Private Investor</span>
                            </div>
                        </div>
                        <div class="testimonial-item">
                            <p class="testimonial-text">"I was new to precious metals investing, and Aurum Gold made the process incredibly simple. Their educational resources and patient advisors helped me build a solid gold portfolio over time."</p>
                            <div class="testimonial-author">
                                <span class="author-name">David Morrison</span>
                                <span class="author-title">Business Owner</span>
                            </div>
                        </div>
                        <div class="testimonial-item">
                            <p class="testimonial-text">"What sets Aurum apart is their commitment to client relationships. They're not just selling gold — they're helping build generational wealth. My family has worked with them for over a decade."</p>
                            <div class="testimonial-author">
                                <span class="author-name">Elizabeth Parker</span>
                                <span class="author-title">Estate Planner</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-nav">
                    <button class="testimonial-arrow prev" id="testimonialPrev" aria-label="Previous testimonial">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button class="testimonial-arrow next" id="testimonialNext" aria-label="Next testimonial">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="testimonial-dots" id="testimonialDots">
                <span class="dot active" data-index="0"></span>
                <span class="dot" data-index="1"></span>
                <span class="dot" data-index="2"></span>
                <span class="dot" data-index="3"></span>
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section class="trust">
        <div class="container">
            <div class="trust-grid">
                <div class="trust-item">
                    <div class="trust-logo">BBB Accredited</div>
                </div>
                <div class="trust-item">
                    <div class="trust-logo">LBMA Member</div>
                </div>
                <div class="trust-item">
                    <div class="trust-logo">NGC Certified</div>
                </div>
                <div class="trust-item">
                    <div class="trust-logo">PCGS Partner</div>
                </div>
                <div class="trust-item">
                    <div class="trust-logo">ICA Approved</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-inner">
                <h2 class="cta-title">Start Building Your <span class="gold">Gold</span> Portfolio Today</h2>
                <p class="cta-desc">Whether you're a first-time buyer or experienced investor, our team is ready to help you achieve your precious metals investment goals.</p>
                <div class="cta-actions">
                    <a href="#contact" class="btn btn-primary">Request Free Consultation</a>
                    <a href="#" class="btn btn-outline">Download Investment Guide</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="section-label">Contact Us</div>
                    <h3>Let's Discuss Your Investment Goals</h3>
                    <p>Our precious metals experts are here to answer your questions and guide you through every step of your gold investment journey.</p>
                    <ul class="contact-details">
                        <li>
                            <div class="contact-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <div class="contact-label">Phone</div>
                                <div class="contact-value">+1 (800) 555-GOLD</div>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <div class="contact-label">Email</div>
                                <div class="contact-value">invest@aurumgold.com</div>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <div class="contact-label">Showroom</div>
                                <div class="contact-value">350 Fifth Avenue, Suite 4800<br>New York, NY 10118</div>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <div class="contact-label">Business Hours</div>
                                <div class="contact-value">Mon - Fri: 9:00 AM - 6:00 PM EST<br>Sat: 10:00 AM - 2:00 PM EST</div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="contact-form-wrapper">
                    @if(session('success'))
                        <div style="background: rgba(46, 204, 113, 0.15); border: 1px solid #2ecc71; padding: 15px; border-radius: 8px; color: #2ecc71; margin-bottom: 20px; text-align: center; font-weight: 500;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div style="background: rgba(231, 76, 60, 0.15); border: 1px solid #e74c3c; padding: 15px; border-radius: 8px; color: #e74c3c; margin-bottom: 20px; text-align: center;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form class="contact-form" method="POST" action="/contact">
                        @csrf
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-input" placeholder="John" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-input" placeholder="Smith" value="{{ old('last_name') }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-input" placeholder="john@example.com" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-input" placeholder="+1 (555) 000-0000" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">I'm interested in</label>
                            <select name="interest" class="form-select" required>
                                <option value="">Select an option</option>
                                <option value="buying" {{ old('interest') == 'buying' ? 'selected' : '' }}>Buying</option>
                                <option value="sell" {{ old('interest') == 'sell' ? 'selected' : '' }}>Selling</option>
                                <option value="storage" {{ old('interest') == 'storage' ? 'selected' : '' }}>Storage Services</option>
                                <option value="ira" {{ old('interest') == 'ira' ? 'selected' : '' }}>IRA</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-textarea" placeholder="Tell us about your investment goals or questions..." required>{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary form-submit">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            function updatePrices() {
                $.ajax({
                    url: '/api/gold-price',
                    method: 'GET',
                    success: function(data) {
                        if (data.prices) {
                            // Update Gold
                            const goldParts = parseFloat(data.prices.gold).toFixed(2).split('.');
                            const goldElement = $('#live-gold-price');
                            if (goldElement.length) {
                                goldElement.html(`<span class="currency">$</span>${parseInt(goldParts[0]).toLocaleString('en-US')}<span style="font-size: 36px;">.${goldParts[1]}</span>`);
                                flashElement(goldElement);
                            }

                            // Update Others
                            const metals = ['silver', 'platinum', 'palladium'];
                            metals.forEach(metal => {
                                const el = $(`#live-${metal}-price`);
                                if (el.length) {
                                    el.html(`$${parseFloat(data.prices[metal]).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
                                    flashElement(el);
                                }
                            });
                        }

                        if (data.product_prices) {
                            Object.entries(data.product_prices).forEach(([id, price]) => {
                                const el = $(`.product-price[data-product-id="${id}"]`);
                                if (el.length) {
                                    el.html(`$${parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
                                    flashElement(el);
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching prices:', error);
                    }
                });
            }

            function flashElement(el) {
                el.css('opacity', '0.5');
                setTimeout(() => {
                    el.css({
                        'opacity': '1',
                        'transition': 'opacity 0.3s'
                    });
                }, 100);
            }

            updatePrices();
            setInterval(updatePrices, 10000);
        });
    </script>
@endsection
