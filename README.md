# depend

> 适配 hyperf 框架的注解依赖器，支持权重并且同步兼容配置文件依赖

## 安装

```shell
composer require zeno/depend
```

## 使用方法

> 当配置文件中配置了相同接口的映射并且权重高于注解声明的权重时，则注解无效

```php
<?php
declare(strict_types=1);

namespace App\Service;

use App\TestInterface;
use Zeno\Depend\Annotation\Depend;

#[Depend(TestInterface::class，2)]
class TestService3 implements TestInterface
{

    public function aa(): int
    {
        return 5645645645645645;
    }
}
```
