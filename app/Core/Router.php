<?php
// Active le mode strict pour les types
declare(strict_types=1);
// Espace de noms du noyau
namespace Mini\Core;
// Déclare le routeur HTTP minimaliste
final class Router
{
    // Tableau des routes : [méthode, chemin, [ClasseContrôleur, action]]
    /** @var array<int, array{0:string,1:string,2:array{0:class-string,1:string}} > */
    private array $routes;

    // Routes publiques qui ne nécessitent pas d'authentification
    /** @var array<string> */
    private array $publicRoutes = [
        '/login',
        '/register',
    ];

    /**
     * Initialise le routeur avec les routes configurées
     * @param array<int, array{0:string,1:string,2:array{0:class-string,1:string}} > $routes
     */
    public function __construct(array $routes)
    {
        // Mémorise les routes fournies
        $this->routes = $routes;
    }

    /**
     * Vérifie si l'utilisateur est authentifié
     */
    private function isAuthenticated(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Vérifie si une route est publique (ne nécessite pas d'authentification)
     */
    private function isPublicRoute(string $path): bool
    {
        return in_array($path, $this->publicRoutes, true);
    }

    // Dirige la requête vers le bon contrôleur en fonction méthode/URI
    public function dispatch(string $method, string $uri): void
    {
        // Extrait uniquement le chemin de l'URI
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Vérifie l'authentification pour les routes protégées
        if (!$this->isPublicRoute($path) && !$this->isAuthenticated()) {
            header('Location: /login');
            return;
        }

        // Parcourt chaque route enregistrée
        foreach ($this->routes as [$routeMethod, $routePath, $handler]) {
            // Vérifie correspondance stricte de méthode et de chemin
            if ($method === $routeMethod && $path === $routePath) {
                // Déstructure le gestionnaire en [classe, action]
                [$class, $action] = $handler;
                // Instancie le contrôleur cible
                $controller = new $class();
                // Appelle l'action sur le contrôleur
                $controller->$action();
                return;
            }
        }

        // Si aucune route ne correspond, renvoie un 404 minimaliste
        http_response_code(404);
        echo '404 Not Found';
    }
}


