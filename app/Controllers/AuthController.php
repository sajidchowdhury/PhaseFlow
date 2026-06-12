<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\EmailService;
use App\Helpers\Mailer;

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
            header('Location: /PhaseFlow/public/app');
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

            $tenantId = $tenantModel->create([
                'name'  => $name . "'s Company",
                'slug'  => $tenantSlug,
                'email' => $email
            ]);

            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $userId = $this->userModel->create([
                'tenant_id'         => $tenantId,
                'name'              => $name,
                'email'             => $email,
                'password'          => password_hash($password, PASSWORD_DEFAULT),
                'role'              => 'owner',
                'verification_code' => $code
            ]);

            if (!$userId) {
                return $this->jsonResponse('error', 'Failed to create user account.', $isAjax);
            }

            // Send nice verification email via EmailService
            $sent = $this->emailService->sendVerificationCode($email, $name, $code);

            // Always store pending info for verification step
            $_SESSION['pending_user_id'] = $userId;
            $_SESSION['pending_user_email'] = $email;

            if ($sent) {
                return $this->jsonResponse('success', 'Registration successful! Please check your email for the 6-digit verification code.', $isAjax, '/PhaseFlow/public/verify-email');
            } else {
                // Still allow verification flow even if mail failed (dev convenience)
                return $this->jsonResponse('success', 'Registration successful, but email sending failed. Use the code shown in logs or contact support. Code: ' . $code, $isAjax, '/PhaseFlow/public/verify-email');
            }

        } catch (\Exception $e) {
            error_log('Registration error: ' . $e->getMessage());
            return $this->jsonResponse('error', 'An unexpected error occurred. Please try again.', $isAjax);
        }
    }

    /**
     * Show the email verification page
     */
    public function showVerifyEmail()
    {
        // If already fully logged in + verified, no need to show verify page
        if (!empty($_SESSION['user_id']) && !empty($_SESSION['email_verified'])) {
            header('Location: /PhaseFlow/public/app');
            exit;
        }

        // Support both session (from register) and query string fallback
        if (empty($_GET['email']) && !empty($_SESSION['pending_user_email'])) {
            // Inject into GET for the view to pick up without changing view too much
            $_GET['email'] = $_SESSION['pending_user_email'];
        }
        require __DIR__ . '/../../resources/View/auth/verify-email.php';
    }

    /**
     * Handle verification code submission (POST /verify-code)
     */
    public function verifyCode()
    {
        $isAjax = $this->isAjaxRequest();

        $email = trim($_POST['email'] ?? $_SESSION['pending_user_email'] ?? '');
        $code  = trim($_POST['code'] ?? '');

        // If code sent as array (from old split inputs) join it
        if (is_array($_POST['code'] ?? null)) {
            $code = implode('', array_map('trim', $_POST['code']));
        }

        $userId = $_SESSION['pending_user_id'] ?? null;

        if (empty($code) || strlen($code) !== 6) {
            return $this->jsonResponse('error', 'Please enter the complete 6-digit code.', $isAjax);
        }

        // Prefer userId from session (most secure after register)
        $user = null;
        if ($userId) {
            $user = $this->userModel->findById($userId);
        } elseif ($email) {
            $user = $this->userModel->findByEmail($email);
            if ($user) $userId = $user['id'];
        }

        if (!$user || !$userId) {
            return $this->jsonResponse('error', 'Verification session expired. Please register again.', $isAjax);
        }

        // Use model method (also checks not already verified)
        $verifiedUser = $this->userModel->verifyCode($userId, $code);

        if ($verifiedUser) {
            // Clear pending
            unset($_SESSION['pending_user_id']);
            unset($_SESSION['pending_user_email']);

            // Auto-login the user after successful verification (best UX)
            $_SESSION['user_id']   = $verifiedUser['id'];
            $_SESSION['tenant_id'] = $verifiedUser['tenant_id'];
            $_SESSION['user_name'] = $verifiedUser['name'];
            $_SESSION['email_verified'] = true;

            // Optional last login update
            if (method_exists($this->userModel, 'updateLastLogin')) {
                $this->userModel->updateLastLogin($verifiedUser['id']);
            }

            return $this->jsonResponse('success', 'Email verified successfully! Welcome to PhaseFlow.', $isAjax, '/PhaseFlow/public/app');
        }

        return $this->jsonResponse('error', 'Invalid or expired verification code. Please try again.', $isAjax);
    }

    /**
     * Resend the verification code
     */
    public function resendVerification()
    {
        $isAjax = $this->isAjaxRequest();
        $userId = $_SESSION['pending_user_id'] ?? null;
        $email  = $_SESSION['pending_user_email'] ?? trim($_POST['email'] ?? '');

        if (!$userId && $email) {
            $u = $this->userModel->findByEmail($email);
            if ($u) $userId = $u['id'];
        }

        if (!$userId) {
            return $this->jsonResponse('error', 'No pending verification found. Please register or log in again.', $isAjax);
        }

        $user = $this->userModel->findById($userId);
        if (!$user || !empty($user['email_verified_at'])) {
            return $this->jsonResponse('error', 'Account is already verified or not found.', $isAjax);
        }

        // Generate fresh code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $updated = $this->userModel->updateVerificationCode($userId, $code);

        if ($updated) {
            $name = $user['name'];
            $sent = $this->emailService->sendVerificationCode($user['email'], $name, $code);

            if ($sent) {
                return $this->jsonResponse('success', 'A new verification code has been sent to your email.', $isAjax);
            }
            return $this->jsonResponse('error', 'Failed to resend email. New code generated (dev): ' . $code, $isAjax);
        }

        return $this->jsonResponse('error', 'Could not resend code at this time.', $isAjax);
    }

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
        $target = $redirect ?? '/PhaseFlow/public/register';
        header('Location: ' . $target);
        exit;
    }

    public function showLoginForm()
    {
        if (!empty($_SESSION['user_id']) && !empty($_SESSION['email_verified'])) {
            header('Location: /PhaseFlow/public/app');
            exit;
        }
        // If partially logged (unverified), send to verify instead of login form
        if (!empty($_SESSION['pending_user_id']) && empty($_SESSION['email_verified'])) {
            header('Location: /PhaseFlow/public/verify-email');
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

        if ($user && password_verify($password, $user['password'])) {

            // Enforce email verification
            if (empty($user['email_verified_at'])) {
                // Set up pending verification state
                $_SESSION['pending_user_id']   = $user['id'];
                $_SESSION['pending_user_email'] = $user['email'];

                $msg = 'Please verify your email before logging in. We sent a new code if needed.';
                if ($isAjax) {
                    return $this->jsonResponse('verify', $msg, true, '/PhaseFlow/public/verify-email');
                }
                $_SESSION['error'] = $msg;
                header('Location: /PhaseFlow/public/verify-email');
                exit;
            }

            // Successful verified login
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['tenant_id'] = $user['tenant_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['email_verified'] = true;

            if (method_exists($this->userModel, 'updateLastLogin')) {
                $this->userModel->updateLastLogin($user['id']);
            }

            if ($isAjax) {
                return $this->jsonResponse('success', 'Login successful!', true, '/PhaseFlow/public/app');
            }

            header('Location: /PhaseFlow/public/app');
            exit;
        }

        $msg = 'Invalid email or password.';
        if ($isAjax) {
            return $this->jsonResponse('error', $msg, true);
        }

        $_SESSION['error'] = $msg;
        header('Location: /PhaseFlow/public/login');
        exit;
    }

    public function logout()
    {
        // Keep tenant info minimal but destroy auth
        $redirect = '/PhaseFlow/public/login';
        session_destroy();
        header('Location: ' . $redirect);
        exit;
    }
}