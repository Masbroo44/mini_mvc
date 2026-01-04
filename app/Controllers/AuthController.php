<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\User;

final class AuthController extends Controller
{
    /**
     * Vérifie si l'utilisateur est déjà connecté et redirige si nécessaire
     */
    private function redirectIfAuthenticated(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm(): void
    {
        $this->redirectIfAuthenticated();
        
        $this->render('auth/login', params: [
            'title' => 'Connexion',
        ]);
    }

    /**
     * Traite la connexion (avec mot de passe)
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $this->render('auth/login', params: [
                'title' => 'Connexion',
                'message' => 'L\'email et le mot de passe sont requis.',
                'success' => false,
                'old_values' => ['email' => $email],
            ]);
            return;
        }

        $user = User::findByEmail($email);

        if (!$user || empty($user['password']) || !password_verify($password, $user['password'])) {
            $this->render('auth/login', params: [
                'title' => 'Connexion',
                'message' => 'Identifiants invalides.',
                'success' => false,
                'old_values' => ['email' => $email],
            ]);
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'] ?? 'client';

        header('Location: /');
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegisterForm(): void
    {
        $this->redirectIfAuthenticated();
        
        $this->render('auth/register', params: [
            'title' => 'Inscription',
        ]);
    }

    /**
     * Traite l'inscription (nom + email + mot de passe + adresse)
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            return;
        }

        $input = $_POST;
        $nom = trim($input['nom'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $password_confirm = $input['password_confirm'] ?? '';
        $adresse = trim($input['adresse'] ?? '');

        if ($nom === '' || $email === '' || $password === '' || $password_confirm === '') {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'message' => 'Les champs "nom", "email" et mot de passe sont requis.',
                'success' => false,
                'old_values' => $input,
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'message' => 'L\'email n\'est pas valide.',
                'success' => false,
                'old_values' => $input,
            ]);
            return;
        }

        if (strlen($password) < 6) {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'message' => 'Le mot de passe doit contenir au moins 6 caractères.',
                'success' => false,
                'old_values' => $input,
            ]);
            return;
        }

        if ($password !== $password_confirm) {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'message' => 'Les mots de passe ne correspondent pas.',
                'success' => false,
                'old_values' => $input,
            ]);
            return;
        }

        // Vérifie si un utilisateur existe déjà avec cet email
        if (User::findByEmail($email)) {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'message' => 'Un compte existe déjà avec cet email.',
                'success' => false,
                'old_values' => $input,
            ]);
            return;
        }

        $user = new User();
        $user->setNom($nom);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setAdresse($adresse);

        if ($user->save()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Récupère l'utilisateur fraîchement créé pour récupérer son id
            $created = User::findByEmail($email);
            if ($created) {
                $_SESSION['user_id'] = (int) $created['id'];
                $_SESSION['user_nom'] = $created['nom'];
                $_SESSION['user_email'] = $created['email'];
                $_SESSION['user_role'] = $created['role'] ?? 'client';
            }

            $this->render('auth/login', params: [
                'title' => 'Connexion',
                'message' => 'Compte créé avec succès, vous pouvez maintenant vous connecter.',
                'success' => true,
                'old_values' => ['email' => $email],
            ]);
        } else {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'message' => 'Erreur lors de la création du compte.',
                'success' => false,
                'old_values' => $input,
            ]);
        }
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header('Location: /');
    }
}


