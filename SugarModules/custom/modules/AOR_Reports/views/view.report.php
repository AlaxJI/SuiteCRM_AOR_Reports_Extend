<?php

use SuiteCRM\Custom\Modules\AOR_Reports\AReport;
use SuiteCRM\Custom\Modules\AOR_Reports\ReportView;

require_once('include/MVC/View/SugarView.php');

class AOR_ReportsViewReport extends SugarView //AOR_ReportsViewReport
{
    /**
     * @var AReport
     */
    public $report;
    /**
     * @var ReportView
     */
    public $rv;

    public function init($bean = null, $view_object_map = [])
    {
        parent::init($bean, $view_object_map);

        $this->initReport()->initView();
    }

    public function preDisplay()
    {
        $this->rv->prepare();
    }

    public function display()
    {
        $this->type = 'detail';
        echo $this->getModuleTitle(false);
        $this->type = 'report';
        $this->rv->display();
    }

    public function process()
    {
        // TODO: если экспорт, то нужен экспорт
        parent::process();
    }

    private function initReport()
    {
        $reportName = $this->bean->id;

        $reportClass = '';
        if (file_exists("custom/lib/Modules/AOR_Reports/$reportName/Report.php")) {
            $reportClass = strtr('SuiteCRM\\Custom\\Modules\\AOR_Reports\\<report_name>\\Report', [
                '<report_name>' => $reportName,
            ]);
        }

        if (empty($reportClass)) {
            $reportBean = new AOR_Report();
            $reportBean->mark_deleted($reportName);
            SugarApplication::appendErrorMessage(strtr($GLOBALS['mod_strings']['TPL_CAN_NOT_VIEW_CUSTOM_RECORD'], [
                '::reportName' => $this->bean->id,
            ]));
            SugarApplication::redirect('index.php?module=AOR_Reports&action=index');
        }

        $this->report = new $reportClass();

        return $this;
    }

    private function initView()
    {
        $reportName = $this->bean->id;

        $viewClass = 'SuiteCRM\\Custom\\Modules\\AOR_Reports\\ReportView';
        if (file_exists("custom/lib/Modules/AOR_Reports/$reportName/ReportView.php")) {
            $viewClass = strtr('SuiteCRM\\Custom\\Modules\\AOR_Reports\\<report_name>\\ReportView', [
                '<report_name>' => $reportName,
            ]);
        }

        $this->rv = new $viewClass();
        $this->rv->init($this->report);

        return $this;
    }
}
