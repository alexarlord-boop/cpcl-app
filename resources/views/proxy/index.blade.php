<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proxy configuration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container">
    <p class="h3 mt-2 mb-5">Proxy configuration</p>

    <div class="container row d-flex justify-content-between align-items-center">

        <!-- ... Upload config from pc ... -->
        <form action="{{ route('proxy.parseAndShow') }}" method="post" enctype="multipart/form-data">
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
        <form action="{{ route('proxy.parseAndShow') }}" method="post">
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

        <button type="submit" form="deleteForm" name="delete_entities" class="btn btn-sm btn-outline-danger">Clear</button>
        <!-- Hidden form to submit when the button is clicked -->
        <form id="deleteForm" method="post"></form>

    </div>

    <!-- ... Your existing HTML content ... -->
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
