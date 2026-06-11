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

        // Create User
        $userId = $this->userModel->create([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'role'     => 'developer'
        ]);

        if ($userId) {
            $token = $this->userModel->generateVerificationToken($userId);
            $verificationLink = "/PhaseFlow/public/verify-email?token=" . $token;

            echo json_encode([
                'status'   => 'success',
                'message'  => 'Registration successful! Please verify your email.',
                'redirect' => $verificationLink
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Please try again.']);
        }
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

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
            return;
        }

        if (empty($user['email_verified_at'])) {
            echo json_encode(['status' => 'error', 'message' => 'Please verify your email first.']);
            return;
        }

        // Start Session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        $this->userModel->updateLastLogin($user['id']);

        echo json_encode([
            'status'   => 'success',
            'message'  => 'Login successful!',
            'redirect' => '/PhaseFlow/public/dashboard'
        ]);
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