<?php

/**
 * Asterisk SugarCRM Integration
 * (c) KINAMU Business Solutions AG 2009
 *
 * Parts of this code are (c) 2006. RustyBrick, Inc.  http://www.rustybrick.com/
 * Parts of this code are (c) 2008 vertico software GmbH
 * Parts of this code are (c) 2009 abcona e. K. Angelo Malaguarnera E-Mail admin@abcona.de
 * http://www.sugarforge.org/projects/yaai/
 * Changes to make this package work with SugarCRM v6 and Asterisk 1.6 and 1.8 by Sebastiaan Tieland
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact KINAMU Business Solutions AG at office@kinamu.com
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 *
 */
// @@WARNING@@




$manifest = array(
    'acceptable_sugar_versions' =>
    array(
        'exact_matches' =>
        array(
            1 => '6.4.0',
        ),
        'regex_matches' =>
        array(
            1 => '6\.4\.\d',
            2 => '6\.[0-5]\.\d', /** matches 6.1.x,6.2.x,6.3.x,6.4.x,6.5.x * */
        ),
    ),
    'acceptable_sugar_flavors'  =>
    array(
        'CE'
        , 'PRO'
        , 'ENT'
    ),
    'readme'                    => 'Модуль расширения отчётов',
    'key'                       => '',
    'author'                    => 'Алексей Дубровский / ООО "Контакт центр" ',
    'description'               => '',
    'icon'                      => '',
    'is_uninstallable'          => true,
    'name'                      => 'Extends SuiteCRM AOR_Reports',
    'published_date'            => '@@PUBLISH_DATE@@',
    'type'                      => 'module',
    'version'                   => '@@VERSION@@',
    'remove_tables'             => 'prompt',
);

$installdefs = array(
    'id'   => 'Extends_SuiteCRM_AOR_Reports',
    'copy' =>
    array(
        array(
            'from' => '<basepath>/SugarModules/modules/AOR_Reports',
            'to'   => 'custom/modules/AOR_Reports',
        ),
    ),
);

?>
