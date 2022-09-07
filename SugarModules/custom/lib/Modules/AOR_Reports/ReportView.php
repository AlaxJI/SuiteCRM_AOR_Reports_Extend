<?php

namespace SuiteCRM\Custom\Modules\AOR_Reports;

use SuiteCRM\Custom\Modules\AOR_Reports\AReport;
use SuiteCRM\Custom\Modules\AOR_Reports\ViewReportSmarty;

/**
 * Класс для вывода отчёта
 *
 * @author Алексей Дубровский <alaxji@gmail.com>
 */
class ReportView
{
    /**
     * @var AReport
     */
    public $report;
    public $defs;
    public $tpl;
    public $headerTpl;
    public $footerTpl;

    public function init($report)
    {
        if (!($report instanceof AReport)) {
            throw new \Exception('Given is not Report');
        }
        $this->report = $report;
    }

    public function prepare()
    {
        $this->prepareMetaData();
    }

    protected function prepareMetaData()
    {
        $this->defs = $this->report->defs;

        return $this;
    }

    public function fetch()
    {
        $tpl = $this->getTemplate();
        $this->tpl = $this->getReportTpl('ReportGenericRows.tpl');
        $this->headerTpl = $this->getReportTpl('ReportHeader.tpl');
        $this->footerTpl = $this->getReportTpl('ReportFooter.tpl');

        $rs = $this->getReportViewSmarty();
        $rs->setup($this->report, $this->tpl, $this->headerTpl, $this->footerTpl);
        $rs->should_process = isset($_REQUEST['request_data']);
        $rs->displayFields = $this->getDisplayFields();
        if ($rs->should_process) {
            $this->report->generate();
        }
        $rs->displayData = $this->report->getDataList();

        return $rs->display();
    }

    public function display()
    {
        echo $this->fetch();
    }

    public function getReportTpl($tplFile)
    {
        $tpl = strtr('custom/lib/Modules/AOR_Reports/tpls/<tplFile>', [
            '<tplFile>' => $tplFile,
        ]);
        $reportTpl = strtr('custom/lib/Modules/AOR_Reports/<Report>/tpls/<tplFile>', [
            '<Report>' => $this->report->name,
            '<tplFile>' => $tplFile,
        ]);

        if (file_exists($reportTpl)) {
            $tpl = $reportTpl;
        } elseif (!file_exists($tpl)) {
            throw new \Exception(strtr('Core is broken. Can`t find tpl-file [<tplFile>].', [
                        '<tplFile>' => $tplFile,
            ]));
        }

        return $tpl;
    }

    /**
     * @return \SuiteCRM\Custom\Modules\AOR_Reports\ReportViewSmarty
     */
    public function getReportViewSmarty()
    {
        $viewSmartyClass = 'SuiteCRM\\Custom\\Modules\\AOR_Reports\\ReportViewSmarty';
        if (file_exists('custom/lib/Modules/AOR_Reports/' . $this->report->name . '/ReportViewSmarty.php')) {
            $viewSmartyClass = strtr('SuiteCRM\\Custom\\Modules\\AOR_Reports\\<report_name>\\ReportViewSmarty', [
                '<report_name>' => $this->report->name,
            ]);
        }

        return new $viewSmartyClass();
    }

    /**
     *
     * @return array
     * @todo add ACLFieldRole::access
     */
    public function getDisplayFields()
    {
        $displayFields = [];
        foreach ($this->report->defs['listViewDefs']as $key => $value) {
            // TODO: add column chooser
            if (!$value['default']) {
                continue;
            }
            $displayFields[$key] = $value;
        }

        return $displayFields;
    }

    public function getTemplate()
    {
        $type = 'rows';
        if (isset($this->defs['templateMeta']['data_in'])) {
            $type = $this->defs['templateMeta']['data_in'];
        }

        $tpl = 'ReportGenericRows.tpl';
        if ($type === 'columns') {
            // NOTE: Not support yet
            //$tpl = 'ReportGenericColumns.tpl';
        }

        return $tpl;
    }
}
