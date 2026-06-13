<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\EmailService;

class AuthController extends Controller
{
    protected $userModel;
    protected $emailService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->emailService = new EmailService();
    }

    public function showRegisterForm()
    {
        if (!empty($_SESSION['user_id'])) {
            header('Location: ' . APP_BASE . '/app');
            exit;
        }
        require __DIR__ . '/../../resources/View/auth/register.php';
    }

    public function register()
    {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        $isAjax = $this->isAjaxRequest();

        if (empty($name) || empty($email) || empty($password) || $password !== $confirm) {
            return $this->jsonResponse('error', 'All fields are required and passwords must match.', $isAjax);
        }

        if ($this->userModel->findByEmail($email)) {
            return $this->jsonResponse('error', 'Email already exists.', $isAjax);
        }

        try {
            // Create Tenant
            $tenantModel = new \App\Models\Tenant();
            $tenantSlug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name)) . '-' . time();

            $tenant = $tenantModel->create([
                'name'  => $name . "'s Company",
                'slug'  => $tenantSlug,
                'email' => $email
            ]);

            $tenantId = \App\Models\Tenant::getId($tenant);

            if (!$tenantId) {
                return $this->jsonResponse('error', 'Failed to create tenant.', $isAjax);
            }

            // Initialize Tenant Usage & Subscription
            \App\Models\TenantUsage::initialize($tenantId);
            \App\Models\Subscription::createDefault($tenantId);

            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Create User
            $user = $this->userModel->create([
                'tenant_id'         => $tenantId,
                'name'              => $name,
                'email'             => $email,
                'password'          => password_hash($password, PASSWORD_DEFAULT),
                'role'              => 'owner',
                'verification_code' => $code
            ]);

            if (!$user) {
                return $this->jsonResponse('error', 'Failed to create user account.', $isAjax);
            }

            $userId = $user->id ?? null;

            $sent = $this->emailService->sendVerificationCode($email, $name, $code);

            $_SESSION['pending_user_id'] = $userId;
            $_SESSION['pending_user_email'] = $email;

            if ($sent) {
                return $this->jsonResponse('success', 'Registration successful! Please check your email for verification code.', $isAjax, APP_BASE . '/verify-email');
            } else {
                return $this->jsonResponse('success', 'Registration successful. Code: ' . $code, $isAjax, APP_BASE . '/verify-email');
            }

        } catch (\Exception $e) {
            error_log('Registration error: ' . $e->getMessage());
            return $this->jsonResponse('error', 'An unexpected error occurred.', $isAjax);
        }
    }

    public function showVerifyEmail()
    {
        if (!empty($_SESSION['user_id']) && !empty($_SESSION['email_verified'])) {
            header('Location: ' . APP_BASE . '/app');
            exit;
        }

        if (empty($_GET['email']) && !empty($_SESSION['pending_user_email'])) {
            $_GET['email'] = $_SESSION['pending_user_email'];
        }
        require __DIR__ . '/../../resources/View/auth/verify-email.php';
    }

    public function verifyCode()
    {
        $isAjax = $this->isAjaxRequest();

        $email = trim($_POST['email'] ?? $_SESSION['pending_user_email'] ?? '');
        $code  = trim($_POST['code'] ?? '');

        if (is_array($code)) {
            $code = implode('', array_map('trim', $code));
        }

        $userId = $_SESSION['pending_user_id'] ?? null;

        if (empty($code) || strlen($code) !== 6) {
            return $this->jsonResponse('error', 'Please enter the complete 6-digit code.', $isAjax);
        }

        if (!$userId && $email) {
            $tempUser = $this->userModel->findByEmail($email);
            if ($tempUser) $userId = $tempUser->id ?? null;
        }

        if (!$userId) {
            return $this->jsonResponse('error', 'Verification session expired.', $isAjax);
        }

        $verifiedUser = $this->userModel->verifyCode($userId, $code);

        if ($verifiedUser) {
            unset($_SESSION['pending_user_id'], $_SESSION['pending_user_email']);

            $_SESSION['user_id']   = $verifiedUser->id;
            $_SESSION['tenant_id'] = $verifiedUser->tenant_id;
            $_SESSION['user_name'] = $verifiedUser->name;
            $_SESSION['email_verified'] = true;

            $this->userModel->updateLastLogin($verifiedUser->id);

            return $this->jsonResponse('success', 'Email verified successfully!', $isAjax, APP_BASE . '/app');
        }

        return $this->jsonResponse('error', 'Invalid or expired verification code.', $isAjax);
    }

    public function resendVerification()
    {
        $isAjax = $this->isAjaxRequest();
        $userId = $_SESSION['pending_user_id'] ?? null;
        $email  = $_SESSION['pending_user_email'] ?? trim($_POST['email'] ?? '');

        if (!$userId && $email) {
            $u = $this->userModel->findByEmail($email);
            if ($u) $userId = $u->id ?? null;
        }

        if (!$userId) {
            return $this->jsonResponse('error', 'No pending verification found.', $isAjax);
        }

        $user = $this->userModel->findById($userId);

        if (!$user || !empty($user->email_verified_at)) {
            return $this->jsonResponse('error', 'Account already verified or not found.', $isAjax);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $updated = $this->userModel->updateVerificationCode($userId, $code);

        if ($updated) {
            $name = $user->name ?? 'User';
            $sent = $this->emailService->sendVerificationCode($user->email ?? $email, $name, $code);

            if ($sent) {
                return $this->jsonResponse('success', 'New verification code sent!', $isAjax);
            }
            return $this->jsonResponse('error', 'Failed to send email. Code: ' . $code, $isAjax);
        }

        return $this->jsonResponse('error', 'Could not resend code.', $isAjax);
    }

    public function showLoginForm()
    {
        if (!empty($_SESSION['user_id']) && !empty($_SESSION['email_verified'])) {
            header('Location: ' . APP_BASE . '/app');
            exit;
        }
        if (!empty($_SESSION['pending_user_id'])) {
            header('Location: ' . APP_BASE . '/verify-email');
            exit;
        }
        require __DIR__ . '/../../resources/View/auth/login.php';
    }

    public function login()
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $isAjax   = $this->isAjaxRequest();

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {

            if (empty($user->email_verified_at)) {
                $_SESSION['pending_user_id']   = $user->id;
                $_SESSION['pending_user_email'] = $user->email;

                $msg = 'Please verify your email first.';
                if ($isAjax) {
                    return $this->jsonResponse('verify', $msg, true, APP_BASE . '/verify-email');
                }
                $_SESSION['error'] = $msg;
                header('Location: ' . APP_BASE . '/verify-email');
                exit;
            }

            // Successful login
            $_SESSION['user_id']   = $user->id;
            $_SESSION['tenant_id'] = $user->tenant_id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['email_verified'] = true;

            $this->userModel->updateLastLogin($user->id);

            if ($isAjax) {
                return $this->jsonResponse('success', 'Login successful!', true, APP_BASE . '/app');
            }

            header('Location: ' . APP_BASE . '/app');
            exit;
        }

        $msg = 'Invalid email or password.';
        if ($isAjax) {
            return $this->jsonResponse('error', $msg, true);
        }

        $_SESSION['error'] = $msg;
        header('Location: ' . APP_BASE . '/login');
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . APP_BASE . '/login');
        exit;
    }

    // Helper methods remain the same
  private function isAjaxRequest(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $requestedWith = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');

        if (strpos($accept, 'application/json') !== false) {
            return true;
        }
        if ($requestedWith === 'xmlhttprequest') {
            return true;
        }
        // Some proxies / older setups only send partial Accept
        if (strpos($accept, 'json') !== false) {
            return true;
        }
        return false;
    }

    private function jsonResponse($status, $message, $isAjax = false, $redirect = null)
    {
        $wantsJson = $isAjax
            || strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false
            || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest'
            || ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';   // These auth endpoints are only called via fetch/JS

        if ($wantsJson) {
            // Clean any stray output (whitespace, warnings, previous echoes) so JSON is pure
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Type: application/json');
            echo json_encode([
                'status'  => $status,
                'message' => $message,
                'redirect'=> $redirect
            ]);
            exit;
        }

        // Legacy non-AJAX fallback (pure HTML forms)
        $_SESSION[$status] = $message;
        $target = $redirect ?? APP_BASE . '/register';
        header('Location: ' . $target);
        exit;
    }

}