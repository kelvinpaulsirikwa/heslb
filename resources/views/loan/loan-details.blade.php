@extends('layouts.app')

@section('title', $details['title'] . ' - ' . __('messages.page_title'))

@section('content')
<div class="loan-details-page">


<div class="heslb-hero">
    <div class="container">
        <h1 class="heslb-hero-title" id="page-title">{{ $details['title'] }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-white">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white">{{ __('messages.breadcrumb_home') }}</a>
                </li>
                <li class="breadcrumb-item">
                                <a href="{{ route('ourproduct.seeproductandfeedback') }}">{{ __('products.our_comprehensive_coverage') }}</a>
                            </li>            </ol>
        </nav>
    </div>
</div>
    <!-- Hero Section -->
  

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Details Section -->
            <div class="col-lg-8">
                <div class="loan-details-content">
                    <h2 class="section-title">{{ __('loan.details_overview') }}</h2>
                    
                    <div class="details-grid">
                        @foreach($details['details'] as $key => $value)
                        <div class="detail-item">
                            <h4 class="detail-title">{{ $key }}</h4>
                            <p class="detail-content">{{ $value }}</p>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <!-- Benefits Sidebar -->
            <div class="col-lg-4">
                <div class="loan-benefits-sidebar">

                    <!-- Action Buttons -->
                    <div class="action-buttons mt-4">
                        <a href="{{ route('loanapplication.applicationlink') }}" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-file-alt me-2"></i>
                            {{ __('loan.apply_now') }}
                        </a>
                        <a href="{{ route('contactus.getusintouch', ['type' => 'inquiries']) }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-question-circle me-2"></i>
                            {{ __('loan.ask_question') }}
                        </a>
                    </div>

                    <!-- Related Links -->
                    <div class="related-links mt-4">
                        <h4 class="related-title">{{ __('loan.related_links') }}</h4>
                        <ul class="related-list">
                            <li><a href="{{ route('loanapplication.guideline') }}">{{ __('loan.application_guidelines') }}</a></li>
                            <li><a href="{{ route('loanrepayment.repaymentlink') }}">{{ __('loan.repayment_info') }}</a></li>
                            <li><a href="{{ route('ourproduct.seeproductandfeedback') }}">{{ __('products.our_comprehensive_coverage') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>

<style>
.loan-details-page {
    min-height: 100vh;
}

.loan-details-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4rem 0;
}

.loan-details-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.loan-details-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.loan-details-icon {
    font-size: 8rem;
    opacity: 0.8;
}


.breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: white;
}

.breadcrumb-item.active {
    color: white;
}

.section-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 2rem;
    padding-bottom: 0.5rem;
    border-bottom: 3px solid #3498db;
}

.details-grid {
    display: grid;
    gap: 2rem;
}

.detail-item {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-left: 4px solid #3498db;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.detail-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
}

.detail-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 0.75rem;
    font-size: 1.1rem;
}

.detail-content {
    color: #555;
    line-height: 1.6;
    margin-bottom: 0;
}

.loan-benefits-sidebar {
    background: #f8f9fa;
    border-radius: 1rem;
    padding: 2rem;
    position: sticky;
    top: 2rem;
}

.benefits-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-align: center;
}

.benefits-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.benefit-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
    color: #555;
    line-height: 1.5;
}

.benefit-item:last-child {
    border-bottom: none;
}

.action-buttons .btn {
    border-radius: 0.5rem;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
}

.related-links {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-top: 2rem;
}

.related-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.related-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.related-list li {
    margin-bottom: 0.5rem;
}

.related-list a {
    color: #3498db;
    text-decoration: none;
    transition: color 0.3s ease;
}

.related-list a:hover {
    color: #2980b9;
    text-decoration: underline;
}

.loan-cta-section {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 3rem 0;
    margin-top: 4rem;
}

.cta-title {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.cta-description {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Advantages Section */
.advantages-section {
    background: #f8f9fa;
    border-radius: 1rem;
    padding: 2rem;
}

.advantages-grid {
    display: grid;
    gap: 1rem;
}

.advantage-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.advantage-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.advantage-item i {
    font-size: 1.2rem;
    margin-top: 0.2rem;
}

/* How It Works Section */
.how-it-works-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 1rem;
    padding: 2rem;
}

.how-it-works-section .section-title {
    color: white;
    border-bottom: 3px solid rgba(255, 255, 255, 0.3);
}

.steps-container {
    display: grid;
    gap: 1.5rem;
}

.step-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.step-number {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-content p {
    margin: 0;
    line-height: 1.6;
    opacity: 0.95;
}

@media (max-width: 768px) {
    .loan-details-title {
        font-size: 2rem;
    }
    
    .loan-details-icon {
        font-size: 4rem;
        margin-top: 2rem;
    }
    
    .loan-details-hero {
        padding: 2rem 0;
    }
    
    .cta-title {
        font-size: 1.5rem;
    }
    
    .loan-cta-section .col-lg-4 {
        text-align: center !important;
        margin-top: 2rem;
    }
    
    .advantages-section,
    .how-it-works-section {
        padding: 1.5rem;
    }
    
    .step-item {
        flex-direction: column;
        text-align: center;
    }
    
    .step-number {
        align-self: center;
    }
}
</style>
@endsection
