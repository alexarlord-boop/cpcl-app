@extends('layouts.master')
@section('title', 'Proxy configuration')

@section('content')
    <div class="container">
        <p class="h3 mt-3">Proxy configuration</p>

        <div class="container row mt-2 d-flex justify-content-between align-items-center">

            <!-- ... Get config from uploads ... -->
            <form action="/"  id="configForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="input-group input-group mb-3">
                    <select id="uploadedFile" class="custom-select" name="uploadedFile" required>
                        <option selected value="close">clear selection</option>
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
{{--                    <div class="input-group-append">--}}
{{--                        <!-- Removed button and replaced with empty div -->--}}
{{--                        <div class="input-group-append btn btn-outline-primary"></div>--}}
{{--                    </div>--}}

                    <x-file-actions/>
                </div>
            </form>

            <script>
                // Add event listener to the select element
                document.getElementById('uploadedFile').addEventListener('change', function () {
                    // Submit the form when a selection is made
                    var selectedOption = this.value;
                    if (selectedOption === 'close') {
                        // Redirect to clear cache route
                        window.location.href = "{{ route('clear.cache') }}";
                    } else {
                        // Submit the form for other options
                        document.getElementById('configForm').submit();
                    }
                });
            </script>

{{--            <!-- ... Upload config from pc ... -->--}}
{{--            <form action="/" method="post" enctype="multipart/form-data">--}}
{{--                @csrf--}}
{{--                <div class="input-group input-group mb-3">--}}
{{--                    <div class="input-group-prepend">--}}
{{--                        <button type="submit" class="input-group-append btn btn-outline-primary">⬆upload</button>--}}
{{--                    </div>--}}
{{--                    <div class="custom-file">--}}
{{--                        <input type="file" class="custom-file-input" id="file" name="file" accept=".yaml" required>--}}
{{--                        <label class="custom-file-label" for="file">Choose file</label>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
            <!-- ... Upload config from pc ... -->
            <form id="uploadForm" action="/" method="post" enctype="multipart/form-data">
                @csrf
                <div class="input-group input-group mb-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="file" name="file" accept=".yaml" required>
                        <label class="custom-file-label" for="file">Choose file</label>
                    </div>
                </div>
            </form>

            <script>
                // Get reference to the file input element
                var fileInput = document.getElementById('file');

                // Listen for change event on file input
                fileInput.addEventListener('change', function() {
                    // Submit the form when a file is selected
                    document.getElementById('uploadForm').submit();
                });
            </script>

        </div>

        <!-- ... Config file content divided in sections ... -->
        @if(isset($entities))

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


                if (!isset($activeTab)) {
                    $activeTab = 'saml';
                }

            @endphp

            {{--            <x-config-sections-bydirection :inSection="$inSection" :outSection="$outSection" :rulesSection="$rulesSection"/>--}}
            <x-config-sections-byprotocol :activeTab="$activeTab" :samlSection="$samlSection"
                                          :oidcSection="$oidcSection"
                                          :rulesSection="$rulesSection"/>

        @endif

    </div>
@endsection

@section('styles')
    <!-- Additional styles specific to this page -->
@endsection

@section('scripts')
    <!-- Additional scripts specific to this page -->
@endsection
