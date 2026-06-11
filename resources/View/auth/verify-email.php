<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - PhaseFlow CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .verify-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 20px;
            max-width: 420px;
            width: 100%;
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
            <div class="col-md-5">
                <div class="verify-card p-5 text-center">
                    <div class="mb-4">
                        <?php if ($type === 'success'): ?>
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <?php else: ?>
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="text-white mb-3"><?= $title ?></h3>
                    
                    <p class="text-muted mb-4"><?= $message ?></p>
                    
                    <a href="<?= $buttonLink ?>" class="btn btn-teal px-4 py-2">
                        <?= $buttonText ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>