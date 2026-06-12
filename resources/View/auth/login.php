<?php 
// resources/View/auth/login.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PhaseFlow CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .auth-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .form-control {
            background: #0f172a;
            border: 1px solid #475569;
            color: #f1e7ff;
        }
        .btn-teal {
            background: #0d9488;
            border: none;
        }
        .btn-teal:hover {
            background: #0f766e;
        }
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


            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <h2 class="text-white fw-bold">PhaseFlow</h2>
                    <p class="text-muted">CRM • Delivery • Growth</p>
                </div>
                
                <div class="auth-card p-4">
                    <h4 class="text-white text-center mb-4">Welcome Back</h4>
                    
                    <form id="loginForm">
                        <div class="mb-3">
                            <label class="form-label text-light">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-light">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
                        </div>
                        
                        <button type="submit" class="btn btn-teal text-white w-100 py-2 mb-3">Login</button>
                    </form>
                    
                    <div class="text-center mb-3">
                        <span class="text-muted">or</span>
                    </div>
                    
                    <!-- Google Login Button -->
                    <a href="#" class="btn btn-light w-100 d-flex align-items-center justify-content-center gap-2 mb-3">
                        <i class="bi bi-google"></i>
                        <span>Continue with Google</span>
                    </a>
                    
                    <div class="text-center">
                        <small class="text-muted">Don't have an account? 
                            <a href="/PhaseFlow/public/register" class="text-teal">Create one</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);

            fetch('/PhaseFlow/public/login', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async response => {
                const text = await response.text();
                try { return JSON.parse(text); } catch(e) { throw new Error(text); }
            })
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#0d9488'
                    }).then(() => {
                        window.location.href = data.redirect || '/PhaseFlow/public/app';
                    });
                } else if (data.status === 'verify') {
                    Swal.fire({
                        title: 'Verification Required',
                        text: data.message || 'Please verify your email first.',
                        icon: 'info',
                        confirmButtonColor: '#0d9488'
                    }).then(() => {
                        window.location.href = data.redirect || '/PhaseFlow/public/verify-email';
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Login failed',
                        icon: 'error',
                        confirmButtonColor: '#0d9488'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#0d9488'
                });
            });
        });
    </script>
</body>
</html>