<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Основной класс для формарования отчётов
 *
 *
 * @version 1.0.0-SNAPSHOT
 * @author Алексей Дубровский <dubrovski@call-center.su>,<alaxji@gmail.com>
 */
abstract class ICustomReport
{
    /**
     * Заголовок отчёта
     * @var array
     */
    public $headers = array();

    /**
     * Именование столбцов отчёта и их порядок в результатах
     * @var array
     */
    public $columns = array();

    /**
     * Строки (результаты) отчёта
     * @var array
     */
    public $results = array();

    /**
     * `$_GET` данные, которые поступили в результате заполнения фильтра отчёта
     * @var array
     */
    public $form = array();

    /**
     *
     * @var string
     */
    public $action;

    /**
     * ID отчёта
     * @var string
     */
    public $record;

    /**
     * Название отчёта
     * @var string
     */
    public $title;

    /**
     * Адрес для формирования запроса на экспорт отчёта
     * @var string
     */
    public $export_url;

    /**
     * Адрес страницы без GET-запроса
     * @var string
     */
    public $action_url;

    /**
     * Название модуля
     * @var string
     */
    public $module_name = "Отчёт";

    /**
     * Шаблон для вывода на экран
     * @var string
     */
    public $template = "custom/modules/AOR_Reports/tpls/ReportSimple.tpl";

    /**
     * Массив ошибок при формаровании отчёта
     * @var array
     */
    public $errors = array();

    /**
     * Создание экземпляра объекта. Во время создания заполняются поля:
     * 1. `form`
     * 2. `action`
     * 3. `record`
     * 4. `export_url`
     * 5. `action_url`
     * 6. `title`
     */
    public function __construct()
    {
        $this->form   = $_GET;
        $this->action     = $_GET['action'];
        $this->record     = $_GET['record'];
        $this->export_url = $_SERVER['REQUEST_URI'] . "&export=1";
        $this->action_url = $this->getActionURL();
        if (isset($_GET['record'])) {
            $bean_report = new AOR_Report();
            $bean_report->retrieve($_GET['record']);
            $this->title = $bean_report->name;
        }
    }

    /**
     * Метод для формарования отчёта. Реализуется в клессе конкретного отчёта и вызывается для формирования отчёта. В последующем для вывода на экран используются шаблон установленный в поле `template` в качестве данных в `Smarty` отправляется отчёт в виде массива в переменную `report`. Таким образом, например,  в шаблоне для доступа к полю `record` необходимо использовать `$report.record`.
     */
    abstract public function generate();


    /**
     * Выводит секунды в формате `HH:mm:ss`
     * @param int $time время в секундах
     * @return string
     */
    public function getFormatTime($time)
    {
        return str_pad($time / 60 / 60 % 60, 2, '0', STR_PAD_LEFT) . ":" .
            str_pad($time / 60 % 60, 2, '0', STR_PAD_LEFT) . ":" .
            str_pad($time % 60, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Экспорт отчёта.
     *
     * `$format` может принимать следующие значения: `csv`
     *
     * @param string $format формат экспорта отчёта.
     */
    public function export($format = "csv")
    {
        switch ($format) {
            case "csv":
                $this->exportCSV();
                break;

            default:
                break;
        }
    }

    /**
     * Экспорт отчёта в формате `csv`
     */
    public function exportCSV()
    {
        $csv = '';

        if (count($this->headers) > 0) {
            $csv .= "\"" . join("\";\"", $this->headers) . "\";\r\n";
        }

        foreach ($this->results as $result) {
            if (count($this->columns) > 0) {
                foreach ($this->columns as $column) {
                    $csv .= "\"" . (isset($result[$column]) ? str_replace('"', '\'', $result[$column]) : "") . "\";";
                }

                $csv .= "\r\n";
            } else {
                $csv .= "\"" . join("\";\"", $result) . "\";\r\n";
            }
        }

        $csv = str_replace("<br/>", " ", $csv);
        $csv = str_replace("<br>", " ", $csv);

        $csv = iconv("UTF-8", "cp1251", $csv);

        if (isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
            Header('Content-Type: application/force-download');
        } else {
            Header('Content-Type: application/octet-stream');
        }
        Header('Accept-Ranges: bytes');
        Header('Content-Length: ' . strlen($csv));
        Header("Content-disposition: attachment; filename=\"$this->title.csv\"");

        echo $csv;

        exit;
    }

    /**
     * Получить текущий путь без  GET запроса.
     * @return string
     */
    private function getActionURL()
    {
        // проверяем на то, что идут данные GET запроса
        $end_URI = strpos($_SERVER['REQUEST_URI'], '?');
        if ($end_URI === false) {
            // Если данных нет, то берём полностью URI
            $route = $_SERVER['REQUEST_URI'];
        } else {
            // Если данные есть, то отсекаем их
            $route = substr($_SERVER['REQUEST_URI'], 0, $end_URI);
        }
        return $route;
    }
}
