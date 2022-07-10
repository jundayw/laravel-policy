<?php

namespace Jundayw\LaravelPolicy;

use Illuminate\Database\Eloquent\Model;

interface PolicyContract
{
    /**
     * @param string $ability
     * @param Model $authenticate
     * @param mixed ...$arguments
     * @return bool
     */
    public function hasPolicies(string $ability, Model $authenticate, mixed ...$arguments): bool;

    /**
     * @param string $ability
     * @param mixed $arguments
     * @return string[]
     */
    public function getPolicies(string $ability, mixed $arguments): array;
}
