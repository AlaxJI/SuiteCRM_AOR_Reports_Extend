<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/AOR_Reports/controller.php';

class CustomAOR_ReportsController extends AOR_ReportsController
{
    public function action_DetailView()
    {
        $dir = "custom/modules/AOR_Reports/reports/{$this->bean->id}";
        if (is_dir($dir)) {
            $this->view = "custom";
        } else {
            $this->view = "detail";
        }
    }
}
