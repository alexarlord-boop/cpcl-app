@if(session()->has('pathFileToParse'))
    <ul class="nav nav-bar ml-3">
        <li class="btn-group ">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
               data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <i class="bi bi-gear text-primary "></i>
            </a>
            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{route('clear.cache')}}">
                    Close</a>
                <a class="dropdown-item" href="#">Download</a>
                <a class="dropdown-item" href="{{route('delete.file', ['filename' => explode('/', session('pathFileToParse'))[1]])}}">Delete</a>
            </div>
        </li>
    </ul>
@endif
