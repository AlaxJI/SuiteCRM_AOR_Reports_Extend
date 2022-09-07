<?php

require_once 'include/MVC/View/views/view.list.php';

class AOR_ReportsViewList extends ViewList
{
    public function __construct()
    {
        global $app_list_strings;
        parent::__construct();

        $dir = 'custom/lib/Modules/AOR_Reports/';
        foreach (scandir($dir) as $reportName) {
            $processDir = $dir . $reportName . DIRECTORY_SEPARATOR;
            if (is_dir($processDir) && file_exists($processDir . 'Report.php')) {
                $this->processFile($processDir, 'Report.php');
            }
        }

        $app_list_strings['aor_moduleList']['Custom'] = 'Custom';
    }

    private function processFile($dir, $filename)
    {
        $report = $this->parseReportFile($dir . $filename);
        if (!empty($report)) {
            $this->syncReport($report);
        }
    }

    public function parseReportFile($filename)
    {
        $tokens = token_get_all(file_get_contents($filename));
        $comment = '';
        foreach ($tokens as $token) {
            $token_name = token_name($token[0]);
            if ($token_name == 'T_OPEN_TAG' || $token_name == 'T_WHITESPACE') {
                continue;
            }
            if ($token_name != 'T_COMMENT') {
                break;
            } else {
                $comment = $token[1];
                break;
            }
        }
        $result = [];
        if (!empty($comment)) {
            $commentStrings = explode(PHP_EOL, $comment);
            foreach ($commentStrings as $commentString) {
                if (false !== strpos($commentString, ' * @id ')) {
                    $result['id'] = trim(substr($commentString, 7));
                } elseif (false !== strpos($commentString, ' * @name ')) {
                    $result['name'] = trim(substr($commentString, 9));
                } elseif (false !== strpos($commentString, ' * @deleted ')) {
                    $result['deleted'] = trim(substr($commentString, 12));
                } elseif (false !== strpos($commentString, ' * @module ')) {
                    $result['report_module'] = trim(substr($commentString, 11));
                }
            }
            if (!isset($result['report_module'])) {
                $result['report_module'] = '';
            }
            if (!isset($result['id']) || !isset($result['name']) || !isset($result['deleted'])) {
                $result = [];
            }
        }
        return $result;
    }

    public function syncReport($report)
    {
        $aorReport = new AOR_Report();
        $db = $aorReport->db;
        $aorReport = $aorReport->retrieve($report['id']);
        if (is_null($aorReport)) {
            if ($report['deleted'] == 0) {
                $sql = strtr('
                    INSERT INTO
                        aor_reports
                            (id, name, report_module, date_entered)
                            VALUES(<id>, <name>, <report_module>, UTC_TIMESTAMP)
                        ON DUPLICATE KEY
                            UPDATE
                                deleted = 0
                ', [
                    '<id>' => $db->quoted($report['id']),
                    '<name>' => $db->quoted($report['name']),
                    '<report_module>' => $db->quoted('Custom'),
                ]);
                $db->query($sql);
            }
        } else {
            if ($report['deleted'] == 1) {
                $aorReport->mark_deleted($report['id']);
            }
            $needSave = false;
            if ($aorReport->name != $report['name']) {
                $aorReport->name = $report['name'];
                $needSave = true;
            }

            if ($aorReport->report_module != $report['report_module']) {
                $aorReport->report_module = $report['report_module'];
                $needSave = true;
            }

            if ($needSave) {
                $aorReport->save();
            }
        }
    }
}
