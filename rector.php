<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests/Src',
    ])
    ->withPhpSets(php80: true)
    ->withImportNames(importShortClasses: false)
    ->withSets([
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_50,
        SilverstripeSetList::CODE_QUALITY,
    ]);
