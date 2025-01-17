<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- google -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <!-- datapicker -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- As a link -->
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <a href="/logout" class="btn btn-outline-danger btn-sm rounded-pill">Logout</a>
        </div>
    </nav>
    <div class="overflow-x-hidden">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-2 bg-light border-end sidebar h-100">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="/dashboard/new-task" class="text-decoration-none">
                            <i class="fas fa-plus-circle"></i> New Task
                        </a>
                    </li>

                </ul>
            </div>
            <!-- Content -->
            <div class="col-10 p-4">
                {{content}}
            </div>
        </div>
    </div>

    <div id="alert-container" style="position: fixed; top: 10%; left: 50%; transform: translate(-50%, -10%); display: none;">
        <?php

        use App\Core\SessionManager;

        if (SessionManager::exists('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?= SessionManager::get('success'); ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php SessionManager::remove('success');
            ?>
        <?php endif; ?>
    </div>

    <script>
        function showAlert() {
            var alertContainer = document.getElementById('alert-container');
            alertContainer.style.display = 'block';
            setTimeout(function() {
                alertContainer.style.display = 'none';
            }, 2000); // 2 seconds
        }

        showAlert();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>