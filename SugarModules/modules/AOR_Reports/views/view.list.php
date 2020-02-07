<?php

require_once 'include/MVC/View/views/view.list.php';

class AOR_ReportsViewList extends ViewList
{

    public function __construct()
    {
        parent::__construct();
        $dir = "custom/modules/AOR_Reports/include/";

        foreach (scandir($dir) as $file) {
            if (substr($file, -3) != "php") {
                continue;
            }
            $this->processFile($dir, $file);
        }

        $dir = "custom/modules/AOR_Reports/reports/";
        foreach (scandir($dir) as $file) {
            $processDir = $dir . $file . DIRECTORY_SEPARATOR;
            if (is_dir($processDir) && file_exists($processDir . $file . ".php")) {
                $this->processFile($processDir, $file . ".php");
            }
        }
    }

    private function processFile($dir, $filename)
    {
        $report = $this->parseReportFile($dir . $filename);
        if (!empty($report) && $filename == $report["id"] . ".php") {
            $this->syncReport($report);
        }
    }

    public function parseReportFile($filename)
    {
        $tokens  = token_get_all(file_get_contents($filename));
        $comment = '';
        foreach ($tokens as $token) {
            $token_name = token_name($token[0]);
            if ($token_name == "T_OPEN_TAG" || $token_name == "T_WHITESPACE") {
                continue;
            }
            if ($token_name != 'T_COMMENT') {
                break;
            } else {
                $comment = $token[1];
                break;
            }
        }
        $result = array();
        if (!empty($comment)) {
            $commentStrings = explode(PHP_EOL, $comment);
            foreach ($commentStrings as $commentString) {
                if (false !== strpos($commentString, " * @id ")) {
                    $result['id'] = trim(substr($commentString, 7));
                }
                if (false !== strpos($commentString, " * @name ")) {
                    $result['name'] = trim(substr($commentString, 9));
                }
                if (false !== strpos($commentString, " * @deleted ")) {
                    $result['deleted'] = trim(substr($commentString, 12));
                }
            }
            if (!isset($result['id']) || !isset($result['name']) || !isset($result['deleted'])) {
                $result = array();
            }
        }
        return $result;
    }

    public function syncReport($report)
    {
        global $db;
        $reportBean = new AOR_Report();
        $reportBean = $reportBean->retrieve($report["id"]);
        if (is_null($reportBean)) {
            if ($report["deleted"] == 0) {
                $db->query("INSERT INTO aor_reports (id, name, report_module, assigned_user_id, date_entered) "
                    . " VALUES('{$report["id"]}', '{$report["name"]}', 'Custom', '1', UTC_TIMESTAMP) "
                    . "ON DUPLICATE KEY UPDATE deleted = 0");
            }
        } else {
            if ($report["deleted"] == 1) {
                $reportBean->mark_deleted($report["id"]);
            }
            $needSave = false;
            if ($reportBean->name != $report["name"]) {
                $reportBean->name = $report["name"];
                $needSave         = true;
            }

            if ($reportBean->report_module != "Custom") {
                $reportBean->report_module = "Custom";
                $needSave                  = true;
            }

            if ($needSave) {
                $reportBean->save();
            }
        }
    }
}
