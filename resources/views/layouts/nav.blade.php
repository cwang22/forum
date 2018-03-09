<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Browse</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/threads">All Threads</a>
                        <a class="dropdown-item" href="/threads?popular=1">Popular Threads</a>
                        <a class="dropdown-item" href="/threads?unanswered=1">Unanswered Threads</a>
                        @if(auth()->check())
                            <a class="dropdown-item" href="{{ '/threads?by=' . auth()->user()->username }}">My Threads</a>
                        @endif
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Channels</a>
                    <div class="dropdown-menu">
                        @foreach($channels as $channel)
                            <a class="dropdown-item" href="{{ '/threads/' . $channel->slug }}">{{$channel->name}}</a>
                        @endforeach
                    </div>
                </li>
                @if(auth()->check())
                    <li class="nav-item"><a class="nav-link" href="/threads/create">New Thread</a></li>
                @endif
            </ul>

            <form class="form-inline my-2 my-lg-0" action="/threads/search">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="q">
            </form>

            <ul class="navbar-nav">
                @if (Auth::guest())
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @else
                    <user-notifications></user-notifications>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</a>

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('profile', auth()->user()) }}">Profile</a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>