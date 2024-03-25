@extends('layouts.master')
@section('title', 'Proxy configuration')

@section('content')
    <div class="container">
        <p class="h3 mt-3">Proxy configuration</p>

        <div class="container row mt-2 d-flex justify-content-between align-items-center">

            <!-- ... Get config from uploads ... -->
            <form action="/" method="post" enctype="multipart/form-data">
                @csrf
                <div class="input-group input-group mb-3">
                    <select class="custom-select " name="uploadedFile" required>
                        <?php

                        // List files in the uploads/ directory
                        $uploadDir = 'uploads/';
                        $files = scandir($uploadDir);

                        foreach ($files as $file) {
                            if ($file != "." && $file != "..") {

                                if (session('uploaded_file') === $file) {
                                    echo "<option selected value=\"$uploadDir$file\">$file</option>";
                                } else {
                                    echo "<option value=\"$uploadDir$file\">$file</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="input-group-append btn btn-outline-primary">Get from server
                        </button>
                    </div>
                    <ul class="nav nav-bar ml-3">
                        <li class="btn-group ">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-gear text-primary "></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="{{route('clear.cache')}}">
                                    Close</a>
                                <a class="dropdown-item" href="#">Download</a>
                                <a class="dropdown-item" href="#">Delete</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </form>

            <!-- ... Upload config from pc ... -->
            <form action="/" method="post" enctype="multipart/form-data">
                @csrf
                <div class="input-group input-group mb-3">
                    <div class="input-group-prepend">
                        <button type="submit" class="input-group-append btn btn-outline-primary">⬆upload</button>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="file" name="file" accept=".yaml" required>
                        <label class="custom-file-label" for="file">Choose file</label>
                    </div>
                </div>
            </form>

        </div>

        <!-- ... Config file content divided in sections ... -->
        @if(isset($entities))
            @if(isset($fileName))
                <div class="mt-2">

                </div>
            @endif

            @php
//                $inSection = array_filter($entities, function ($dto) {
//                    return $dto->getSection() === 'in';
//                });
//                $outSection = array_filter($entities, function ($dto) {
//                    return $dto->getSection() === 'out';
//                });

                $samlSection = array_filter($entities, function ($dto) {
                    return $dto->getProtocol() === 'saml';
                });

                 $oidcSection = array_filter($entities, function ($dto) {
                    return $dto->getProtocol() === 'oidc';
                });

                $rulesSection = $rules;
                // parse each section into special DTOs

            @endphp

{{--            <x-config-sections-bydirection :inSection="$inSection" :outSection="$outSection" :rulesSection="$rulesSection"/>--}}
            <x-config-sections-byprotocol :samlSection="$samlSection" :oidcSection="$oidcSection" :rulesSection="$rulesSection"/>

        @endif

    </div>
@endsection

@section('styles')
    <!-- Additional styles specific to this page -->
@endsection

@section('scripts')
    <!-- Additional scripts specific to this page -->
@endsection
