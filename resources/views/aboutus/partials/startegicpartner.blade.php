@extends('aboutus.aboutus')

@section('aboutus-content')

<style>
  .partner-container {
    padding: 60px 15px;
    background-color: #ffffff; /* clean white background */
    min-height: 100vh;
    width: 100%;
    margin: 0 auto;
    padding: 0;
  }

  .partner-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* exactly 3 per row */
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
  }

  .partner-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 16px;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    color: #1c3f91;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* keeps button at bottom */
    align-items: center;
    padding: 25px 15px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    min-height: 280px; /* ensures uniform height */
  }

  .partner-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
  }

  .partner-logo {
    max-height: 110px;
    max-width: 90%;
    margin-bottom: 15px;
    filter: drop-shadow(0 0 3px rgba(28, 63, 145, 0.4));
  }

  .partner-name {
    font-size: 1.2rem;
    font-weight: 700;
    margin: 10px 0;
    letter-spacing: 0.04em;
    word-break: break-word; /* handles long names */
    flex-grow: 1; /* makes name take available space */
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

  .partner-link {
    font-weight: 600;
    color: #1c3f91;
    text-decoration: none;
    border: 2px solid #1c3f91;
    padding: 8px 16px;
    border-radius: 40px;
    transition: background-color 0.3s ease, color 0.3s ease;
    font-size: 0.9rem;
    margin-top: auto; /* pushes it to bottom */
  }

  .partner-link:hover {
    background-color: #1c3f91;
    color: #ffffff;
    text-decoration: none;
  }

  /* Responsive: 2 per row on tablets, 1 per row on phones */
  @media (max-width: 992px) {
    .partner-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 600px) {
    .partner-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="partner-container">
  <div class="partner-grid">
    @foreach($strategicPartners as $partner)
      <div class="partner-card">
        <img src="{{ asset($partner['logo']) }}" alt="{{ $partner['acronym_name'] }}" class="partner-logo" />
        <div class="partner-name">{{ $partner['acronym_name'] }}</div>
        <a href="{{ $partner['url'] }}" target="_blank" class="partner-link">{{ __('gallery.visit_site') }}</a>
      </div>
    @endforeach
  </div>
</div>

@endsection
