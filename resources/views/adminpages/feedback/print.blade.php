<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Report - #{{ str_pad($contact->id, 4, '0', STR_PAD_LEFT) }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: white;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .header-logo {
            width: 100%;
            height: auto;
            display: block;
            margin: 0;
            padding: 0;
        }
        
        .print-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }
        
        .print-header .subtitle {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        
        .section-title {
            background-color: #f8f9fa;
            padding: 8px 12px;
            margin: 20px 0 10px 0;
            border-left: 4px solid #0e9bd5;
            font-weight: bold;
            font-size: 14px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 12px;
            color: #000;
        }
        
        .message-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-reviewed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .footer-info {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        
        /* Watermark styles */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0,0,0,0.05);
            z-index: -1;
            pointer-events: none;
            font-weight: bold;
            white-space: nowrap;
        }
        
        .user-watermark {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: rgba(0,0,0,0.7);
            background: rgba(255,255,255,0.9);
            padding: 8px 12px;
            border-radius: 3px;
            border: 1px solid #333;
            z-index: 1000;
        }
        
        @media print {
            body { margin: 0; }
            .container { max-width: 100% !important; }
            .no-print { display: none !important; }
            .watermark, .user-watermark {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .header-logo {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
                width: 100% !important;
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            @page { margin: 1in; size: A4; }
        }
        
        @media screen {
            body { padding: 20px; }
            .container { max-width: 800px; margin: 0 auto; }
            .no-print { 
                text-align: center; 
                margin: 20px 0; 
                padding: 15px;
                background-color: #f8f9fa;
                border-radius: 5px;
            }
        }
    </style>
</head>
<body>
    <!-- Background Watermark -->
    <div class="watermark">HESLB CONFIDENTIAL</div>
    
    <!-- User Watermark -->
   

    <div class="container">
        <!-- Print Controls (only visible on screen) -->
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i>Print Document
            </button>
            <button onclick="window.close()" class="btn btn-secondary ms-2">
                <i class="fas fa-times me-2"></i>Close
            </button>
        </div>

        <!-- Header with Logo Only -->
        <div class="print-header">
            <img src="{{ asset('images/static_files/logoandcoatofarm.jpg') }}" 
                 alt="HESLB Logo and Coat of Arms" 
                 class="header-logo">
        </div>

        <!-- Contact Information -->
        <div class="section-title">
            <i class="fas fa-user me-2"></i>Contact Information
        </div>
        
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ $contact->first_name }} {{ $contact->last_name }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $contact->email }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">{{ $contact->phone }}</div>
                </div>
            </div>
            
            <div>
                <div class="info-item">
                    <div class="info-label">Gender</div>
                    <div class="info-value">{{ ucfirst($contact->gender) }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Description Type</div>
                    <div class="info-value">
                        @switch($contact->contact_type)
                            @case('suggestions')
                                Suggestions
                                @break
                            @case('inquiries')
                                Inquiries
                                @break
                            @case('success_stories')
                                Success Stories
                                @break
                            @case('complaint')
                                Complaint
                                @break
                            @case('other')
                                Other
                                @break
                            @default
                                {{ ucfirst($contact->contact_type) }}
                        @endswitch
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        @if($contact->status === 'seen')
                            <span class="status-badge status-reviewed">Reviewed</span>
                        @else
                            <span class="status-badge status-pending">Pending Review</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Details -->
        @if($contact->date_of_incident || $contact->location)
        <div class="section-title">
            <i class="fas fa-info-circle me-2"></i>Additional Details
        </div>
        
        <div class="info-grid">
            @if($contact->date_of_incident)
            <div class="info-item">
                <div class="info-label">Date of Incident</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($contact->date_of_incident)->format('F d, Y') }}</div>
            </div>
            @endif
            
            @if($contact->location)
            <div class="info-item">
                <div class="info-label">Location</div>
                <div class="info-value">{{ $contact->location }}</div>
            </div>
            @endif
        </div>
        @endif

        <!-- Message Content -->
        <div class="section-title">
            <i class="fas fa-envelope me-2"></i>Message Content
        </div>
        
        <div class="message-content">{{ $contact->message }}</div>

        <!-- Consent Information -->
        <div class="section-title">
            <i class="fas fa-shield-alt me-2"></i>Data Processing Consent
        </div>
        
        <div class="info-item">
            <div class="info-value">
                @if($contact->consent)
                    <span class="status-badge status-reviewed">Consent Provided</span>
                    - The user has provided consent for data processing
                @else
                    <span class="status-badge status-pending">No Consent</span>
                    - The user has not provided consent for data processing
                @endif
            </div>
        </div>

        <!-- Record Information -->
        <div class="section-title">
            <i class="fas fa-history me-2"></i>Record Information
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Submitted On</div>
                <div class="info-value">{{ $contact->created_at->format('F d, Y \a\t g:i A') }}</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Last Updated</div>
                <div class="info-value">{{ $contact->updated_at->format('F d, Y \a\t g:i A') }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-info">
            <div style="text-align: center;">
                <strong>Higher Education Students' Loans Board (HESLB)</strong><br>
                This document contains confidential information. Handle according to data protection policies.<br>
                Document generated on {{ now()->format('F d, Y \a\t g:i A') }} by {{ Auth::user()->username ?? 'Unknown User' }}, {{ Auth::user()->email ?? '' }}
            </div>
        </div>
    </div>

    <script>
        // Auto-print when opened in new window (optional)
        // window.addEventListener('load', function() {
        //     setTimeout(() => window.print(), 500);
        // });
    </script>
</body>
</html>
