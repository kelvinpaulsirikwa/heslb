@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background: #f8f9fa; min-height: 100vh;">
    <!-- Header Section -->
 

    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background: #ffffff; border-radius: 8px;">
                <div class="card-header border-bottom" style="background: #009fe3; border-radius: 8px 8px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-white">
                            <h5 class="mb-0 fw-semibold">
                                 Contact Form Feedback Management
                            </h5>
                         </div>
                        <div>
                            <button class="btn btn-light btn-sm shadow-sm me-2" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf me-1 text-danger"></i>Export PDF
                            </button>
                            <button class="btn btn-outline-light btn-sm shadow-sm" onclick="printAllReports()">
                                <i class="fas fa-print me-1"></i>Print All
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0" style="background: #ffffff;">
                    @if($feedbacks->isEmpty())
                        <div class="text-center py-5" style="background: #f8f9fa; margin: 20px; border-radius: 8px;">
                            <div class="mb-3">
                                <div class="bg-secondary bg-opacity-10 rounded-circle p-4 mx-auto" style="width: fit-content;">
                                    <i class="fas fa-inbox fa-3x text-secondary"></i>
                                </div>
                            </div>
                            <h5 class="text-dark">No Feedback Records Found</h5>
                            <p class="text-muted mb-0">No citizen feedback has been submitted yet.</p>
                        </div>
                    @else
                        <div class="table-responsive" style="background: #ffffff; margin: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            <table class="table table-hover mb-0" id="feedbackTable" style="font-size: 0.9rem; border-radius: 8px; overflow: hidden;">
                                <thead style="background: #ecf0f1;">
                                    <tr>
                                        <th class="px-3 py-3 text-center fw-semibold text-dark" style="min-width: 60px;">#</th>
                                        <th class="px-3 py-3 fw-semibold text-dark" style="min-width: 120px;">
                                            <i class="text-primary"></i>Full Name
                                        </th>
                                        <th class="px-3 py-3 fw-semibold text-dark" style="min-width: 200px;">
                                            <i class="  text-success"></i>Contact Details
                                        </th>
                                       
                                        <th class="px-3 py-3 fw-semibold text-dark" style="min-width: 120px;">
                                            <i class=" text-warning"></i>Category
                                        </th>
                                        <th class="px-3 py-3 fw-semibold text-dark" style="min-width: 300px;">
                                            <i class=" text-secondary"></i>Message
                                        </th>
                                        <th class="px-3 py-3 fw-semibold text-dark" style="min-width: 150px;">
                                            <i class=" text-info"></i>Reference Info
                                        </th>
                                        
                                        
                                        <th class="px-3 py-3 fw-semibold text-dark" style="min-width: 140px;">
                                            <i class=" text-secondary"></i>Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody style="background: #ffffff;">
                                    @foreach($feedbacks as $index => $feedback)
                                        <tr class="border-bottom feedback-row" style="transition: all 0.3s ease;">
                                            <td class="px-3 py-3 text-center">
                                                <span class="badge bg-secondary text-white fw-normal">{{ $index + 1 }}</span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="fw-semibold text-dark">
                                                    {{ $feedback->first_name }} {{ $feedback->last_name }}
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="small">
                                                    <div class="mb-1">
                                                        <i class="fas fa-envelope text-primary me-1"></i>
                                                        <span class="text-dark">{{ $feedback->email }}</span>
                                                    </div>
                                                    <div>
                                                        <i class="fas fa-phone text-success me-1"></i>
                                                        <span class="text-dark">{{ $feedback->phone }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                         
                                            <td class="px-3 py-3">
                                                <span class="badge bg-light text-dark border fw-normal" style="padding: 6px 12px;">
                                                    {{ $feedback->contact_type }}
                                                </span>
                                            </td>
                                         <td class="px-3 py-3">
    @php

        // Remove HTML tags
        $cleanMessage = strip_tags($feedback->message);

        // Fixed character limit for preview
        $charLimit = 200;
        $isLongMessage = Str::length($cleanMessage) > $charLimit;
        $shortMessage = Str::limit($cleanMessage, $charLimit, '...');
        $wordCount = str_word_count($cleanMessage);
    @endphp

    <div class="message-content">
        <div class="mb-2">
            <p class="mb-0 text-dark" style="line-height: 1.4; word-break: break-word; white-space: pre-wrap;">
                {{ $shortMessage }}
            </p>
        </div>
          @if($shortMessage)
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-sm btn-outline-primary read-more-btn shadow-sm" 
                     data-bs-toggle="modal" 
                        data-bs-target="#messageModal"
                        data-message="{{ htmlspecialchars($feedback->message, ENT_QUOTES) }}"
                        data-name="{{ $feedback->first_name }} {{ $feedback->last_name }}"
                        data-email="{{ $feedback->email }}"
                        data-phone="{{ $feedback->phone }}"
                        data-gender="{{ $feedback->gender }}"
                        data-age="{{ $feedback->age }}"
                        data-contact-type="{{ $feedback->contact_type }}"
                        data-reference="{{ $feedback->reference_number ?? 'N/A' }}"
                        data-incident-date="{{ $feedback->date_of_incident ?? 'N/A' }}"
                        data-location="{{ $feedback->location ?? 'N/A' }}"
                        data-education="{{ $feedback->education_level ?? 'N/A' }}"
                        data-loan-status="{{ $feedback->loan_status ?? 'N/A' }}"
                        data-consent="{{ $feedback->consent ? 'Yes' : 'No' }}"
                        data-date="{{ $feedback->created_at->format('M d, Y H:i A') }}"
                        data-word-count="{{ $wordCount }}">    <i class="fas fa-expand me-1"></i>Read More
                </button>
                <small class="text-muted">{{ $wordCount }} words</small>
            </div>
        @endif
        @if($isLongMessage)
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-sm btn-outline-primary read-more-btn shadow-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#messageModal"
                        data-message="{{ htmlspecialchars($feedback->message, ENT_QUOTES) }}"
                        data-name="{{ $feedback->first_name }} {{ $feedback->last_name }}"
                        data-email="{{ $feedback->email }}"
                        data-phone="{{ $feedback->phone }}"
                        data-gender="{{ $feedback->gender }}"
                        data-age="{{ $feedback->age }}"
                        data-contact-type="{{ $feedback->contact_type }}"
                        data-reference="{{ $feedback->reference_number ?? 'N/A' }}"
                        data-incident-date="{{ $feedback->date_of_incident ?? 'N/A' }}"
                        data-location="{{ $feedback->location ?? 'N/A' }}"
                        data-education="{{ $feedback->education_level ?? 'N/A' }}"
                        data-loan-status="{{ $feedback->loan_status ?? 'N/A' }}"
                        data-consent="{{ $feedback->consent ? 'Yes' : 'No' }}"
                        data-date="{{ $feedback->created_at->format('M d, Y H:i A') }}"
                        data-word-count="{{ $wordCount }}">
                    <i class="fas fa-expand me-1"></i>Read More
                </button>
                <small class="text-muted">{{ $wordCount }} words</small>
            </div>
        @endif
    </div>
</td>
   <td class="px-3 py-3">
                                                <div class="small">
                                                    @if($feedback->reference_number)
                                                        <div class="mb-1">
                                                            <strong>Ref:</strong> {{ $feedback->reference_number }}
                                                        </div>
                                                    @endif
                                                    @if($feedback->date_of_incident)
                                                        <div class="mb-1 text-muted">
                                                            <strong>Incident:</strong> {{ $feedback->date_of_incident }}
                                                        </div>
                                                    @endif
                                                    @if($feedback->location)
                                                        <div class="text-muted">
                                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $feedback->location }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                           
                                           
                                            <td class="px-3 py-3">
                                                <div class="small">
                                                    <div class="fw-semibold text-dark">{{ $feedback->created_at->format('M d, Y') }}</div>
                                                    <div class="text-muted">{{ $feedback->created_at->format('H:i A') }}</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Info -->
                        <div class="card-footer border-top" style="background: #f8f9fa;">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-dark fw-semibold">
                                    Showing {{ $feedbacks->count() }} records
                                </small>
                                <small class="text-muted">
                                    Last updated: {{ now()->format('M d, Y H:i A') }}
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Full Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg" style="border-radius: 8px; border: none; background: #ffffff;">
            <div class="modal-header border-bottom" style="background: #34495e;">
                <h5 class="modal-title text-white fw-bold" id="messageModalLabel">
                    <i class="fas fa-envelope-open me-2"></i>Complete Feedback Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-0">
                <!-- Header Information -->
                <div class="p-4" style="background: #ecf0f1;">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle p-2 bg-primary">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Sender</small>
                                    <strong id="modalSenderName" class="text-dark"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle p-2 bg-success">
                                        <i class="fas fa-envelope text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <strong id="modalSenderEmail" class="text-dark"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle p-2 bg-info">
                                        <i class="fas fa-calendar text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Date Submitted</small>
                                    <strong id="modalMessageDate" class="text-dark"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle p-2 bg-secondary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Word Count</small>
                                    <span id="modalWordCount" class="badge bg-secondary text-white"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Details -->
                <div class="p-4" style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                   
                    <div class="row g-3">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Phone</small>
                            <span id="modalPhone" class="fw-semibold"></span>
                        </div>
                       
                      
                    </div>
                </div>
                
                <!-- Message Content -->
                <div class="p-4" style="background: #ffffff;">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-0 fw-bold text-secondary">
                                <i class="fas fa-comment-dots me-2"></i>Message Content
                            </h6>
                        </div>
                        
                        <div class="card border shadow-sm" style="border-radius: 8px;">
                            <div class="card-body">
                                <div id="modalFullMessage" 
                                     class="message-text" 
                                     style="
                                         line-height: 1.7; 
                                         font-size: 1rem; 
                                         color: #333; 
                                         white-space: pre-wrap; 
                                         word-wrap: break-word;
                                         max-height: 400px;
                                         overflow-y: auto;
                                     ">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer" style="background: #f8f9fa;">
                <div class="d-flex gap-2">
                    
                    <button type="button" class="btn btn-info shadow-sm" onclick="printSingleMessage()">
                        <i class="fas fa-print me-1"></i>Print This
                    </button>
                    <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Professional Government Styling */
body {
    background: #f8f9fa !important;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card {
    border-radius: 8px !important;
    border: none !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
}

/* Table styling */
.table-responsive {
    border-radius: 8px !important;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.table th {
    border: none;
    font-weight: 600;
    font-size: 0.85rem;
    letter-spacing: 0.3px;
    padding: 1rem 0.75rem;
    background: #ecf0f1 !important;
}

.table td {
    border: none;
    border-bottom: 1px solid #ecf0f1;
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.feedback-row:hover {
    background: rgba(52, 73, 94, 0.05) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-radius: 4px;
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 4px !important;
    padding: 0.4rem 0.8rem;
}

/* Button styling */
.read-more-btn {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.read-more-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,123,255,0.3);
}

/* Modal styling */
.modal-xl {
    max-width: 90%;
}

.modal-content {
    border-radius: 8px !important;
    border: none !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    padding: 1.5rem 2rem;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 1.5rem 2rem;
}

/* Scrollbar styling */
.message-text::-webkit-scrollbar {
    width: 6px;
}

.message-text::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

.message-text::-webkit-scrollbar-thumb {
    background: #6c757d;
    border-radius: 3px;
}

.message-text::-webkit-scrollbar-thumb:hover {
    background: #495057;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table {
        font-size: 0.8rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .modal-xl {
        max-width: 95%;
        margin: 0.5rem;
    }
    
    .modal-header, .modal-footer {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 0.5rem !important;
    }
    
    .modal-dialog {
        margin: 0.25rem;
    }
    
    .modal-xl {
        max-width: 100%;
    }
}

/* Print styles */
@media print {
    body {
        background: white !important;
    }
    
    .card {
        box-shadow: none !important;
        background: white !important;
    }
    
    .btn, .modal {
        display: none !important;
    }
    
    .table {
        font-size: 0.7rem !important;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
// Global variables to store current modal data
let currentModalData = {};

document.addEventListener('DOMContentLoaded', function() {
    // Handle Read More button clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.read-more-btn')) {
            const button = e.target.closest('.read-more-btn');
            
            // Store all data in global variable for later use
            currentModalData = {
                message: button.getAttribute('data-message'),
                name: button.getAttribute('data-name'),
                email: button.getAttribute('data-email'),
                phone: button.getAttribute('data-phone'),
                gender: button.getAttribute('data-gender'),
                age: button.getAttribute('data-age'),
                contactType: button.getAttribute('data-contact-type'),
                reference: button.getAttribute('data-reference'),
                incidentDate: button.getAttribute('data-incident-date'),
                location: button.getAttribute('data-location'),
                education: button.getAttribute('data-education'),
                loanStatus: button.getAttribute('data-loan-status'),
                consent: button.getAttribute('data-consent'),
                date: button.getAttribute('data-date'),
                wordCount: button.getAttribute('data-word-count')
            };
            
            // Populate modal fields
            document.getElementById('modalSenderName').textContent = currentModalData.name;
            document.getElementById('modalSenderEmail').textContent = currentModalData.email;
            document.getElementById('modalMessageDate').textContent = currentModalData.date;
            document.getElementById('modalWordCount').textContent = currentModalData.wordCount + ' words';
            document.getElementById('modalFullMessage').textContent = currentModalData.message;
            
            // Additional details
            document.getElementById('modalPhone').textContent = currentModalData.phone;
            document.getElementById('modalGenderAge').textContent = `${currentModalData.gender}, Age ${currentModalData.age}`;
            document.getElementById('modalContactType').textContent = currentModalData.contactType;
            document.getElementById('modalConsent').textContent = currentModalData.consent;
            document.getElementById('modalReference').textContent = currentModalData.reference;
            document.getElementById('modalEducation').textContent = currentModalData.education;
            document.getElementById('modalLoanStatus').textContent = currentModalData.loanStatus;
            document.getElementById('modalIncidentDate').textContent = currentModalData.incidentDate;
            document.getElementById('modalLocation').textContent = currentModalData.location;
        }
    });
});

// Copy message function
function copyFullMessage() {
    const messageText = document.getElementById('modalFullMessage').textContent;
    
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(messageText).then(() => {
            showCopySuccess();
        }).catch(() => {
            fallbackCopy(messageText);
        });
    } else {
        fallbackCopy(messageText);
    }
}

function fallbackCopy(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showCopySuccess();
    } catch (err) {
        console.error('Failed to copy text: ', err);
    }
    
    document.body.removeChild(textArea);
}

function showCopySuccess() {
    const copyBtn = document.querySelector('[onclick="copyFullMessage()"]');
    const originalText = copyBtn.innerHTML;
    copyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
    copyBtn.classList.remove('btn-primary');
    copyBtn.classList.add('btn-success');
    
    setTimeout(() => {
        copyBtn.innerHTML = originalText;
        copyBtn.classList.remove('btn-success');
        copyBtn.classList.add('btn-primary');
    }, 2500);
}

// Print single message function
function printSingleMessage() {
    const data = currentModalData;
    
    const printWindow = window.open('', '_blank');
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Citizen Feedback Report - ${data.name}</title>
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    padding: 30px; 
                    line-height: 1.6; 
                    color: #333;
                    background: #ffffff;
                }
                .header { 
                    background: #34495e; 
                    color: white; 
                    padding: 20px; 
                    border-radius: 8px; 
                    margin-bottom: 30px;
                    text-align: center;
                }
                .header h1 { 
                    margin: 0 0 5px 0; 
                    font-size: 1.8rem; 
                    font-weight: 700;
                }
                .header p { 
                    margin: 0; 
                    opacity: 0.9; 
                    font-size: 1rem;
                }
                .info-section { 
                    background: #f8f9fa; 
                    padding: 20px; 
                    border-radius: 8px; 
                    margin-bottom: 20px;
                    border: 1px solid #dee2e6;
                }
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 15px;
                }
                .info-item { 
                    background: #ffffff;
                    padding: 12px;
                    border-radius: 6px;
                    border: 1px solid #e9ecef;
                }
                .info-label { 
                    font-weight: 600; 
                    color: #495057; 
                    display: block;
                    font-size: 0.85rem;
                    margin-bottom: 4px;
                    text-transform: uppercase;
                    letter-spacing: 0.3px;
                }
                .info-value {
                    font-size: 1rem;
                    color: #212529;
                    font-weight: 500;
                }
                .message-section { 
                    background: #f8f9fa; 
                    padding: 20px; 
                    border-radius: 8px;
                    border: 1px solid #dee2e6;
                }
                .message-title {
                    color: #495057;
                    font-size: 1.2rem;
                    font-weight: 600;
                    margin-bottom: 15px;
                    padding-bottom: 10px;
                    border-bottom: 2px solid #dee2e6;
                }
                .message-content { 
                    white-space: pre-wrap; 
                    font-size: 1rem; 
                    line-height: 1.7;
                    color: #212529;
                    background: #ffffff;
                    padding: 15px;
                    border-radius: 6px;
                    border: 1px solid #e9ecef;
                }
                .footer {
                    margin-top: 30px;
                    text-align: center;
                    font-size: 0.9rem;
                    color: #6c757d;
                    border-top: 2px solid #dee2e6;
                    padding-top: 15px;
                }
                .government-seal {
                    text-align: center;
                    margin-bottom: 20px;
                    padding: 15px;
                    background: #e9ecef;
                    border-radius: 8px;
                }
                @media print {
                    body { padding: 15px; }
                    .info-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
                    .info-item { padding: 8px; }
                }
            </style>
        </head>
        <body>
            <div class="government-seal">
                <h2 style="color: #34495e; margin: 0;">Higher Education Students' Loans Board (HESLB)</h2>
                <p style="margin: 5px 0 0 0; color: #6c757d;">Individual Feedback Record</p>
            </div>
            
            <div class="info-section">
                <h3 style="margin-bottom: 15px; color: #495057;">📋 Personal Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">👤 Full Name:</span>
                        <div class="info-value">${data.name}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📧 Email Address:</span>
                        <div class="info-value">${data.email}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📞 Phone Number:</span>
                        <div class="info-value">${data.phone}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">👥 Demographics:</span>
                        <div class="info-value">${data.gender}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📝 Contact Type:</span>
                        <div class="info-value">${data.contactType}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">✅ Consent Given:</span>
                        <div class="info-value">${data.consent}</div>
                    </div>
                </div>
            </div>

       
            
            <div class="message-section">
                <div class="message-title">
                    💬 Message Content (${data.wordCount} words)
                </div>
                <div class="message-content">${data.message}</div>
            </div>
            
            <div class="footer">
                <p><strong>Generated on:</strong> ${new Date().toLocaleString()}</p>
                <p><strong>System:</strong> HESLB Contact Form Management System</p>
                <p><strong>Document ID:</strong> FB-${Date.now()}</p>
            </div>
        </body>
        </html>
    `;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
}

// Export all reports to PDF
function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4'); // Landscape orientation
    
    // Get table data
    const table = document.getElementById('feedbackTable');
    const rows = [];
    
    // Add header
    const headers = ['#', 'Name', 'Email', 'Phone', 'Gender', 'Type', 'Message Preview', 'Date'];
    
    // Add data rows
    const tableRows = table.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        const cells = row.querySelectorAll('td');
        const rowData = [
            (index + 1).toString(),
            cells[1].textContent.trim(),
            cells[2].querySelector('span').textContent.trim(),
            cells[2].querySelectorAll('span')[1].textContent.trim(),
            cells[3].querySelector('.badge').textContent.trim(),
            cells[3].querySelector('.text-muted').textContent.replace('Age: ', '').trim(),
            cells[4].querySelector('.badge').textContent.trim(),
            cells[5].querySelector('p').textContent.substring(0, 50) + '...',
            cells[6].querySelector('strong') ? cells[6].querySelector('strong').nextSibling.textContent.trim() : 'N/A',
            cells[7].textContent.trim() || 'N/A',
            cells[8].querySelector('.badge').textContent.trim(),
            cells[9].querySelector('.fw-semibold').textContent.trim()
        ];
        rows.push(rowData);
    });
    
    // Add title
    doc.setFontSize(18);
    doc.setTextColor(52, 73, 94);
    doc.text('HESLB - Citizen Feedback Reports', 148, 20, { align: 'center' });
    
    // Add subtitle
    doc.setFontSize(12);
    doc.setTextColor(108, 117, 125);
    doc.text(`Generated on: ${new Date().toLocaleString()}`, 148, 28, { align: 'center' });
    doc.text(`Total Records: ${rows.length}`, 148, 34, { align: 'center' });
    
    // Add table
    doc.autoTable({
        head: [headers],
        body: rows,
        startY: 45,
        styles: {
            fontSize: 8,
            cellPadding: 3,
            overflow: 'linebreak',
            halign: 'left'
        },
        headStyles: {
            fillColor: [52, 73, 94],
            textColor: 255,
            fontSize: 9,
            fontStyle: 'bold'
        },
        alternateRowStyles: {
            fillColor: [248, 249, 250]
        },
        columnStyles: {
            0: { halign: 'center', cellWidth: 15 },
            1: { cellWidth: 25 },
            2: { cellWidth: 35 },
            3: { cellWidth: 25 },
            4: { cellWidth: 15 },
            5: { cellWidth: 12 },
            6: { cellWidth: 20 },
            7: { cellWidth: 40 },
            8: { cellWidth: 20 },
            9: { cellWidth: 25 },
            10: { cellWidth: 15 },
            11: { cellWidth: 25 }
        },
        margin: { top: 45, right: 14, bottom: 20, left: 14 },
        didDrawPage: function (data) {
            // Add footer
            doc.setFontSize(8);
            doc.setTextColor(108, 117, 125);
            doc.text('HESLB Contact Form Management System', 14, doc.internal.pageSize.height - 10);
            doc.text(`Page ${data.pageNumber}`, doc.internal.pageSize.width - 14, doc.internal.pageSize.height - 10, { align: 'right' });
        }
    });
    
    // Save the PDF
    doc.save(`HESLB_Feedback_Reports_${new Date().toISOString().split('T')[0]}.pdf`);
    
    // Show success message
    showExportSuccess();
}

function showExportSuccess() {
    const exportBtn = document.querySelector('[onclick="exportToPDF()"]');
    const originalText = exportBtn.innerHTML;
    exportBtn.innerHTML = '<i class="fas fa-check me-1 text-success"></i>Exported!';
    exportBtn.classList.add('btn-success');
    exportBtn.classList.remove('btn-light');
    
    setTimeout(() => {
        exportBtn.innerHTML = originalText;
        exportBtn.classList.remove('btn-success');
        exportBtn.classList.add('btn-light');
    }, 3000);
}

// Print all reports function
function printAllReports() {
    const table = document.getElementById('feedbackTable');
    const tableRows = table.querySelectorAll('tbody tr');
    const totalRecords = tableRows.length;
    
    const printWindow = window.open('', '_blank');
    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>HESLB - All Feedback Reports</title>
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    padding: 20px; 
                    line-height: 1.4; 
                    color: #333;
                    background: #ffffff;
                }
                .header { 
                    background: #34495e; 
                    color: white; 
                    padding: 20px; 
                    border-radius: 8px; 
                    margin-bottom: 30px;
                    text-align: center;
                    page-break-after: avoid;
                }
                .header h1 { 
                    margin: 0 0 10px 0; 
                    font-size: 1.8rem; 
                    font-weight: 700;
                }
                .header p { 
                    margin: 0; 
                    opacity: 0.9; 
                    font-size: 1rem;
                }
                .government-seal {
                    text-align: center;
                    margin-bottom: 20px;
                    padding: 15px;
                    background: #f8f9fa;
                    border-radius: 8px;
                    border: 2px solid #dee2e6;
                    page-break-after: avoid;
                }
                .summary-section {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 8px;
                    margin-bottom: 30px;
                    border: 1px solid #dee2e6;
                    page-break-after: avoid;
                }
                .feedback-item {
                    background: #ffffff;
                    border: 1px solid #dee2e6;
                    border-radius: 8px;
                    padding: 20px;
                    margin-bottom: 25px;
                    page-break-inside: avoid;
                }
                .feedback-header {
                    background: #34495e;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 6px;
                    margin-bottom: 15px;
                }
                .feedback-info {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 10px;
                    margin-bottom: 15px;
                }
                .info-item {
                    background: #f8f9fa;
                    padding: 8px 12px;
                    border-radius: 4px;
                    border: 1px solid #e9ecef;
                }
                .info-label {
                    font-weight: 600;
                    color: #495057;
                    font-size: 0.85rem;
                    display: block;
                    margin-bottom: 3px;
                }
                .info-value {
                    color: #212529;
                    font-size: 0.95rem;
                }
                .message-content {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 6px;
                    border: 1px solid #e9ecef;
                    white-space: pre-wrap;
                    line-height: 1.6;
                    font-size: 0.95rem;
                }
                .page-break {
                    page-break-before: always;
                }
                @media print {
                    body { padding: 10mm; font-size: 10pt; }
                    .feedback-item { margin-bottom: 15mm; padding: 15px; }
                    .feedback-info { grid-template-columns: repeat(2, 1fr); gap: 8px; }
                    .info-item { padding: 6px 10px; }
                }
                @page {
                    margin: 15mm;
                    @bottom-right {
                        content: counter(page) " of " counter(pages);
                    }
                }
            </style>
        </head>
        <body>
            <div class="government-seal">
                <h2 style="color: #34495e; margin: 0;">🏛️ GOVERNMENT OF TANZANIA</h2>
                <p style="margin: 5px 0 0 0; color: #6c757d;">Higher Education Students' Loans Board (HESLB)</p>
            </div>
            
            <div class="header">
                <h1>📊 Complete Citizen Feedback Reports</h1>
                <p>Comprehensive Report of All Submitted Feedback</p>
            </div>
            
            <div class="summary-section">
                <h3 style="margin-bottom: 10px; color: #34495e;">📋 Report Summary</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <div style="text-align: center; padding: 10px; background: white; border-radius: 6px; border: 1px solid #dee2e6;">
                        <strong style="display: block; font-size: 1.5rem; color: #34495e;">${totalRecords}</strong>
                        <span style="font-size: 0.9rem; color: #6c757d;">Total Records</span>
                    </div>
                    <div style="text-align: center; padding: 10px; background: white; border-radius: 6px; border: 1px solid #dee2e6;">
                        <strong style="display: block; font-size: 1.2rem; color: #34495e;">${new Date().toLocaleDateString()}</strong>
                        <span style="font-size: 0.9rem; color: #6c757d;">Report Date</span>
                    </div>
                    <div style="text-align: center; padding: 10px; background: white; border-radius: 6px; border: 1px solid #dee2e6;">
                        <strong style="display: block; font-size: 1.2rem; color: #34495e;">FB-${Date.now()}</strong>
                        <span style="font-size: 0.9rem; color: #6c757d;">Report ID</span>
                    </div>
                </div>
            </div>
    `;
    
    // Add individual feedback records
    tableRows.forEach((row, index) => {
        const cells = row.querySelectorAll('td');
        
        // Extract data from table cells
        const name = cells[1].textContent.trim();
        const email = cells[2].querySelector('span').textContent.trim();
        const phone = cells[2].querySelectorAll('span')[1].textContent.trim();
        const gender = cells[3].querySelector('.badge').textContent.trim();
        const age = cells[3].querySelector('.text-muted').textContent.replace('Age: ', '').trim();
        const contactType = cells[4].querySelector('.badge').textContent.trim();
        const message = cells[5].querySelector('p').textContent.trim();
        const reference = cells[6].querySelector('strong') ? cells[6].querySelector('strong').nextSibling.textContent.trim() : 'N/A';
        const education = cells[7].textContent.trim() || 'N/A';
        const consent = cells[8].querySelector('.badge').textContent.trim();
        const dateSubmitted = cells[9].querySelector('.fw-semibold').textContent.trim();
        const timeSubmitted = cells[9].querySelector('.text-muted').textContent.trim();
        
        printContent += `
            <div class="feedback-item">
                <div class="feedback-header">
                    <h4 style="margin: 0; font-size: 1.1rem;">📝 Feedback Record #${index + 1}</h4>
                </div>
                
                <div class="feedback-info">
                    <div class="info-item">
                        <span class="info-label">👤 Full Name:</span>
                        <div class="info-value">${name}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📧 Email:</span>
                        <div class="info-value">${email}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📞 Phone:</span>
                        <div class="info-value">${phone}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">👥 Demographics:</span>
                        <div class="info-value">${gender}, Age ${age}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📝 Contact Type:</span>
                        <div class="info-value">${contactType}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">🔢 Reference:</span>
                        <div class="info-value">${reference}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">🎓 Education:</span>
                        <div class="info-value">${education}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">✅ Consent:</span>
                        <div class="info-value">${consent}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📅 Date Submitted:</span>
                        <div class="info-value">${dateSubmitted} at ${timeSubmitted}</div>
                    </div>
                </div>
                
                <div style="margin-top: 15px;">
                    <h5 style="margin-bottom: 8px; color: #495057; font-size: 1rem;">💬 Message Content:</h5>
                    <div class="message-content">${message}</div>
                </div>
            </div>
        `;
        
        // Add page break every 2 records for better printing
        if ((index + 1) % 2 === 0 && index < totalRecords - 1) {
            printContent += '<div class="page-break"></div>';
        }
    });
    
    printContent += `
            <div style="margin-top: 30px; text-align: center; font-size: 0.9rem; color: #6c757d; border-top: 2px solid #dee2e6; padding-top: 15px;">
                <p><strong>End of Report</strong></p>
                <p>Generated on: ${new Date().toLocaleString()}</p>
                <p>HESLB Contact Form Management System</p>
                <p>Total Records Printed: ${totalRecords}</p>
            </div>
        </body>
        </html>
    `;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
    
    // Show success message
    showPrintSuccess();
}

function showPrintSuccess() {
    const printBtn = document.querySelector('[onclick="printAllReports()"]');
    const originalText = printBtn.innerHTML;
    printBtn.innerHTML = '<i class="fas fa-check me-1 text-success"></i>Printing...';
    
    setTimeout(() => {
        printBtn.innerHTML = originalText;
    }, 3000);
}
</script>
@endsection