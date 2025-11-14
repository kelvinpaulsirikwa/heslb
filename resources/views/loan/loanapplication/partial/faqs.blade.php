@extends('loan.loanapplication.loanapplication')

@php($pageTitle = __('loan_faq.page_title'))

@section('aboutus-content')
<div class="faq-container">
    <div class="faq-header">
        <h2 class="faq-main-title">{{ __('loan_faq.main_title') }}</h2>
        <p class="faq-subtitle">{{ __('loan_faq.subtitle') }}</p>
    </div>

    <div class="row">
        <!-- Popular Questions Column -->
        <div class="col-md-6">
            <div class="faq-section">
                <h3 class="section-title">{{ __('loan_faq.popular_questions_heading') }}</h3>
                <br>
                @foreach($popular_questions as $index => $faq)
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ('popular-{{ $index }}', this)">
                        <span class="question-text">{{ $faq['question'] }}</span>
                        <div class="toggle-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                    <div id="popular-{{ $index }}" class="faq-answer">
                        <div class="answer-content">
                            {!! $faq['answer'] !!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- General Questions Column -->
        <div class="col-md-6">
            <div class="faq-section">
                <h3 class="section-title">{{ __('loan_faq.general_questions_heading') }}</h3>
                <br>
                @foreach($general_questions as $index => $faq)
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ('general-{{ $index }}', this)">
                        <span class="question-text">{{ $faq['question'] }}</span>
                        <div class="toggle-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                    <div id="general-{{ $index }}" class="faq-answer">
                        <div class="answer-content">
                            {!! $faq['answer'] !!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function toggleFAQ(answerId, questionElement) {
    const answerElement = document.getElementById(answerId);
    const section = questionElement.closest('.faq-section');
    const allQuestions = section.querySelectorAll('.faq-question');
    const allAnswers = section.querySelectorAll('.faq-answer');
    
    const isCurrentlyActive = questionElement.classList.contains('active');
    
    allQuestions.forEach(q => q.classList.remove('active'));
    allAnswers.forEach(a => a.classList.remove('show'));
    
    if (!isCurrentlyActive) {
        questionElement.classList.add('active');
        answerElement.classList.add('show');
    }
}

$(document).ready(function() {
    $('.faq-question').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const section = $(this).closest('.faq-section');
        const isCurrentlyActive = $(this).hasClass('active');
        
        section.find('.faq-question').removeClass('active');
        section.find('.faq-answer').removeClass('show');
        
        if (!isCurrentlyActive) {
            $(this).addClass('active');
            $(this).siblings('.faq-answer').addClass('show');
        }
    });
});
</script>
@endsection
