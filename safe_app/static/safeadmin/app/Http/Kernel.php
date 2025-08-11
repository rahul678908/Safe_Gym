protected $routeMiddleware = [
    // Other middleware
    'checkSession' => \App\Http\Middleware\AuthCheck::class,
];
