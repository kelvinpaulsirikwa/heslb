@php
    $products = __('products.products');
@endphp

<section class="products-section">
    <div class="container">
        <!-- Top Heading Row -->
        <div class="top-header-row">
            <h2>{{ __('products.who_we_are') }}</h2>
          
        </div>

        <!-- Product Cards -->
        <div class="row g-4 justify-content-center">
            @foreach ($products as $index => $product)
                @php
                    $images = [
                        'images/static_files/bg3.jpg',
                        'images/static_files/bg1.jpg',
                        'images/static_files/bg2.jpg',
                    ];
                @endphp
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="product-card-v2 w-100">
                        <div class="product-image-v2"
                             style="background-image: url('{{ asset($images[$index]) }}');">
                        </div>

                        <div class="product-content-v2">
                            <div class="content-card-v2">
                                <div>
                                    <h3 class="title">{{ $product['title'] }}</h3>
                                    <div class="title-divider"></div>
                                    <p class="description">{{ $product['description'] }}</p>
                                </div>

                                <a href="{{ route('aboutus.visionmission') }}" class="learn-more-btn-v2">
                                    {{ __('products.learn_more') }}
                                    <svg class="arrow-icon-v2" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M13 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
