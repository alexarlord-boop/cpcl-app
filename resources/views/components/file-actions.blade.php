@if(session()->has('pathFileToParse'))

    <div class="ml-3 btn-group dropend">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
            <i class="bi bi-gear text-primary "></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdownMenuLink">
            {{--                <a class="dropdown-item" href="{{route('clear.cache')}}">Close</a>--}}
            <li><a class="dropdown-item"
                   href="{{route('download.file', ['filename' => explode('/', session('pathFileToParse'))[1]])}}"><i
                        class="bi bi-cloud-download"></i> Download</a></li>
            <li><a class="dropdown-item"
                   href="{{route('delete.file', ['filename' => explode('/', session('pathFileToParse'))[1]])}}"><i
                        class="bi bi-trash"></i> Delete</a></li>
        </ul>
    </div>

@endif
