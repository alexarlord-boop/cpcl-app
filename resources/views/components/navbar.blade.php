<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
    <a class=" navbar-brand" href={{route("proxy.index")}}>CPCL</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class=" collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
{{--            <li class="nav-item active">--}}
{{--                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>--}}
{{--            </li>--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="#">Features</a>--}}
{{--            </li>--}}
            <li class="nav-item">
                <a class="nav-link" href="{{route("check.all")}}">Proxy</a>
            </li>
{{--            <li class="nav-item dropdown">--}}
{{--                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                    Actions--}}
{{--                </a>--}}
{{--                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">--}}
{{--                    <a class="dropdown-item" href="{{route('clear.cache')}}"><i class="bi bi-trash text-danger"></i> Clear cache</a>--}}
{{--                    <a class="dropdown-item" href="#">Another action</a>--}}
{{--                    <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                </div>--}}
{{--            </li>--}}



        </ul>
    </div>
</nav>
