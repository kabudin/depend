<?php

declare(strict_types=1);

namespace Bud\Depend;

use Bud\Depend\Collector\DependCollector;
use Bud\Depend\Listener\DependListener;

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
