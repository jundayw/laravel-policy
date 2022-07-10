<?php

namespace Jundayw\LaravelPolicy\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jundayw\LaravelPolicy\Exceptions\PolicyException;
use Jundayw\LaravelPolicy\Policies\Policy;
use Jundayw\LaravelPolicy\Support\NamespaceControllerActionNameTrait;

class Policies
{
    use NamespaceControllerActionNameTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response|PolicyException
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->can($this->getNamespaceControllerActionName('.'), [Policy::class])) {
            return $next($request);
        }

        throw new PolicyException('UnAuthorized Access.');
    }
}
