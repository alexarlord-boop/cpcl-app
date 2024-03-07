<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proxy configuration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <a class="h3" href="/">Proxy configuration</a>

    <div class="container row mt-2 d-flex justify-content-between align-items-center">

        <!-- ... Upload config from pc ... -->
        <form action="/" method="post" enctype="multipart/form-data">
            @csrf
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <button type="submit" class="input-group-append btn btn-outline-success">⬆upload</button>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="file" name="file" accept=".yaml" required>
                    <label class="custom-file-label"
                           for="file"><?= $_SESSION['new_file'] ?? "Choose file" ?></label>
                </div>
            </div>
        </form>

        <!-- ... Get config from uploads ... -->
        <form action="/" method="post" enctype="multipart/form-data">
            @csrf
            <div class="input-group input-group-sm mb-3">
                <select class="custom-select " name="uploadedFile" required>
                    <?php

                    // List files in the uploads/ directory
                    $uploadDir = 'uploads/';
                    $files = scandir($uploadDir);

                    foreach ($files as $file) {
                        if ($file != "." && $file != "..") {

                            if ($_SESSION['uploaded_file'] === $file) {
                                echo "<option selected value=\"$uploadDir$file\">$file</option>";
                            } else {
                                echo "<option value=\"$uploadDir$file\">$file</option>";
                            }
                        }
                    }
                    ?>
                </select>
                <div class="input-group-append">
                    <button type="submit" class="input-group-append btn btn-outline-primary">Get from uploads/
                    </button>
                </div>
            </div>
        </form>

    </div>

    <!-- ... Config file content divided in sections ... -->
    @if(isset($entities))
        @if(isset($fileName))
            <div>
                <h5>File: <span class="text-secondary">{{$fileName}}</span></h5>
            </div>
        @endif

        @php
            $inSection = array_filter($entities, function ($dto) {
                return $dto->getSection() === 'in';
            });
            $outSection = array_filter($entities, function ($dto) {
                return $dto->getSection() === 'out';
            });
            $rulesSection = array_filter($entities, function ($dto) {
                return $dto->getSection() === 'rules';
            });
            // parse each section into special DTOs

        @endphp

        <x-config-sections :inSection="$inSection" :outSection="$outSection" :rulesSection="$rulesSection"/>


    @endif

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $(document).ready(function () {
        $('#file').on('change', function () {
            var fileName = $(this).val().split('\\').pop();
            $('.custom-file-label').html(fileName);
        });
    });
</script>
</body>
</html>
