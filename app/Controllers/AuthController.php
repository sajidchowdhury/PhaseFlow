<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // ==================== REGISTRATION ====================

    public function register()
    {
        require __DIR__ . '/../../resources/View/auth/register.php';
    }

  public function store()
{
    header('Content-Type: application/json');

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // === Validation ===
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
        return;
    }

    if (strlen($password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters long.']);
        return;
    }

    if ($password !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        return;
    }

    if ($this->userModel->emailExists($email)) {
        echo json_encode(['status' => 'error', 'message' => 'This email is already registered.']);
        return;
    }

    try {
        // === 1. Create Tenant ===
        $tenantModel = new \App\Models\Tenant();
        $tenantSlug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name)) . '-' . time();

        $tenantId = $tenantModel->create([
            'name'  => $name . "'s Company",
            'slug'  => $tenantSlug,
            'email' => $email
        ]);

        if (!$tenantId) {
            throw new \Exception("Failed to create tenant");
        }

        // === 2. Create User as Owner ===
        $userId = $this->userModel->create([
            'tenant_id' => $tenantId,
            'name'      => $name,
            'email'     => $email,
            'password'  => $password,
            'role'      => 'owner'
        ]);

        if (!$userId) {
            throw new \Exception("Failed to create user");
        }

        // === 3. Create Subscription + Usage ===
        $subscriptionModel = new \App\Models\Subscription();
        $subscriptionModel->createDefault($tenantId);

        $usageModel = new \App\Models\TenantUsage();
        $usageModel->initialize($tenantId);

        // === 4. Generate 6-Digit Code ===
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->userModel->saveVerificationCode($userId, $code);

        // === 5. Send Verification Email ===
        $this->sendVerificationEmail($email, $name, $code);

        echo json_encode([
            'status'   => 'success',
            'message'  => 'Registration successful! Please check your email for the 6-digit code.',
            'redirect' => BASE_URL . '/verify-code?email=' . urlencode($email)
        ]);

    } catch (\Exception $e) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}



// Show the 6-digit code verification page
public function showVerifyCodePage()
{
    require __DIR__ . '/../../resources/View/auth/verify-code.php';
}

// Verify the 6-digit code
public function verifyCode()
{
    header('Content-Type: application/json');

    $email = trim($_POST['email'] ?? '');
    $code  = trim($_POST['code'] ?? '');

    if (empty($email) || empty($code)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and code are required.']);
        return;
    }

    // Find user by email
    $user = $this->userModel->findByEmail($email);

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        return;
    }

    // Check if code matches (using verification_token column for now)
    if ($user['verification_token'] != $code) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid verification code.']);
        return;
    }

    // Mark email as verified
    $this->userModel->verifyEmailByCode($user['id']);

    echo json_encode([
        'status'   => 'success',
        'message'  => 'Email verified successfully! You can now login.',
        'redirect' => '/PhaseFlow/public/login'
    ]);
}

    // ==================== EMAIL VERIFICATION ====================
public function verifyEmail()
{
    $token = $_GET['token'] ?? '';

    if (empty($token)) {
        $this->renderVerificationPage('Invalid Link', 'This verification link is invalid or missing.', 'error');
        return;
    }

    if ($this->userModel->verifyEmail($token)) {
        $this->renderVerificationPage(
            'Email Verified!', 
            'Your account has been successfully activated.', 
            'success'
        );
    } else {
        $this->renderVerificationPage(
            'Verification Failed', 
            'This verification link is invalid or has expired.', 
            'error'
        );
    }
}

private function renderVerificationPage($title, $message, $type)
{
    $buttonText = $type === 'success' ? 'Login to Your Account' : 'Back to Registration';
    $buttonLink = $type === 'success' ? '/PhaseFlow/public/login' : '/PhaseFlow/public/register';

    // Pass variables to the view
    $data = [
        'title'       => $title,
        'message'     => $message,
        'type'        => $type,
        'buttonText'  => $buttonText,
        'buttonLink'  => $buttonLink
    ];

    extract($data);
    require __DIR__ . '/../../resources/View/auth/verify-email.php';
}

    // ==================== LOGIN ====================

    public function login()
    {
        require __DIR__ . '/../../resources/View/auth/login.php';
    }

   public function authenticate()
{
    header('Content-Type: application/json');

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and Password are required.']);
        return;
    }

    // Find user
    $user = $this->userModel->findByEmail($email);

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
        return;
    }

    // Check if email is verified
    if (empty($user['email_verified_at'])) {
        echo json_encode(['status' => 'error', 'message' => 'Please verify your email first.']);
        return;
    }

    // Check if user account is active
    if (!$user['is_active']) {
        echo json_encode(['status' => 'error', 'message' => 'Your account has been deactivated.']);
        return;
    }

    // Start Session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // === Store Important Data in Session ===
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['name'];
    $_SESSION['user_role']  = $user['role'];
    $_SESSION['tenant_id']  = $user['tenant_id'];           // ← Important for multi-tenancy

    // Update last login time
    $this->userModel->updateLastLogin($user['id']);

    // === Success Response ===
    echo json_encode([
        'status'   => 'success',
        'message'  => 'Login successful!',
        'redirect' => '/PhaseFlow/public/dashboard'
    ]);
}


/**
 * Send professional verification email with 6-digit code
 */
private function sendVerificationEmail($email, $name, $code)
{
    $subject = "Verify Your Email - PhaseFlow CRM";

    $htmlBody = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: "Segoe UI", Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
            .header { background: linear-gradient(135deg, #0f172a, #1e293b); padding: 30px; text-align: center; }
            .header h1 { color: #ffffff; margin: 0; font-size: 28px; }
            .content { padding: 40px 30px; color: #334155; }
            .code-box { 
                background: #0f172a; 
                color: #ffffff; 
                font-size: 42px; 
                font-weight: bold; 
                letter-spacing: 12px; 
                padding: 20px; 
                text-align: center; 
                border-radius: 12px; 
                margin: 30px 0;
                font-family: "Courier New", monospace;
            }
            .btn { 
                display: inline-block; 
                background: #0d9488; 
                color: white; 
                padding: 14px 30px; 
                text-decoration: none; 
                border-radius: 8px; 
                font-weight: 600;
            }
            .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 13px; color: #64748b; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>PhaseFlow CRM</h1>
            </div>
            <div class="content">
                <h2 style="color: #0f172a;">Hello ' . htmlspecialchars($name) . ',</h2>
                <p style="font-size: 16px; line-height: 1.6;">
                    Thank you for registering with <strong>PhaseFlow CRM</strong>.<br>
                    Please use the following 6-digit code to verify your email:
                </p>

                <div class="code-box">' . $code . '</div>

                <p style="font-size: 15px; color: #475569;">
                    This code will expire in <strong>15 minutes</strong>.
                </p>

                <p>
                    <a href="http://localhost/PhaseFlow/public/verify-code?email=' . urlencode($email) . '" class="btn">
                        Verify Email Now
                    </a>
                </p>
            </div>
            <div class="footer">
                &copy; ' . date('Y') . ' PhaseFlow CRM. All rights reserved.
            </div>
        </div>
    </body>
    </html>';

    // Send using PHPMailer
    return \App\Helpers\Mailer::send($email, $name, $subject, $htmlBody);
}


    // ==================== LOGOUT ====================

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header("Location: /PhaseFlow/public/login");
        exit;
    }
}