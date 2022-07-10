<?php

namespace Jundayw\LaravelPolicy;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Jundayw\LaravelPolicy\Middleware\Policies;
use Jundayw\LaravelPolicy\Policies\Policy;

class PolicyServiceProvider extends AuthServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Policy::class => Policy::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/policy.php', 'policy');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/policy.php' => config_path('policy.php'),
            ], 'artisan-policy-config');
        }

        $this->registerBladeExtensions();

        $this->addMiddlewareAlias('policy', Policies::class);
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }

    public function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function(BladeCompiler $bladeCompiler) {
            $bladeCompiler->if('policy', function(string $ability, $arguments = [Policy::class]) {
                return app(Gate::class)->check($ability, $arguments);
            });
            $bladeCompiler->if('policies', function(array $abilities, $arguments = [Policy::class]) {
                return app(Gate::class)->any($abilities, $arguments);
            });
        });
    }

    /**
     * Register the middleware.
     *
     * @param $name
     * @param $class
     * @return mixed
     */
    protected function addMiddlewareAlias($name, $class)
    {
        $router = $this->app['router'];

        if (method_exists($router, 'aliasMiddleware')) {
            return $router->aliasMiddleware($name, $class);
        }

        return $router->middleware($name, $class);
    }
}
