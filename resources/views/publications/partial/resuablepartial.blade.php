@extends('publications.publications')

@section('publication-content')

<style>
    .download-card {
        display: flex;
        align-items: center;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .download-icon {
        width: 120px;
        height: 120px;
        flex-shrink: 0;
        background: url('{{ asset("images/static_files/file-pdf.svg") }}') no-repeat center center;
        background-size: contain;
    }

    .separator {
        width: 1px;
        height: 100px;
        background-color: #ccc;
        margin: 0 30px;
    }

    .download-content {
        flex: 1;
        padding-right: 30px;
    }

    .download-title {
        font-size: 1.75rem;
        font-weight: 400;
        color: #2c3e50;
        margin: 0;
    }

    .button-group {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .download-button,
    .read-button {
        background-color: #3498db;
        color: #fff;
        border: none;
        padding: 14px 35px;
        border-radius: 40px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 1rem;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s ease;
        display: inline-block;
        white-space: nowrap;
    }

    .read-button {
        background-color: #2ecc71;
    }

    .download-button:hover {
        background-color: #2980b9;
    }

    .read-button:hover {
        background-color: #27ae60;
    }

    @media (max-width: 768px) {
        .download-card {
            flex-direction: column;
            text-align: center;
            padding: 30px 20px;
        }

        .separator {
            display: none;
        }

        .download-content {
            padding: 20px 0;
        }

        .button-group {
            flex-direction: row;
            justify-content: center;
            flex-wrap: wrap;
        }

        .button-group a {
            margin: 5px;
        }
    }
</style>

<div class="container my-5">
    @foreach($items as $item)
        <div class="download-card">
            <div class="download-icon"></div>
            <div class="separator"></div>
            <div class="download-content">
                <p class="download-title">{{ $item['title'] }}</p>
            </div>
            <div class="button-group">
                <a href="#" class="download-button" target="_blank">{{ __('publications.download') }}</a>
                <a href="#" class="read-button" target="_blank">{{ __('publications.read') }}</a>
            </div>
        </div>
    @endforeach
</div>
@endsection
