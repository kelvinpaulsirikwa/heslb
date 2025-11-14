<!-- Footer Section Start -->
<footer>
    <!-- Top Footer Info Block -->
    <div class="footer-top-info">
        <div class="container">
            <div class="footer-row">
                <div class="footer-col">
                    <div class="info-item d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <a href="{{ config('links.contact.phone_tel') }}" style="color: inherit; text-decoration: none;">{{ config('links.contact.phone') }}</a>
                            <span>{{ __('footer.phone_info') }}</span>
                        </h4>
                        <i class="fas fa-phone info-icon ms-2"></i>
                    </div>
                </div>
                <div class="footer-col">
                    <div class="info-item d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <a href="{{ config('links.contact.email_mailto') }}" style="color: inherit; text-decoration: none;">{{ config('links.contact.email') }}</a>
                            <span>{{ __('footer.email_note') }}</span>
                        </h4>
                        <i class="fas fa-envelope info-icon ms-2"></i>
                    </div>
                </div>
                <div class="footer-col">
                    <div class="info-item d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            Bodi ya Mikopo
                            <span>{{ __('footer.slogan') }}</span>
                        </h4>
                        <i class="fas fa-bullhorn info-icon ms-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer Block -->
    <div class="footer-main">
        <div class="footer-overlay"></div>
        <div class="container">
            <div class="footer-row">
                
                <!-- Our Profile -->
                <div class="footer-col-lg">
                    <div>
                        <h4 class="footer-title">
                            {{ __('footer.profile') }}
                            <div class="title-underline"></div>
                        </h4>
                        <ul class="footer-links">
                            <li>
                                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                <a href="{{ route('aboutus.visionmission') }}">{{ __('footer.background') }}</a>
                            </li>
                            <li>
                                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                <a href="{{ route('aboutus.function') }}">{{ __('footer.functions') }}</a>
                            </li>
                            <li>
                                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                <a href="{{ route('aboutus.boardofdirector') }}">{{ __('footer.board') }}</a>
                            </li>
                            <li>
                                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                <a href="{{ route('aboutus.organogram') }}">{{ __('footer.organogram') }}</a>
                            </li>
                            <li>
                                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                <a href="{{ route('aboutus.visionmission') }}">{{ __('footer.strategic') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Publications -->
                <div class="footer-col-lg">
                    <div>
                        <h4 class="footer-title">
                            {{ __('footer.publications') }}
                            <div class="title-underline"></div>
                        </h4>
                        <ul class="footer-links">
                            @if($showPublications && !empty($publicationlist) && count($publicationlist) > 0)
                                @foreach($publicationlist as $publication)
                                    <li>
                                        <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                        <a href="{{ route('publications.category', ['choice' => $publication['slug']]) }}" 
                                           title="{{ $publication['name'] }}"
                                           class="footer-link">
                                            {{ $publication['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Loan Application -->
                <div class="footer-col-lg">
                    <div>
                        <h4 class="footer-title">
                            {{ __('footer.loan_app') }}
                            <div class="title-underline"></div>
                        </h4>
                        <ul class="footer-links">
                            <li>
                                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                <a href="{{ route('loanapplication.about') }}">{{ __('footer.loan_info') }}</a>
                            </li>
                            <li>
                                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                <a href="{{ route('loanapplication.guideline') }}">{{ __('footer.loan_guideline') }}</a>
                            </li>
                            <li>
                                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                <a href="{{ route('loanapplication.faqs') }}">{{ __('footer.faqs') }}</a>
                            </li>
                            <li>
                                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                <a href="{{ config('links.heslb_systems.olams_pre_applicant') }}">{{ __('footer.olams') }}</a>
                            </li>
                            <li>
                                <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                <a href="{{ route('loanapplication.obligation') }}">{{ __('footer.obligations') }}</a>
                            </li>
                        </ul>

                        <div class="footer-subsection">
                            <h4 class="footer-title">
                                {{ __('footer.loan_repay') }}
                                <div class="title-underline"></div>
                            </h4>
                            <ul class="footer-links">
                                <li>
                                    <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                    <a href="{{ route('loanrepayment.about') }}">{{ __('footer.repay_info') }}</a>
                                </li>
                                <li>
                                    <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                    <a href="{{ route('loanrepayment.obligation') }}">{{ __('footer.obligations') }}</a>
                                </li>
                                <li>
                                    <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                    <a href="{{ config('links.heslb_systems.olams_employer_login') }}">{{ __('footer.repay_portal') }}</a>
                                </li>
                                <li>
                                    <span class="link-arrow"><i class="fas fa-angle-right"></i></span>
                                    <a href="{{ route('loanrepayment.faqs') }}">{{ __('footer.faqs') }}</a>
                                </li>
                               
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="footer-col-lg">
                    <div>
                        <h4 class="footer-title">   
                            {{ __('footer.contact') }}
                            <div class="title-underline"></div>
                        </h4>
                        <ul class="footer-contact">
                            <li>
                                <p>{{ __('footer.call_us') }}: <a href="{{ config('links.contact.phone_tel') }}" style="color: inherit; text-decoration: none;">{{ config('links.contact.phone') }}</a></p>
                                <i class="fas fa-phone contact-icon"></i>
                            </li>
                            <li>
                                <p><a href="{{ config('links.contact.email_mailto') }}">{{ config('links.contact.email') }}</a></p>
                                <i class="fas fa-envelope contact-icon"></i>
                            </li>
                            <li>
                                <p>{{ config('links.contact.address') }}</p>
                                <i class="fas fa-map-marker-alt contact-icon"></i>
                            </li>
                            <li>
                                <p>{{ __('footer.hours') }}</p>
                                <i class="fas fa-clock contact-icon"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Social Media Row -->
            <div class="d-flex justify-content-end align-items-center mt-3" style="gap: 0.75rem;">
                <a href="{{ config('links.social_media.twitter') }}" target="_blank" class="icon-box">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
                <a href="{{ config('links.social_media.facebook') }}" target="_blank" class="icon-box">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="{{ config('links.social_media.instagram') }}" target="_blank" class="icon-box">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="{{ config('links.social_media.youtube') }}" target="_blank" class="icon-box">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section End -->