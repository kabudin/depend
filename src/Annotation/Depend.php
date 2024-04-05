<?php
declare(strict_types=1);

namespace Zeno\Depend\Annotation;

use Attribute;
use ReflectionClass;
use Zeno\Depend\Collector\DependCollector;
use Hyperf\Di\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_CLASS)]
class Depend extends AbstractAnnotation
{
    /**
     * 自动依赖，用于简化 config/autoload/dependencies.php 中手动配置
     * @param string|null $interface 接口类
     * @param int $priority 优先级权重，默认权重为1.当配置文件中相同接口的权重高于注解声明的权重，则注解无效
     */
    public function __construct(public ?string $interface = null, public int $priority  = 1)
    {
    }

    /**
     * @param string $className
     * @throws \ReflectionException
     */
    public function collectClass(string $className): void
    {
        $interface = $this->interface;
        if (empty($interface)){
            $reflectionClass = new ReflectionClass($className);
            $interfaces = $reflectionClass->getInterfaceNames();
            $interface = count($interfaces) === 0 ? $className : $interfaces[0];
        }
        DependCollector::setAround($className, $interface, $this->priority);
    }
}
