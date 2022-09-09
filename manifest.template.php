<?php

/**
 *
 */
// @@WARNING@@

$manifest = [
    'acceptable_sugar_versions' => [
        'exact_matches' =>
        [
            '6.4.0',
        ],
        'regex_matches' =>
        [
            '6\.4\.\d',
            '6\.[0-5]\.\d', /** matches 6.1.x,6.2.x,6.3.x,6.4.x,6.5.x * */
        ],
    ],
    'acceptable_sugar_flavors' => [
        'CE'
        , 'PRO'
        , 'ENT'
    ],
    'readme' => 'Модуль расширения отчётов',
    'key' => '',
    'author' => 'Алексей Дубровский / ООО "КУБ ТРИ" ',
    'description' => '',
    'icon' => '',
    'is_uninstallable' => true,
    'name' => 'Extends SuiteCRM AOR_Reports',
    'published_date' => '@@PUBLISH_DATE@@',
    'type' => 'module',
    'version' => '@@VERSION@@',
    'remove_tables' => 'prompt',
];

$installdefs = [
    'id' => 'Extends_SuiteCRM_AOR_Reports',
    'pre_execute' => [
        '<basepath>/scripts/pre_execute.php',
    ],
    'copy' => [
        [
            'from' => '<basepath>/SugarModules/custom/',
            'to' => 'custom/',
        ],
        [
            'from' => '<basepath>/README.md',
            'to' => 'custom/modules/AOR_Reports/README.md',
        ],
        [
            'from' => '<basepath>/CHANGELOG.md',
            'to' => 'custom/modules/AOR_Reports/CHANGELOG.md',
        ],
    ],
];
