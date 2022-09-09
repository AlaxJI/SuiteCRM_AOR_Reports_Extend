<?php

namespace SuiteCRM\Custom\Modules\AOR_Reports;

use SuiteCRM\Custom\Modules\AOR_Reports\AReport;
use SuiteCRM\Custom\Utility\TableCssGenerator;

/**
 * Класс для формирования вывода отчёта
 *
 * @author Алексей Дубровский <alaxji@gmail.com>
 */
class ReportViewSmarty
{
    /**
     * @var \TemplateHandler
     */
    public $th;
    /**
     * @var AReport
     */
    public $report;
    public $tpl;
    /**
     * @var \SearchForm
     */
    public $searchForm;
    public $displayFields;
    public $displayData;
    /**
     * @var boolean Нужно ли выводить отчёт
     */
    public $should_process = false;

    public function __construct()
    {
        $this->th = $this->getTemplateHandler();
        $this->th->loadSmarty();
    }

    public function setup($report, $tpl, $headerTpl, $footerTpl)
    {
        $this->report = $report;
        $this->tpl = $tpl;
        $this->th->ss->assign('headerTpl', $headerTpl);
        $this->th->ss->assign('footerTpl', $footerTpl);
        $this->searchForm = $this->getSearchForm();
        $this->searchForm->view = 'ReportSearchForm';
        $searchdefs = [$this->report->module_dir => $this->report->defs['searchdefs']];
        $searchFields = [$this->report->module_dir => $this->report->defs['searchFields']];
        $this->searchForm->setup($searchdefs, $searchFields, 'SearchFormGenericReport.tpl', 'report_search', $this->report->defs['listViewDefs']);
        $this->searchForm->displaySavedSearch = false;
        $this->searchForm->populateFromRequest('report_search');

        $this->searchForm->parsedView = 'report_query_form';
        $this->searchForm->displayType = 'SearchView';
        $this->searchForm->th->ss->assign('form_name', $this->searchForm->parsedView);
        $this->th->ss->assign('searchForm', $this->searchForm->display(false));

        $this->report->searchForm = $this->searchForm;
    }

    public function getTemplateHandler()
    {
        require_once 'include/TemplateHandler/TemplateHandler.php';

        return new \TemplateHandler();
    }

    public function getSearchForm()
    {
        require_once'include/SearchForm/SearchForm2.php';

        return new \SearchForm($this->report, $this->report->module_dir);
    }

    public function display()
    {
        $request_data = !isset($_REQUEST['request_data']) ? 'true' : $_REQUEST['request_data'];

        $pageData = [
            'bean' => [
                'moduleDir' => $this->report->module_dir
            ],
            'ordering' => '',
            'offsets' => [
                'total' => 0, 'next' => 0, 'current' => 0
            ]
        ];

        $styles = TableCssGenerator::getStylizy(TableCssGenerator::TYPE_COLUMN);

        $this->th->ss->assign('report', (array) $this->report);
        $this->th->ss->assign('prerow', false); // Support multiselect (MassUpdate) if need. NOT SUPPORTED. FOR FUTURE.
        $this->th->ss->assign('pageData', $pageData);
        $this->th->ss->assign('fieldCount', count($this->displayFields) + 1);
        $this->th->ss->assign('request_data', $request_data);
        $this->th->ss->assign('should_process', $this->should_process);
        $this->th->ss->assign('displayFields', $this->displayFields);
        $this->th->ss->assign('data', $this->displayData);
        $this->th->ss->assign('rowColor', ['oddListRow', 'evenListRow']);
        $this->th->ss->assign('styles', $styles);

        $this->th->ss->assign('includes', isset($this->report->defs['templateMeta']['includes']) ? $this->report->defs['templateMeta']['includes'] : null);

        return $this->th->displayTemplate($this->report->module_dir, 'ReportDisplay_' . $this->report->name, $this->tpl);
    }
}
