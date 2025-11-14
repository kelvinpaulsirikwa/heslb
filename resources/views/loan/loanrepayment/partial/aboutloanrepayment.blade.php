@extends('loan.loanrepayment.loanrepayment')
@php($pageTitle = __('repaymentservice.general_information'))

@section('aboutus-content')
<div class="general-info-container" style="padding: 40px 0;">
    <div class="container">
        <div class="content-wrapper" style="background: rgba(255, 255, 255, 0.95); padding: 40px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            
            <!-- General Information Section -->
            <div class="general-info-section mb-5">
                <h2 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px;">{{ __('repaymentservice.general_information') }}</h2>
                
                <div class="info-content">
                    <p style="font-size: 16px; line-height: 1.6; color: #333;">
                        {{ __('repaymentservice.heslb_mandate_repayment') }}
                    </p>
                </div>
            </div>

            <!-- Employers Section -->
            <div class="employers-section mb-5">
                <h3 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px;">{{ __('repaymentservice.employers') }}:</h3>
                
                <div class="employer-requirements">
                    <div class="requirement-item mb-4">
                        <p><strong>1.</strong> {{ __('repaymentservice.submit_graduate_list') }}</p>
                        
                        <!-- Employee Details Table -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered" style="font-size: 14px;">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        @foreach(__('repaymentservice.employee_table_headers') as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1)</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="requirement-item mb-3">
                        <p><strong>2.</strong> {{ __('repaymentservice.effect_deductions') }}</p>
                    </div>

                    <div class="requirement-item mb-3">
                        <p><strong>3.</strong> {{ __('repaymentservice.obtain_control_number') }}</p>
                    </div>

                    <div class="requirement-item mb-4">
                        <p><strong>4.</strong> {{ __('repaymentservice.submit_payment_proof') }}</p>
                    </div>
                </div>
            </div>

            <!-- Employed Beneficiary Section -->
            <div class="employed-beneficiary-section mb-5">
                <h3 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px;">{{ __('repaymentservice.employed_beneficiary') }}:</h3>
                
                <div class="beneficiary-steps">
                    @foreach(__('repaymentservice.employed_beneficiary_steps') as $index => $step)
                        <div class="step-item mb-3">
                            <p><strong>{{ $index + 1 }}.</strong> {{ $step }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Self-employed Beneficiary and Diaspora Section -->
            <div class="self-employed-section mb-5">
                <h3 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px;">{{ __('repaymentservice.self_employed_and_diaspora') }}:</h3>
                
                <div class="self-employed-steps">
                    @foreach(__('repaymentservice.self_employed_steps') as $index => $step)
                        <div class="step-item mb-3">
                            <p><strong>{{ $index + 1 }}.</strong> {{ $step }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Note Section -->
            <div class="note-section">
                <h3 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px;">{{ __('repaymentservice.note') }}:</h3>
                
                <div class="note-items">
                    <div class="note-item mb-3">
                        <p><strong>1.</strong> {{ __('repaymentservice.gepg_payments') }}</p>
                    </div>
                    <div class="note-item mb-3">
                        <p><strong>2.</strong> {{ __('repaymentservice.control_number_unchanged') }}</p>
                    </div>
                    <div class="note-item mb-3">
                        <p><strong>3.</strong> {{ __('repaymentservice.request_clearance') }}</p>
                    </div>
                </div>
            </div>

            <!-- Image Display Section -->
            <div class="image-section mt-5">
                <div class="text-center">
                    <img src="{{ asset('images/static_files/loanrepaymentflowchart.jpg') }}" 
                         alt="HESLB Information" 
                         class="img-fluid rounded shadow-lg" 
                         style="max-width: 100%; height: auto; border: 3px solid #2c5aa0;">
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.general-info-container {
    position: relative;
}

.content-wrapper {
    background: white !important;
    border: 1px solid #e0e0e0;
}

.table th {
    background-color: #2c5aa0 !important;
    color: white;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
}

.table td {
    vertical-align: middle;
    text-align: center;
    min-height: 40px;
    padding: 12px 8px;
}

.requirement-item, .step-item, .note-item {
    padding: 10px 0;
    border-left: 4px solid #2c5aa0;
    padding-left: 15px;
    margin-left: 10px;
}

.requirement-item p, .step-item p, .note-item p {
    margin-bottom: 0;
    color: #444;
}

.image-section img {
    transition: transform 0.3s ease;
}

.image-section img:hover {
    transform: scale(1.02);
}

@media (max-width: 768px) {
    .content-wrapper {
        padding: 20px;
        margin: 10px;
    }
    
    .table-responsive {
        font-size: 12px;
    }
    
    .general-info-container {
        padding: 20px 0;
    }
    
    .image-section img {
        border-width: 2px !important;
    }
}
</style>

@endsection