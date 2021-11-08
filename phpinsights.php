<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Preset
    |--------------------------------------------------------------------------
    |
    | This option controls the default preset that will be used by PHP Insights
    | to make your code reliable, simple, and clean. However, you can always
    | adjust the `Metrics` and `Insights` below in this configuration file.
    |
    | Supported: "default", "laravel", "symfony", "magento2", "drupal"
    |
    */

    'preset' => 'symfony',
    /*
    |--------------------------------------------------------------------------
    | IDE
    |--------------------------------------------------------------------------
    |
    | This options allow to add hyperlinks in your terminal to quickly open
    | files in your favorite IDE while browsing your PhpInsights report.
    |
    | Supported: "textmate", "macvim", "emacs", "sublime", "phpstorm",
    | "atom", "vscode".
    |
    | If you have another IDE that is not in this list but which provide an
    | url-handler, you could fill this config with a pattern like this:
    |
    | myide://open?url=file://%f&line=%l
    |
    */

    'ide' => 'vscode',
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may adjust all the various `Insights` that will be used by PHP
    | Insights. You can either add, remove or configure `Insights`. Keep in
    | mind, that all added `Insights` must belong to a specific `Metric`.
    |
    */

    'exclude' => [
        //  'path/to/directory-or-file'
    ],

    'add' => [
        //  ExampleMetric::class => [
        //      ExampleInsight::class,
        //  ]
    ],

    'remove' => [
        SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff::class,
        PhpCsFixer\Fixer\ReturnNotation\ReturnAssignmentFixer::class,
    ],

    'config' => [
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 160,
        ],
        \NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff::class => [
            'exclude' => [
                'src/Entity',
                'src/Handler',
            ],
        ],
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses::class => [
            'exclude' => [
                'src/Entity',
                'src/Kernel.php',
            ],
        ],
        \SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff::class => [
            'exclude' => [
                'src/Handler',
            ],
        ],
        \SlevomatCodingStandard\Sniffs\Classes\SuperfluousAbstractClassNamingSniff::class => [
            'exclude' => [
                'src/Handler',
            ],
        ],
        \SlevomatCodingStandard\Sniffs\Functions\UnusedParameterSniff::class => [
            'exclude' => [
                'src',
            ],
        ],
        \SlevomatCodingStandard\Sniffs\Functions\FunctionLengthSniff::class => [
            'maxLinesLength' => 25,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Requirements
    |--------------------------------------------------------------------------
    |
    | Here you may define a level you want to reach per `Insights` category.
    | When a score is lower than the minimum level defined, then an error
    | code will be returned. This is optional and individually defined.
    |
    */

    'requirements' => [
//        'min-quality' => 0,
//        'min-complexity' => 0,
//        'min-architecture' => 0,
//        'min-style' => 0,
//        'disable-security-check' => false,
    ],

];
