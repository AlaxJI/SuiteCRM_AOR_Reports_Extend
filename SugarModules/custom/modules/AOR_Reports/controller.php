<?php

require_once 'modules/AOR_Reports/controller.php';

class CustomAOR_ReportsController extends AOR_ReportsController
{
    public function action_DetailView()
    {
        $view = 'detail';
        $reportFile = strtr('custom/lib/Modules/AOR_Reports/<reportName>/Report.php', [
            '<reportName>' => $this->bean->id,
        ]);
        if (file_exists($reportFile)) {
            $view = 'report';
        }

        $this->view = $view;
    }

    public function action_EditView()
    {
        $view = 'edit';
        $reportFile = strtr('custom/lib/Modules/AOR_Reports/<reportName>/Report.php', [
            '<reportName>' => $this->bean->id,
        ]);
        if (file_exists($reportFile)) {
            SugarApplication::appendErrorMessage(strtr($GLOBALS['mod_strings']['TPL_CAN_NOT_EDIT_CUSTOM_RECORD'], [
                '::reportName' => $this->bean->id,
            ]));
            SugarApplication::redirect('index.php?module=AOR_Reports&action=index');
        }
        $this->view = $view;
    }
}
