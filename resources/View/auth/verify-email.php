<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - PhaseFlow CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); min-height: 100vh; display: flex; align-items: center; }
        .verify-card { background: #1e293b; border: 1px solid #334155; border-radius: 20px; }
        .code-input { width: 50px; height: 60px; text-align: center; font-size: 24px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="verify-card p-5 text-center">
                    <h3 class="text-white mb-2">Verify Your Email</h3>
                    <p class="text-muted mb-4">Enter the 6-digit code sent to <strong><?= htmlspecialchars($_GET['email'] ?? '') ?></strong></p>

                    <form id="verifyForm">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                        
                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <?php for ($i = 1; $i <= 6; $i++): ?>
                                <input type="text" name="code[]" maxlength="1" class="form-control code-input text-center" required>
                            <?php endfor; ?>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 py-2">Verify Code</button>
                    </form>

                    <div class="mt-3">
                        <small class="text-danger">Didn't receive the code? <a href="#" class="text-warning">Resend</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto focus next input
        const inputs = document.querySelectorAll('.code-input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        // Form submit
        document.getElementById('verifyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const code = Array.from(document.querySelectorAll('.code-input')).map(i => i.value).join('');

            fetch('/PhaseFlow/public/verify-code', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email=${encodeURIComponent(formData.get('email'))}&code=${code}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Success!', data.message, 'success').then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            });
        });
    </script>
</body>
</html>