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

    public function showRegisterForm()
    {
        require __DIR__ . '/../../resources/View/auth/register.php';
    }

    public function register()
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || 
                  strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false;

        $name            = trim($_POST['name'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            return $this->jsonResponse('error', 'All fields are required.', $isAjax);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->jsonResponse('error', 'Please enter a valid email address.', $isAjax);
        }

        if (strlen($password) < 6) {
            return $this->jsonResponse('error', 'Password must be at least 6 characters.', $isAjax);
        }

        if ($password !== $confirmPassword) {
            return $this->jsonResponse('error', 'Passwords do not match.', $isAjax);
        }

        if ($this->userModel->findByEmail($email)) {
            return $this->jsonResponse('error', 'This email is already registered.', $isAjax);
        }

        try {
            // Create Tenant
            $tenantModel = new \App\Models\Tenant();
            $tenantSlug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name)) . '-' . time();

            $tenantId = $tenantModel->create([
                'name'  => $name . "'s Company",
                'slug'  => $tenantSlug,
                'email' => $email
            ]);

            if (!$tenantId) throw new \Exception("Failed to create tenant.");

            $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Create User
            $userData = [
                'tenant_id'         => $tenantId,
                'name'              => $name,
                'email'             => $email,
                'password'          => password_hash($password, PASSWORD_DEFAULT),
                'role'              => 'owner',
                'verification_code' => $verificationCode
            ];

            $userId = $this->userModel->create($userData);
            if (!$userId) throw new \Exception("Failed to create user.");

            // Create Subscription & Usage
            $subscriptionModel = new \App\Models\Subscription();
            $subscriptionModel->createDefault($tenantId);

            $usageModel = new \App\Models\TenantUsage();
            $usageModel->initialize($tenantId);

            // Send Email
            $mailer = new Mailer();
            $subject = "Verify your PhaseFlow account";
            $message = "Your verification code is: <strong>{$verificationCode}</strong>";

            $emailSent = $mailer->send($email, $name, $subject, $message);

            if (!$emailSent) {
                throw new \Exception("Failed to send verification email.");
            }

            $_SESSION['pending_user_id'] = $userId;

            return $this->jsonResponse('success', 
                'Registration successful! Please check your email for the verification code.', 
                $isAjax, 
                '/verify-email'
            );

        } catch (\Exception $e) {
            return $this->jsonResponse('error', $e->getMessage(), $isAjax);
        }
    }

    // Helper method for consistent JSON / Redirect response
    private function jsonResponse($status, $message, $isAjax = false, $redirect = null)
    {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'status'   => $status,
                'message'  => $message,
                'redirect' => $redirect
            ]);
            exit;
        }

        $_SESSION[$status === 'success' ? 'success' : 'error'] = $message;
        header('Location: ' . ($redirect ?? '/register'));
        exit;
    }

    // ==================== VERIFY EMAIL ====================

    public function verifyEmail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
            $code = trim($_POST['code']);
            $userId = $_SESSION['pending_user_id'] ?? 0;

            $user = $this->userModel->verifyCode($userId, $code);

            if ($user) {
                unset($_SESSION['pending_user_id']);
                $_SESSION['success'] = "Email verified successfully!";
                header('Location: /login');
                exit;
            } else {
                $_SESSION['error'] = "Invalid or expired code.";
            }
        }

        require __DIR__ . '/../../resources/View/auth/verify-email.php';
    }

    // ==================== LOGIN ====================

    public function showLoginForm()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /app');
            exit;
        }
        require __DIR__ . '/../../resources/View/auth/login.php';
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Invalid email or password.";
            header('Location: /login');
            exit;
        }

        if (empty($user['email_verified_at'])) {
            $_SESSION['error'] = "Please verify your email first.";
            header('Location: /login');
            exit;
        }

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['tenant_id']  = $user['tenant_id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['role']       = $user['role'];

        $this->userModel->updateLastLogin($user['id']);

        header('Location: /app');
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}