<?php

$reports['Example'] = [
    'templateMeta' => [
        'data_in' => 'rows', // Как выводить результата в страках или столцах: `rows` или `columns`. Сейчас поддерживатеся только вывод в строки. НЕ ПОДДЕРЖИВАЕТСЯ.
        'limit' => '40', // Лимит запроса. Если лимит `< -1` то вывод всех данных. Если лимит равен `-1`, то знечение по умолчанию будет браться из настроек для LV. НЕ ПОДДЕРЖИВАЕТСЯ.
        'form' => [
            // Если необходимо задать свой шаблон, поместите свой шаблон в  `custom/lib/Modules/AOR_Reports/<ReportName>/tpls/ReportGenericRows.tpl`
            'headerTpl' => 'custom/lib/Modules/AOR_Reports/Example/tpls/Header.tpl', // НЕ ПОДДЕРЖИВАЕТСЯ. Поместите свой шаблон в `custom/lib/Modules/AOR_Reports/<ReportName>/tpls/ReportHeader.tpl`
            'footerTpl' => 'custom/lib/Modules/AOR_Reports/Example/tpls/Footer.tpl', // НЕ ПОДДЕРЖИВАЕТСЯ. Поместите свой шаблон в `custom/lib/Modules/AOR_Reports/<ReportName>/tpls/ReportFooter.tpl`
        ],
        'includes' => [
            [
                'file' => 'custom/lib/Modules/AOR_Reports/Example/report.js',
            ],
        ],
    ],
    'searchdefs' => [
        'templateMeta' => [
            'maxColumns' => 4,
            'widths' => [
                'label' => 10,
                'field' => 30,
            ],
        ],
        'layout' => [
            'report_search' => [
                'name',
                'username',
                'reports_to_name' => [
                    'name' => 'reports_to_name',
                    'displayParams' => [
                        // Для поиска по ID а не по имени
                        'useIdSearch' => true,
                    ],
                    'operator' => '=',
                ],
                'roles' => [
                    'name' => 'roles',
                    'vname' => 'LBL_ROLES',
                    'type' => 'varchar',
                ],
                'groups' => [
                    'name' => 'groups',
                    'type' => 'varchar',
                ],
                'date_entered' => [
                    'type' => 'datetime',
                    'name' => 'date_entered',
                    'enable_range_search' => true,
                    'options' => 'date_range_search_dom',
                    'width' => '10%',
                ],
                'date_modified',
            ],
        ],
    ],
    'searchFields' => [
        'name' => [
            'skip' => false, // Не искать по полю. По-умолчанию, `true`
            'query_type' => 'default',
            // для автоматической подстановки в шаблон where
            'table' => 'users',
            'rname' => 'first_name',
        ],
        'username' => [
            'query_type' => 'default',
            // для автоматической подстановки в шаблон where
            'table' => 'users',
            'rname' => 'user_name',
        ],
        // Поиск по ID на равенство, а не на вхождение
        'reports_to_id' => [
            'query_type' => 'default',
            'operator' => '=',
            // для автоматической подстановки в шаблон where
            'table' => 'users',
            'rname' => 'reports_to_id',
        ],
        // Поиск по равенство, а не на вхождение
        'reports_to_name' => [
            // Поле не учавствует в поиске, пропускаем
            'skip' => true,
            'query_type' => 'default',
            'operator' => '=',
            'table' => 'users',
            'rname' => 'list_name',
            'db_field' => [
                'report.reports_to_name',
            ]
        ],
        'roles' => [
            'query_type' => 'default',
            // для автоматической подстановки в шаблон where
            'table' => 'acl_roles',
            'rname' => 'name',
        ],
        'groups' => [
            'query_type' => 'default',
            // для автоматической подстановки в шаблон where
            'table' => 'securitygroups',
            'rname' => 'name',
        ],
        'date_entered' => [
            'query_type' => 'default',
            'is_date_field' => true,
            // для автоматической подстановки в шаблон where
            'table' => 'users',
            'rname' => 'date_entered',
        ],
        'date_modified' => [
            'query_type' => 'default',
            'is_date_field' => true,
            // для автоматической подстановки в шаблон where
            'table' => 'users',
            'rname' => 'date_modified',
        ],
        // Range Search Support
        // date_entered
        'range_date_entered' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true,],
        'start_range_date_entered' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true,],
        'end_range_date_entered' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true,],
        // date_entered
        'range_date_modified' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true,],
        'start_range_date_modified' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true,],
        'end_range_date_modified' => ['query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true,],
    ],
    'listViewDefs' => [
        // Обогащается из полей у которых параметр reportable установлен в `true`
        'NAME' => [
            'default' => false, // Выводить в отчёт или нет по-умолчанию. (Для поддержки выбора столбцов, пока не реализовано) По умолчанию `true`
        ],
    ],
    'fields' => [
        'name' => [
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'string',
            'reportable' => true, // Выводить в отчёт или нет. По умолчанию `true`
        ],
        'username' => [
            'name' => 'username',
            'vname' => 'LBL_USERNAME',
            'type' => 'varchar',
            'len' => 100,
            'reportable' => true,
        ],
        'reports_to_id' => [
            'name' => 'reports_to_id',
            'vname' => 'LBL_REPORTS_TO_ID',
            'type' => 'id',
            'reportable' => false,
        ],
        'reports_to_name' => [
            'name' => 'reports_to_name',
            'rname' => 'last_name',
            'id_name' => 'reports_to_id',
            'vname' => 'LBL_REPORTS_TO_NAME',
            'type' => 'relate',
            'isnull' => 'true',
            'module' => 'Users',
            'table' => 'users',
            'link' => 'reports_to_link',
            'reportable' => false,
            'source' => 'non-db',
            'side' => 'right',
        ],
        'reports_to_link' => [
            'name' => 'reports_to_link',
            'type' => 'link',
            'relationship' => 'user_direct_reports',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_REPORTS_TO',
            'reportable' => false,
        ],
        'roles' => [
            'name' => 'roles',
            'vname' => 'LBL_ROLES',
            'type' => 'text',
            'reportable' => true,
        ],
        'groups' => [
            'name' => 'groups',
            'vname' => 'LBL_GROUPS',
            'type' => 'text',
            'reportable' => true,
        ],
        'date_entered' => [
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetimecombo',
            'reportable' => true,
        ],
        'date_modified' => [
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetimecombo',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'reportable' => true,
        ],
    ],
];
