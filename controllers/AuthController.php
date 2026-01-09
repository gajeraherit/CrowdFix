<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/helpers.php';

class AuthController
{
    public static function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if (!verifyCsrf($_POST['csrf'] ?? '')) {
                flash('error', 'Invalid session token.');
                redirect('index.php?page=login');
            }
            if ($action === 'register') {
                self::register();
            } elseif ($action === 'login') {
                self::login();
            }
        } elseif (isset($_GET['action']) && $_GET['action'] === 'logout') {
            session_destroy();
            redirect('index.php?page=login');
        }
    }

    private static function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = 'citizen';

        if (!$name || !$email || !$password) {
            flash('error', 'All fields are required.');
            redirect('index.php?page=register');
        }

        if (User::findByEmail($email)) {
            flash('error', 'Email already registered.');
            redirect('index.php?page=register');
        }

        User::register($name, $email, $password, $role);
        flash('success', 'Registered successfully. Please login.');
        redirect('index.php?page=login');
    }

    private static function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = User::authenticate($email, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            redirect('index.php?page=dashboard');
        }
        flash('error', 'Invalid credentials.');
        redirect('index.php?page=login');
    }
}

