<?php
declare(strict_types=1);

namespace Bud\Depend\Listener;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Definition\PriorityDefinition;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Bud\Depend\Collector\DependCollector;

class DependListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var StdoutLoggerInterface
     */
    protected StdoutLoggerInterface $console;

    public function __construct(StdoutLoggerInterface $console, ContainerInterface $container)
    {
        $this->console = $console;
        $this->container = $container;
    }

    public function listen(): array
    {
        return [BootApplication::class];
    }

    /**
     * @param object $event
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function process(object $event): void
    {
        $data = DependCollector::list();
        foreach ($data as $class => $item) {
            list($interface, $priority) = $item;
            if (!interface_exists($interface)) {
                $this->console->error(sprintf('Dependencies [%s] Injection to the [%s] failed.', $class, $interface));
                continue;
            }
            $config = $this->container->get(ConfigInterface::class);
            $oldClass = $config->get("dependencies.{$interface}");
            if ($oldClass instanceof PriorityDefinition) {
                // 如果现有绑定中有权重则合并
                $definition = $oldClass->merge(new PriorityDefinition($class, $priority));
            } else {
                // 现有绑定中没有权重或者当前绑定权重大于0 则覆盖 否则不覆盖
                $definition = (is_null($oldClass) || $priority > 0) ? new PriorityDefinition($class, $priority) : new PriorityDefinition($oldClass);
            }
            $config->set("dependencies.{$interface}", $definition);
            $this->container->define($interface, $definition->getDefinition());
            if (!$this->container->has($interface)) {
                $this->console->error(sprintf('Dependencies [%s] Injection to the [%s] failed.', $class, $interface));
            }
        }
    }
}
