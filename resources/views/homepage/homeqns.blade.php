@php
    $sections = __('homeqns');
@endphp

<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; padding: 40px 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8f9fa;">
    @foreach ($sections as $index => $item)
        <a href="{{ $item['link'] }}" 
           style="display: flex; width: 500px; height: 240px; background: white; color: #fff; text-decoration: none; overflow: hidden; border-radius: 6px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); transition: all 0.3s ease; border-left: 5px solid #0c1f38;">

            @if($index % 2 === 0)
                <!-- Image Block (Left) -->
                <div style="width: 35%; height: 100%; position: relative; overflow: hidden;">
                    <img src="{{ asset($item['image']) }}" 
                         onerror="this.style.display='none'; this.parentNode.style.background='linear-gradient(135deg, #0c1f38, #1a3a6a)'" 
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" 
                         alt="{{ $item['title'] }}">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 40px; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);"></div>
                </div>

                <!-- Text Block (Right) -->
                <div style="flex: 1; padding: 25px; display: flex; flex-direction: column; background: #0e9bd5; color: #fff;">
                    <div style="font-size: 28px; margin-bottom: 10px;">{{ $item['icon'] }}</div>
                    <h3 style="margin: 0 0 10px 0; font-size: 1.3rem; font-weight: 600;">{{ $item['title'] }}</h3>
                    <div style="width: 40px; height: 3px; background: #007bbf; margin-bottom: 15px;"></div>
                    <p style="margin: 0; font-size: 0.95rem; line-height: 1.5; flex-grow: 1;">{{ $item['description'] }}</p>
                    <div style="margin-top: 15px; font-weight: 500; display: flex; align-items: center; color: #d0eaff;">
                    {{ __('vocabulary.learnmore') }}
                        <span style="margin-left: 5px; transition: transform 0.2s ease;">→</span>
                    </div>
                </div>
            @else
                <!-- Text Block (Left) -->
                <div style="flex: 1; padding: 25px; display: flex; flex-direction: column; background: #0e9bd5; color: #fff;">
                    <div style="font-size: 28px; margin-bottom: 10px;">{{ $item['icon'] }}</div>
                    <h3 style="margin: 0 0 10px 0; font-size: 1.3rem; font-weight: 600;">{{ $item['title'] }}</h3>
                    <div style="width: 40px; height: 3px; background: #007bbf; margin-bottom: 15px;"></div>
                    <p style="margin: 0; font-size: 0.95rem; line-height: 1.5; flex-grow: 1;">{{ $item['description'] }}</p>
                    <div style="margin-top: 15px; font-weight: 500; display: flex; align-items: center; color: #d0eaff;">
                    {{ __('vocabulary.learnmore') }}
                        <span style="margin-left: 5px; transition: transform 0.2s ease;">→</span>
                    </div>
                </div>

                <!-- Image Block (Right) -->
                <div style="width: 35%; height: 100%; position: relative; overflow: hidden;">
                    <img src="{{ asset($item['image']) }}" 
                         onerror="this.style.display='none'; this.parentNode.style.background='linear-gradient(135deg, #0c1f38, #1a3a6a)'" 
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" 
                         alt="{{ $item['title'] }}">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 40px; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);"></div>
                </div>
            @endif

        </a>
    @endforeach
</div>
