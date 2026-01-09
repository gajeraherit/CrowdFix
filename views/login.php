<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Login</h4>
                <form method="post" action="index.php?page=login">
                    <input type="hidden" name="csrf" value="<?= csrfToken(); ?>">
                    <input type="hidden" name="action" value="login">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Login</button>
                    <p class="mt-3 small">No account? <a href="index.php?page=register">Register</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

