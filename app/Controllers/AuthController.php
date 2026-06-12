<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Helpers\Mailer;

class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // ==================== REGISTRATION ====================

        // Show Register Form
    public function showRegisterForm()
    {
        require __DIR__ . '/../../resources/View/auth/register.php';
    }



 public function register()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['error'] = "All fields are required";
            header('Location: /register');
            exit;
        }

        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = "Email already exists";
            header('Location: /register');
            exit;
        }

        $userData = [
            'tenant_id' => 1, // Default for now (multi-tenant later)
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'owner',
            'verification_code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT)
        ];

        $userId = $this->userModel->create($userData);

        if ($userId) {
            // Send verification email
            $mailer = new Mailer();
            $subject = "Verify your PhaseFlow account";
            $message = "Your verification code is: <strong>{$userData['verification_code']}</strong>";
            
            if ($mailer->send($email, $name, $subject, $message)) {
                $_SESSION['pending_user_id'] = $userId;
                $_SESSION['success'] = "Registration successful! Check your email for verification code.";
                header('Location: /verify-email');
                exit;
            }
        }

        $_SESSION['error'] = "Registration failed";
        header('Location: /register');
        exit;
    }



 public function store()
{
    header('Content-Type: application/json');

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    try {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
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

        $db = \Database::getInstance()->getConnection();
        $db->beginTransaction();

        // 1. Create Tenant
        $tenantModel = new \App\Models\Tenant();
        $tenantSlug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name)) . '-' . time();

        $tenantId = $tenantModel->create([
            'name'  => $name . "'s Company",
            'slug'  => $tenantSlug,
            'email' => $email
        ]);

        if (!$tenantId) throw new \Exception("Failed to create tenant");

        // 2. Create User as Owner
        $userId = $this->userModel->create([
            'tenant_id' => $tenantId,
            'name'      => $name,
            'email'     => $email,
            'password'  => $password,
            'role'      => 'owner'
        ]);

        if (!$userId) throw new \Exception("Failed to create user");

        // 3. Subscription + Usage
        $subscriptionModel = new \App\Models\Subscription();
        if (!$subscriptionModel->createDefault($tenantId)) {
            throw new \Exception("Failed to create subscription");
        }

        $usageModel = new \App\Models\TenantUsage();
        if (!$usageModel->initialize($tenantId)) {
            throw new \Exception("Failed to initialize usage");
        }

        // 4. Generate Code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->userModel->saveVerificationCode($userId, $code);

  

        // 5. Send Email using your existing Mailer
        $emailService = new \App\Models\EmailService();
        $emailSent = $emailService->sendVerificationCode($email, $name, $code);

        if (!$emailSent) {
        throw new \Exception('Failed to send verification email.');
        }

      // Commit Database Changes
        $db->commit();

        
        echo json_encode([
            'status'   => 'success',
            'message'  => 'Registration successful! Please check your email for the 6-digit code.',
            'redirect' => '/PhaseFlow/public/verify-code?email=' . urlencode($email)
        ]);

    } catch (\Exception $e) {
        if (isset($db) && $db->inTransaction()) {
            $db->rollBack();
        }

        echo json_encode([
            'status'  => 'error',
            'message' => $e->getMessage()
        ]);
    }
}


// Show the 6-digit code verification page
public function showVerifyCodePage()
{
    require __DIR__ . '/../../resources/View/auth/verify-email.php';

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
        if (isset($_POST['code'])) {
            $code = trim($_POST['code']);
            $userId = $_SESSION['pending_user_id'] ?? 0;

            $user = $this->userModel->verifyCode($userId, $code);

            if ($user) {
                unset($_SESSION['pending_user_id']);
                $_SESSION['success'] = "Email verified successfully!";
                header('Location: /login');
                exit;
            } else {
                $_SESSION['error'] = "Invalid or expired code";
            }
        }

        require __DIR__ . '/../../resources/View/auth/verify-email.php';
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

 // Show Login Form
public function showLoginForm()
{
    if (isset($_SESSION['user_id'])) {
        header('Location: /home');
        exit;
    }
    
    // Just load the view - NO require bootstrap here
    require __DIR__ . '/../../resources/View/auth/login.php';
}

    // Handle Login
    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if (!$user['email_verified_at']) {
                $_SESSION['error'] = "Please verify your email first.";
                header('Location: /login');
                exit;
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['tenant_id'] = $user['tenant_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Update last login
            $this->userModel->updateLastLogin($user['id']);

            header('Location: /home');
            exit;
        }

        $_SESSION['error'] = "Invalid credentials";
        header('Location: /login');
        exit;
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
        'redirect' => '/PhaseFlow/public/app'
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

    // Resend Verification
    public function resendVerification()
    {
        // Implementation similar to register
        $_SESSION['success'] = "New code sent (TODO: implement)";
        header('Location: /verify-email');
        exit;
    }



   // Logout
    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}