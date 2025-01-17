<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- google -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <!-- As a link -->
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Task Tracker</a>
        </div>
    </nav>

    {{content}}


    <div id="alert-container" style="position: fixed; top: 10%; left: 50%; transform: translate(-50%, -10%); display: none;">
        <?php if (\App\Core\SessionManager::exists('errors')) : ?>
            <?php foreach (\App\Core\SessionManager::get('errors') as $error) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?= $error; ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endforeach; ?>
            <?php \App\Core\SessionManager::remove('errors'); ?>
        <?php endif; ?>
    </div>

    <script>
        function showAlert() {
            var alertContainer = document.getElementById('alert-container');
            alertContainer.style.display = 'block';
            setTimeout(function() {
                alertContainer.style.display = 'none';
            }, 5000);
        }

        showAlert();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>