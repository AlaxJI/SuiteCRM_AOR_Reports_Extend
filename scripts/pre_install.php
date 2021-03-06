<?php

/**
 * Asterisk SugarCRM Integration
 * (c) KINAMU Business Solutions AG 2009
 *
 * Parts of this code are (c) 2006. RustyBrick, Inc.  http://www.rustybrick.com/
 * Parts of this code are (c) 2008 vertico software GmbH
 * Parts of this code are (c) 2009 abcona e. K. Angelo Malaguarnera E-Mail admin@abcona.de
 * http://www.sugarforge.org/projects/yaai/
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
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

function pre_install()
{
    global $sugar_config;
    require_once('include/utils.php');

    // creates logic hook that inserts Asterisk JavaScript into every UI frame
    // This could be moved to manifest! See
//
    //   array(
    //'module' => '',
    //'hook' => 'after_ui_frame',
    //'order' => 67,
    //'description' => 'Add Tab to User Editor',
    //'file' => 'custom/modules/Users/Users_Enhanced.php',
    //'class' => 'defaultHomepage',
    //'function' => 'addTab',
    //),
//
    //  check_logic_hook_file("", "after_ui_frame",
    //  array(1, 'Asterisk', 'custom/modules/Asterisk/include/AsteriskJS.php','AsteriskJS', 'echoJavaScript'));
    // creates Asterisk logging table
    // TODO detect if it's MSSQL and raise error
    if (empty($db)) {
        if (!class_exists('DBManagerFactory')) {
            die("no db available");
        }
        $db = & DBManagerFactory::getInstance();
    }
    
    //$query = "DROP TABLE IF EXISTS asterisk_log";
    //$db->query($query, false, "Error dropping asterisk_log table: " . $query);
}

// http://www.edmondscommerce.co.uk/mysql/mysql-add-column-if-not-exists-php-function/
function add_column_if_not_exist($db, $table, $column, $column_attr = "VARCHAR( 255 ) NULL")
{
    $exists = false;
    $cols   = $db->get_columns($table);
    if (!array_key_exists($column, $cols)) {
        $db->query("ALTER TABLE `$table` ADD `$column`  $column_attr");
    }
}
