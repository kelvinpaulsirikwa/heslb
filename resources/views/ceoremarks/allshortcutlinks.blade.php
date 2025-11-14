@extends('layouts.app')

@section('content')
<style>
    .section-head {
        margin-bottom: 30px;
        text-align: center;
    }

    .section-head h1 {
        color: #2c3e50;
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .section-head p {
        color: #666;
        font-size: 16px;
        margin-bottom: 20px;
    }

    .section-head::after {
        content: '';
        display: block;
        width: 100px;
        height: 4px;
        background: #3498db;
        margin: 20px auto 0;
    }

    .shortcut-link {
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        padding: 20px 25px;
        background: white;
        color: #333;
        text-decoration: none;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        margin-bottom: 15px;
        position: relative;
    }

    .shortcut-link:hover {
        background: #f8f9ff;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        color: #0e9bd5;
    }

    /* Circular icon design matching the exact image */
    .shortcut-link-icon {
        width: 40px;
        height: 40px;
        background: #3498db;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        flex-shrink: 0;
    }

    .shortcut-link-icon::before {
        content: '›';
        color: white;
        font-size: 20px;
        font-weight: bold;
        transform: translateX(1px);
    }

    .shortcut-link-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .shortcut-link-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.3;
    }

    .shortcut-link-subtitle {
        font-size: 14px;
        color: #666;
        font-weight: 400;
    }

    .back-link {
        display: inline-block;
        padding: 10px 20px;
        background: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
        margin-bottom: 30px;
    }

    .back-link:hover {
        background: #5a6268;
        color: white;
        transform: translateY(-1px);
    }

    .stats-card {
        background: linear-gradient(135deg, #0e9bd5, #3498db);
        color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(14, 155, 213, 0.3);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stats-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .file-indicator {
        display: inline-block;
        background: #28a745;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 10px;
    }

    .url-indicator {
        display: inline-block;
        background: #007bff;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 10px;
    }

    @media (max-width: 768px) {
        .links-grid {
            grid-template-columns: 1fr;
        }
        
        .shortcut-link {
            font-size: 16px;
            padding: 16px 15px;
            margin-left: 5px;
            margin-right: 5px;
        }
        
        .shortcut-link-icon {
            width: 35px;
            height: 35px;
            margin-right: 15px;
        }
        
        .shortcut-link-icon::before {
            font-size: 18px;
        }
        
        .shortcut-link-title {
            font-size: 16px;
        }
        
        .shortcut-link-subtitle {
            font-size: 13px;
        }
        
        .section-head h1 {
            font-size: 28px;
            text-align: center;
        }
        
        .section-head p {
            text-align: center;
        }
        
        /* Container padding reduction */
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
    
    @media (max-width: 480px) {
        .shortcut-link {
            padding: 12px 8px;
            margin-left: 3px;
            margin-right: 3px;
        }
        
        .shortcut-link-icon {
            width: 30px;
            height: 30px;
            margin-right: 12px;
        }
        
        .shortcut-link-icon::before {
            font-size: 16px;
        }
        
        .shortcut-link-title {
            font-size: 14px;
        }
        
        .shortcut-link-subtitle {
            font-size: 12px;
        }
        
        .container-fluid {
            padding-left: 8px;
            padding-right: 8px;
        }
    }
</style>

<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
   


    <!-- Section Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="section-head">
                <h1>{{ __('shortcutlinks.all_shortcut_links') }}</h1>
                <p>{{ __('shortcutlinks.all_links_description') }}</p>
            </div>
        </div>
    </div>

    <!-- Links Grid -->
    <div class="row">
        <div class="col-12">
            <div class="links-grid">
                @forelse ($allLinks as $link)
                    <a href="{{ $link->is_file ? asset('images/storage/' . $link->link) : $link->link }}" 
                       target="_blank"
                       class="shortcut-link">
                        <div class="shortcut-link-icon"></div>
                        <div class="shortcut-link-content">
                            <div class="shortcut-link-title">{{ $link->link_name }}</div>
                            <div class="shortcut-link-subtitle">
                                @if($link->is_file)
                                    Document File
                                @else
                                    External Link
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                        <i class="fas fa-link" style="font-size: 3rem; color: #ccc; margin-bottom: 20px;"></i>
                        <h3 style="color: #666;">No Shortcut Links Available</h3>
                        <p style="color: #999;">There are currently no shortcut links in the system.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="row mt-5">
        <div class="col-12">
            <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                <p style="color: #666; margin: 0;">
                    <i class="fas fa-info-circle me-2"></i>
                    All links open in a new tab. Files are stored locally while URLs point to external resources.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
