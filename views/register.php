<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Create Account</h4>
                <form method="post" action="index.php?page=register">
                    <input type="hidden" name="csrf" value="<?= csrfToken(); ?>">
                    <input type="hidden" name="action" value="register">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-success w-100">Register</button>
                    <p class="mt-3 small">Already have an account? <a href="index.php?page=login">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

