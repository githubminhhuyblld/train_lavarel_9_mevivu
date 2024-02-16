<header>
    <nav style="background-color:{{ $backgroundColor }};" class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Mevivu</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @foreach($menuItems as $menuItem)
                            @if($menuItem->status == 1)
                                <li style="font-size: {{ $menuFont }}px;" class="nav-item">
                                    <a style="color:{{$menuColor}}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url($menuItem->slug) }}">
                                        {{ $menuItem->title }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </ul>

                <ul class="navbar-nav ml-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">Hello, {{ auth()->user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="display: none;" id="logout-form">
                                @csrf
                            </form>
                            <a href="#" class="btn btn-outline-light me-2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>
