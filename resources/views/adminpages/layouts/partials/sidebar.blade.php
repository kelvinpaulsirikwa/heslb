{{-- resources/views/layouts/sidebar.blade.php --}}

@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;

    $user = Auth::user();
    $role = $user->role ?? '';
    $email = $user->email ?? 'noemail';
    
    // Check if user has profile image and if the file actually exists
    $avatar = asset('images/static_files/nodp.png'); // Default fallback
    
    if ($user && $user->profile_image) {
        $imagePath = 'images/storage/' . $user->profile_image;
        $fullPath = public_path($imagePath);
        
        // Check if the file actually exists
        if (file_exists($fullPath)) {
            $avatar = asset($imagePath);
        }
        // If file doesn't exist, keep the default nodp.png
    }
        
@endphp

<section id="sidebar" class="hide">
    <!-- User Info -->
    <br><br>
    <div class="form-group d-flex align-items-center ps-4">
        <!-- User/Profile Image -->
        <img src="{{ $avatar }}" 
             alt="User" 
             class="rounded-circle me-3" 
             style="width: 50px; height: 50px; object-fit: cover;"
             onerror="handleImageError(this)">

        <!-- User Details -->
        <ul class="list-unstyled mb-0">
            <li class="text-muted small">{{ $email }}</li>
            <li class="text-muted small">{{ $role }}</li>
        </ul>
    </div>

    <ul class="side-menu">
        <!-- Common Links (user + admin) -->
        <li>
            <a href="{{ route('dashboard') }}" class="active">
                <i class="bx bxs-dashboard icon"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('home') }}">
                <i class="bx bxs-home icon"></i>
                Website Home
            </a>
        </li>

        <li class="divider" data-text="Content Management"></li>

        <li>
            <a href="{{ route('admin.news.index') }}">
                <i class="bx bxs-news icon"></i>
                News and Event
            </a>
        </li>
        <li>
            <a href="{{ route('adminpages.feedback.index') }}">
                <i class="bx bxs-comment-detail icon"></i>
                Feedbacks
            </a>
        </li>
        <li>
            <a href="{{ route('admin.window_applications.index') }}">
                <i class="bx bxs-time icon"></i>
                Dirisha la Usajili
            </a>
        </li>
        <li>
            <a href="{{ route('admin.publications.index') }}">
                <i class="bx bxs-group icon"></i>
                Publications
            </a>
        </li>
        <li>
            <a href="{{ route('admin.taasisevents.index') }}">
                <i class="bx bxs-photo-album icon"></i>
                Photo Gallery
            </a>
        </li>
        <li>
            <a href="{{ route('videopodcasts.index') }}">
                <i class="bx bxs-videos icon"></i>
                Video Podcasts
            </a>
        </li>
        <li>
            <a href="{{ route('admin.scholarships.index') }}">
                <i class="bx bxs-graduation icon"></i>
                Scholarships
            </a>
        </li>
        <li>
            <a href="{{ route('shortcut-links.index') }}">
                <i class="bx bx-link-alt icon"></i>
                Shortcut Links
            </a>
        </li>
        <li>
            <a href="{{ route('admin.partners.index') }}">
                <i class="bx bxs-group icon"></i>
                Strategic Partners
            </a>
        </li>
        <li>
            <a href="{{ route('admin.board-of-directors.index') }}">
                <i class="bx bxs-user-badge icon"></i>
                Board of Directors
            </a>
        </li>
        <li>
            <a href="{{ route('admin.executive-directors.index') }}">
                <i class="bx bxs-user-circle icon"></i>
                Executive Directors
            </a>
        </li>
        <li>
            <a href="{{ route('admin.application-guidelines.index') }}">
                <i class="bx bxs-file-doc icon"></i>
                Application Guidelines
            </a>
        </li>

        <!-- FAQ Dropdown -->
        <li>
            <a href="#">
                <i class='bx bxs-inbox icon'></i> FAQ 
                <i class='bx bx-chevron-right icon-right'></i>
            </a>
            <ul class="side-dropdown">
                <li><a href="{{ route('loan-application-faqs.index') }}">Loan Application</a></li>
                <li><a href="{{ route('loan-repayment-faqs.index') }}">Loan Repayment</a></li>
            </ul>
        </li>
      
        <li>
            <a href="{{ route('admin.user-stories.index') }}">
                <i class="bx bxs-book icon"></i>
                User Stories
            </a>
        </li>
        <li>
    <a href="{{ route('validation-documentation') }}">
        <i class="bx bxs-file-doc icon"></i>
        Validation Rules
    </a>
</li>


        <!-- Role-specific Links -->
        @if ($role === 'admin')
            <li class="divider" data-text="Account Management"></li>
            <li>
                <a href="{{ route('admin.users.index') }}">
                    <i class="bx bxs-user-detail icon"></i>
                    User Management
                </a>
            </li>
          
        @else
            <li class="divider" data-text="Account"></li>
        @endif

        <!-- Common Account/Profile -->
        <li>
            <a href="{{ route('profile.edit') }}">
                <i class="bx bxs-cog alt icon"></i>
                Profile Management
            </a>
        </li>

        <!-- Logout -->
        <div class="ads">
            <div class="wrapper">
                <a href="{{ route('logout') }}" 
                   class="btn-upgrade"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   LOGOUT
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <p>Please <span>logout</span> to keep your account safe.</p>
            </div>
        </div>
    </ul>
</section>
