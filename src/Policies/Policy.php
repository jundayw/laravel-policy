<?php

namespace Jundayw\LaravelPolicy\Policies;

use BadMethodCallException;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Policy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function __call($method, $arguments)
    {
        if (app('config')->get('policy.enabled') === false) {
            return true;
        }

        $method = Str::kebab($method);

        $instance       = Arr::first($arguments);
        $instanceClass  = get_class($instance);
        $instanceMethod = 'hasPolicies';

        if (method_exists($instanceClass, $instanceMethod) === false) {
            throw new BadMethodCallException("Call to undefined method {$instanceClass}::{$instanceMethod}()");
        }

        return call_user_func_array([$instance, $instanceMethod], [$method, ...$arguments]);
    }
}
