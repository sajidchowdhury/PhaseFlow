<?php 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PhaseFlow CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { background: #1e293b; border: 1px solid #334155; border-radius: 20px; }
        .form-control { background: #0f172a; border: 1px solid #475569; color: #f1e7ff; }
        .btn-teal { background: #0d9488; border: none; }
        .loading { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>


            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2 class="text-white fw-bold">PhaseFlow</h2>
                </div>
                <div class="auth-card p-4">
                    <h4 class="text-white text-center mb-4">Create Account</h4>
                    
                    <form id="registerForm">
                        <div class="mb-3">
                            <label class="form-label text-light">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-light">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-teal text-white w-100" id="submitBtn">
                            Create Account
                        </button>
                    </form>

                    <div class="text-center my-3"><span class="text-muted">or</span></div>
                    
                    <a href="#" class="btn btn-light w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-google"></i> Sign up with Google
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable form and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Processing...
            `;

            const formData = new FormData(this);

            fetch('/PhaseFlow/public/register', {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    throw new Error(text);
                }
                return data;
            })
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#0d9488'
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Unknown error',
                        icon: 'error',
                        confirmButtonColor: '#0d9488'
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    title: 'Server Error!',
                    text: error.message || 'Something went wrong.',
                    icon: 'error'
                });
            })
            .finally(() => {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Create Account';
            });
        });
    </script>
</body>
</html>