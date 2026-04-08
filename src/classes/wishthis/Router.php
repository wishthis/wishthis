<?php

namespace wishthis;

class Router
{
    private array $routes = [];

    public function __construct()
    {
    }

    public function add(string $method, string $path, array $handler): void
    {
        $path = \sprintf('#^%1$s$#', \str_replace('#', '\\#', $path));

        $this->routes[$method][$path] = $handler;
    }

    public function get(string $path, array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, array $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, array $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    public function resolve(string $path): void
    {
        global $options;

        $method = $_REQUEST['METHOD']    ?? $_SERVER['REQUEST_METHOD'];
        $routes = $this->routes[$method] ?? [];

        $user           = User::getCurrent();
        $userIsLoggedIn = $user->isLoggedIn();
        $userPower      = $user->getPower();
        $userIsAdmin    = 100 === $userPower;

        $versionCurrent    = $options->version;
        $versionNew        = VERSION;
        $versionIsOutdated = \version_compare($versionCurrent, $versionNew, '<');

        if ($versionIsOutdated) {
            $pathNeedsRedirect = 'GET' === $method && !\in_array(
                $path,
                [
                    Page::PAGE_LOGIN,
                    Page::PAGE_UPDATE,
                    Page::PAGE_MAINTENANCE,
                ]
            );

            if ($pathNeedsRedirect) {
                if ($userIsAdmin) {
                    redirect(Page::PAGE_UPDATE);
                } else {
                    redirect(Page::PAGE_MAINTENANCE);
                }
            }
        }

        foreach ($routes as $pattern => [$controllerClass, $controllerMethod]) {
            $pathMatches = 1 === \preg_match($pattern, $path, $matches);

            if (!$pathMatches) {
                continue;
            }

            $matches = \array_filter(
                $matches,
                function (mixed $key): string {
                    return \is_string($key);
                },
                \ARRAY_FILTER_USE_KEY
            );

            $controller                       = new $controllerClass($matches);
            $controllerRequiresAuthentication = $controller->getRequiresAuthentication();

            $acceptHeader         = $_SERVER['HTTP_ACCEPT'];
            $acceptList           = \explode(',', $acceptHeader);
            $acceptListNormalised = \array_map(
                function (string $contentAndQuality): string {
                    $parts = \explode(';', $contentAndQuality);

                    return $parts[0];
                },
                $acceptList
            );
            $acceptContent        = \reset($acceptListNormalised);

            if ($controllerRequiresAuthentication && !$userIsLoggedIn) {
                if ('text/html' === $acceptContent) {
                    \redirect(Page::PAGE_LOGIN);
                } else {
                    \http_response_code(403);
                    die();
                }
            } else {
                $controller->$controllerMethod();
            }

            return;
        }

        \http_response_code(404);
        die();
    }
}
