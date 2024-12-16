# 安装方法

命令行下, 执行 composer 命令安装:

````
composer require jundayw/laravel-policy
````

[![Latest Stable Version](https://poser.pugx.org/jundayw/laravel-policy/v)](https://packagist.org/packages/jundayw/laravel-policy)
[![Total Downloads](https://poser.pugx.org/jundayw/laravel-policy/downloads)](https://packagist.org/packages/jundayw/laravel-policy)
[![Latest Unstable Version](https://poser.pugx.org/jundayw/laravel-policy/v/unstable)](https://packagist.org/packages/jundayw/laravel-policy)
[![License](https://poser.pugx.org/jundayw/laravel-policy/license)](https://packagist.org/packages/jundayw/laravel-policy)
[![PHP Version Require](https://poser.pugx.org/jundayw/laravel-policy/require/php)](https://packagist.org/packages/jundayw/laravel-policy)

# 使用方法

## Model

需要验证权限的 `App\Models\User` 继承 `Jundayw\LaravelPolicy\PolicyContract`，
并实现 `getPolicies(string $ability, mixed $arguments)` 方法，返回如下类型的权限数组

```php
namespace App\Models;

use Jundayw\LaravelPolicy\PolicyContract;
use Jundayw\LaravelPolicy\Policy;

class Manager extends Authenticate implements PolicyContract
{
    use Policy;

    /**
     * @param string $ability
     * @param mixed $arguments
     * @return string[]
     */
    public function getPolicies(string $ability, mixed $arguments): array
    {
        // do anything for get polices
        return [
            // "backend.module.list",
            // "backend.module.create",
            // "backend.module.store",
            // "backend.module.edit",
            // "backend.module.update",
            // "backend.module.destroy",
            // "backend.policy.list",
            // "backend.policy.create",
            // "backend.policy.store",
            // "backend.policy.edit",
            // "backend.policy.update",
            // "backend.policy.destroy",
            // "backend.role.*",
            // "backend.*.*",
        ];
    }
}
```

## 使用内置中间件

控制器中使用

```php
class AuthController extends CommonController
{
    public function __construct()
    {
        $this->middleware('policy');
    }
}
```

或者路由中使用

```php
Route::middleware('policy')->group(function(){});
```

内置中间件验证中，若无权访问将抛出 `Jundayw\LaravelPolicy\Exceptions\PolicyException` 异常，注意捕获

## 自定义中间件

```php
use Jundayw\LaravelPolicy\Policies\Policy;
/**
 * Handle an incoming request.
 *
 * @param Request $request
 * @param Closure $next
 * @return Response|PolicyException
 */
public function handle(Request $request, Closure $next)
{
    // 传入当前请求需要的权限标识符，可自定义任意形式
    // 如
    // admin.role.delete
    // admin/role/delete
    // admin-role-delete
    // adminRoleDelete
    // ...
    // 注意与 getPolicies 方法返回类型一致
    $policy = '';
    if ($request->user()->can($policy, [Policy::class])) {
        return $next($request);
    }
    // 自定义无权访问行为
    throw new Exception('UnAuthorized Access.');
}
```

# 调试模式

## 发布配置文件

```php
php artisan vendor:publish --tag=artisan-policy-config
```

配置 `.env` 文件，追加

```php
POLICY_ENABLED=false
```

配置为 `false` 关闭权限验证，默认为 `true`
