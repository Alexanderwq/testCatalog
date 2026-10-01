<?php

namespace App;

class Router
{
    private array $routes = [];

    public function get(string $path, string|callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string|callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';

        foreach ($this->routes[$method] ?? [] as $path => $handler) {
            $pattern = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $path) . '$#';

            if (!preg_match($pattern, $uri, $matches)) {
                continue;
            }

            $params = array_values(array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));

            if (is_string($handler) && class_exists($handler)) {
                $handler = new $handler();
            }

            echo call_user_func_array($handler, $params);
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}