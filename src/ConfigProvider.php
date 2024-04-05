<?php

declare(strict_types=1);

namespace Zeno\Depend;

use Zeno\Depend\Collector\DependCollector;
use Zeno\Depend\Listener\DependListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'listeners' => [
                DependListener::class
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                    'collectors' => [
                        DependCollector::class,
                    ]
                ],
            ],
        ];
    }
}
