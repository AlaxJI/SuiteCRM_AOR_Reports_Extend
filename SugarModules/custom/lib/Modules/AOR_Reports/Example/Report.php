<?php

/*
 * Пример составлкение простого отчёта
 *
 * @id Example
 * @name Example
 * @deleted 1
 * @module Users
 */

namespace SuiteCRM\Custom\Modules\AOR_Reports\Example;

use SuiteCRM\Custom\Modules\AOR_Reports\AReport;

class Report extends AReport
{
    protected const REPORT_QUERY = '
        SELECT
            users.first_name AS name,
            users.user_name AS username,
            users.date_entered,
            users.date_modified,
            GROUP_CONCAT(acl_roles.name) AS roles,
            GROUP_CONCAT(securitygroups.name) AS groups
        FROM
            users
        LEFT JOIN
            acl_roles_users
                ON
                    acl_roles_users.user_id = users.id AND acl_roles_users.deleted = 0
        LEFT JOIN
            acl_roles
                ON
                    acl_roles.id = acl_roles_users.role_id AND acl_roles.deleted = 0
        LEFT JOIN
            securitygroups_users
                ON securitygroups_users.user_id = users.id AND securitygroups_users.deleted = 0
        LEFT JOIN
            securitygroups
                ON
                securitygroups.id = securitygroups_users.securitygroup_id AND securitygroups.deleted = 0
        WHERE
            1 = 1
            <where>
        GROUP BY
            users.id
        ';

    public function getSqlTemplate()
    {
        return self::REPORT_QUERY;
    }
}
