<nav class="navbar navbar-fixed-top">
    <div class="container">
        <div class="navbar-brand">
            <a href="{{ route('index') }}"><img src="{{ asset('assets/images/logo.svg') }}" alt="Lucid Logo" class="img-responsive logo"></a>
        </div>

        <div class="navbar-right">
            <div id="navbar-menu">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="icon-menu" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="icon-login"></i>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="navbar-btn">
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                <i class="lnr lnr-menu fa fa-bars"></i>
            </button>
        </div>
    </div>
</nav>
