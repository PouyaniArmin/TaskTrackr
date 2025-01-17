<div class="container d-flex align-items-center justify-content-center">
    <h2>SignIn</h2>
</div>
<div class="row">
    <div class="col-6">
        <div class="container w-75 shadow p-3">
            <form method="post">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" id="inputEmail" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <input type="password" name="password" placeholder="Password" class="form-control" id="inputPassword">
                </div>
                <div class="mb-3 form-check">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <div class="col-6 w-25 p-4">
        <a href="<?php echo $google; ?>" class="d-flex align-items-center text-decoration-none shadow-lg">
            <i class="bi bi-google me-2 text-danger fs-4"></i> Login With Gmail
        </a>
    </div>
</div>
