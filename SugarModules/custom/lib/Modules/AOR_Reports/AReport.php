<?php

/*
 *  Описание отчёта
 *
 * @id <ReportName>
 * @name Название отчёта
 * @deleted 0|1
 * @version Версия
 */

namespace SuiteCRM\Custom\Modules\AOR_Reports;

use SuiteCRM\Custom\Modules\AOR_Reports\IReport;

/**
 * Основной класс для формарования отчётов
 *
 * @author Алексей Дубровский <alaxji@gmail.com>
 */
abstract class AReport extends \SugarBean implements IReport
{
    /**
     * @var array Массив строк/столбцов (единицы) отчёта в виде массива. Массив единицы отчёта с ключами в ВЕРХНЕМ_РЕГИСТРЕ. Заполняется при построении отчёта.
     */
    protected $dataList = [];
    /**
     * @var array Массив строк/столбцов (единицы) отчёта в виде объектов `ReportData`. Заполняется при построении отчёта.
     */
    protected $dataReports = [];
    /**
     * @var \DBManager Указатель на объект базы данных. Заполянется при содании объекта.
     */
    public $db;
    /**
     * @var array Метаданные об отчёте. Заполняются обогащёнными данными из файла `reportdefs.php` при содании объекта.
     */
    public $defs;
    /**
     * @var array Метаданные полей отчёта. Заполняются из файла `reportdefs.php ` при создании объекта. Оставлен для совместимости в SugarBean.
     */
    public $field_defs;
    /**
     * @var array Метаданные полей отчёта. Заполняются из файла `reportdefs.php ` при создании объекта. Оставлен для совместимости в SugarBean.
     */
    public $field_name_map;
    /**
     * @var string Директория модуля (отчёта) Заполняется автоматически при создании объекта и имеет вид `AOR_Reports/<ReportName>`. Служит для совместимости с SugarBean.
     */
    public $module_dir;
    /**
     * @var string Имя отчёта. Заполняется автоматически при создании объекта из `namespace`.
     */
    public $name;
    /**
     * @var string Полный SQL-запрос отчёта.
     */
    protected $sql;
    /**
     * @var string Имя таблицы в БД. Не менять. Оставлен для совместимости с SugarBean. Таблица `report` не создаётся в БД, а значение является заглушкой.
     */
    public $table_name = 'report';
    /**
     * @var \SearchForm
     */
    public $searchForm;

    public function __construct()
    {
        $this->db = \DBManagerFactory::getInstance();

        $class = get_class($this);
        $class = explode('\\', $class);
        $this->id = $class[count($class) - 2];
        $this->name = $class[count($class) - 2];
        $this->module_dir = 'AOR_Reports/' . $this->name;

        $this->prepareMetaData()->processLanguage();
    }

    /**
     * Метод формирует и выдаёт SQL-запрос для подсчёта количество данных в отчёте. *Сейчас не применяется. Для будущего использования.*
     * @param string $sql SQL-запрос для формирования запроса подсчёта количество данных в отчёте
     * @return null
     * @todo Write the handler
     */
    public function countSql($sql)
    {
        return null;
    }

    /**
     * Метод для формарования отчёта. Запрашивает SQL-Запрос (см. `AReport::getSql()`). Формирует лимиты данных (см. `AReport::limitSql($sql, $offset, $limit)`  в текушей версии не поддерживается). Выполняет запрос и заполняет массивы `AReport::dataList` и`AReport::dataReports` через `AReport::processRow()`
     *
     * `$offset` принимает значения: `>= 0` для определения смещения,   `-1` - данные будут браться из настройки системы, `< -1`  - смещение не будет определено в запросе.
     *
     * `$limit` принимает значения: `>= 0` для определения количества получаемых строк напрямую,   `-1` - данные будут браться из настройки системы, `< -1`  - количество получаемых строк не будет определено в запросе.
     * @param int $offset смещение для получение данных в отчёт, по-умолчавнию, `-1`.
     * @param int $limit количество строк для получение данных в отчёт, по-умолчавнию, `-1`.
     * @return $this
     * @see AReport::getSql() Запрос SQL-запроса
     * @see AReport::limitSql() Формирование лимитов данных
     * @see AReport::$dataList
     * @see AReport::$dataReports
     */
    public function generate($offset = -1, $limit = -1)
    {
        $sql = $this->getSql();
        $sql = $this->limitSql($sql, $offset = -1, $limit = -1);
        $countSql = $this->countSql($sql);

        if (empty($sql)) {
            return $this;
        }

        $result = $this->db->query($sql);
        if ($this->db->lastDbError() !== false) {
            \SugarApplication::appendErrorMessage('Database error. Please check suitecrm.log for details.');
            \SugarApplication::redirect('index.php?module=AOR_Reports&action=DetailView&record=' . $this->name);
        }

        while ($row = $this->db->fetchByAssoc($result)) {
            $this->processRow($row);
        }

        return $this;
    }

    /**
     * Возвращает массив строк/столбцов (единицы) отчёта в виде массива. Массив единицы отчёта с ключами в ВЕРХНЕМ_РЕГИСТРЕ. Заполняется при построении отчёта. **Может быть перезаписана в классе отчёта.**
     * @return array
     * @see AReport::generate() Построение отчёта
     */
    public function getDataList()
    {
        return $this->dataList;
    }

    /**
     * Формирует SQL-запрос из шаблона и блока `where`
     * @return string
     * @see AReport::getSqlTemplate() Шаблон SQL-запроса
     * @see AReport::getWhere() Блок **WHERE** в виде массива
     * @see AReport::parseWhere() Построение блока **WHERE** в виде строки.
     */
    public function getSql()
    {
        $where = $this->getWhere();
        $whereConditions = $this->parseWhere($where);

        $this->sql = strtr($this->getSqlTemplate(), $whereConditions);

        return $this->sql;
    }

    /**
     * Через класс `SearchForm` формирует условия запроса и приводит уловия к массиву вида, где ключ - это поле отчёта, значение элемента - это шаблон условия где вместо поля отчёта устанавливается его шаблон вида `<field_name>`, которые впоследствии можно заменить на необходимое значение вида, например, `table.field`.
     * @return array
     */
    protected function getWhere()
    {
        $searchWhere = $this->searchForm->generateSearchWhere();
        $regExp = '/report\.\S+/m';
        $where = [];
        foreach ($searchWhere as $key => $condition) {
            $matches = [];
            preg_match_all($regExp, $condition, $matches, PREG_SET_ORDER, 0);
            if (empty($matches)) {
                continue;
            }
            $field = str_replace('report.', '', $matches[0][0]);
            $where[$field] = preg_replace($regExp, '<' . $field . '>', $condition);
        }

        return $where;
    }

    /**
     * Метод для формарования отчёта. Запрашивает SQL-Запрос (см. `AReport::getSql()`). Формирует лимиты данных (см. `AReport::limitSql($sql, $offset, $limit)`  в текушей версии не поддерживается). Выполняет запрос и заполняет массивы `AReport::dataList` и`AReport::dataReports`
     *
     * `$offset` принимает значения: `>= 0` для определения смещения,   `-1` - данные будут браться из настройки системы, `< -1`  - смещение не будет определено в запросе.
     *
     * `$limit` принимает значения: `>= 0` для определения количества получаемых строк напрямую,   `-1` - данные будут браться из настройки системы, `< -1`  - количество получаемых строк не будет определено в запросе.
     * @param string $sql SQL-запрос к которому будет добавлены данные для смещения и ограничения строк запроса
     * @param int $offset смещение для получение данных в отчёт, по-умолчавнию, `-1`.
     * @param int $limit количество строк для получение данных в отчёт, по-умолчавнию, `-1`.
     * @return string
     * @todo Write the handler
     */
    public function limitSql($sql, $offset = -1, $limit = -1)
    {
        return $sql;
    }

    /**
     * Разбирает медатанные отчёта, обогащает их необхордимыми данными, загружает языковой пакет и возвращает обработанные метаданнные
     * @param array $reportdefs Массив с сырыми метаданными отчёта
     * @return array
     */
    protected function parseDefs($reportdefs)
    {
        if (!isset($reportdefs['listViewDefs'])) {
            $reportdefs['listViewDefs'] = [];
        }
        foreach ($reportdefs['fields'] as $field => $fieldDef) {
            $fieldname = strtoupper($fieldDef['name']);
            if (isset($fieldDef['reportable']) && !parseBool($fieldDef['reportable'])) {
                continue;
            }
            $listViewDef = [
                'label' => isset($fieldDef['vname']) ? $fieldDef['vname'] : 'LBL_' . $fieldname,
                'link' => isset($fieldDef['link']) ? parseBool($fieldDef['link']) : false,
                'default' => true,
            ];

            if (isset($reportdefs['listViewDefs'][$fieldname])) {
                $listViewDef = array_merge($listViewDef, $reportdefs['listViewDefs'][$fieldname]);
            }
            $reportdefs['listViewDefs'][$fieldname] = $listViewDef;
        }

        $reportdefs['searchdefs']['layout']['advanced_search'] = $reportdefs['searchdefs']['layout']['report_search'];

        return $reportdefs;
    }

    /**
     * Формирует массив для подстановки условий в шаблон SQL-запроса.
     * @param array $where Массив с шаблонами условий
     * @return array
     * @see SuiteCRM\Custom\Modules\AOR_Reports\AReport::getWhere
     */
    public function parseWhere($where)
    {
        $fieldDefs = $this->field_defs;
        $whereArray = [];
        $whereAndArray = [];
        foreach ($this->defs['searchFields'] as $key => $searchDef) {
            if (isset($searchDef['skip']) && parseBool($searchDef['skip'])) {
                continue;
            }
            $fieldname = $key;
            if (strpos($fieldname, 'range') !== false) {
                continue;
            }
            if (!isset($where[$fieldname])) {
                $whereAndArray['<' . $fieldname . '>'] = '';
                continue;
            }
            $condition = $where[$fieldname];
            $field = $fieldname;
            $table = '';

            if (isset($searchDef['table'])) {
                $table = $searchDef['table'];
            } elseif (isset($fieldDefs[$fieldname], $fieldDefs[$fieldname]['table'])) {
                $table = $fieldDefs[$fieldname]['table'];
            }

            if (isset($searchDef['rname'])) {
                $field = $searchDef['rname'];
            } elseif (isset($fieldDefs[$fieldname], $fieldDefs[$fieldname]['rname'])) {
                $field = $fieldDefs[$fieldname]['rname'];
            }

            $whereArray['<' . $fieldname . '>'] = strtr($condition, [
                '<' . $fieldname . '>' => implode('.', [$table, $field]),
            ]);
            $whereAndArray['<' . $fieldname . '>'] = ' AND ' . strtr($condition, [
                    '<' . $fieldname . '>' => implode('.', [$table, $field]),
            ]);
        }

        $whereAndArray['<where>'] = '';
        if (!empty($whereArray)) {
            $whereAndArray['<where>'] = 'AND (' . implode(' AND ', $whereArray) . ')';
        }

        return $whereAndArray;
    }

    /**
     * Загружает метаданные из файла `custom/lib/Modules/AOR_Reports/<reportName>/reportdefs.php`, отдаёт их на обработку и заполняет  необходимые поля объекта.
     * @return $this
     * @throws \Exception
     * @see SuiteCRM\Custom\Modules\AOR_Reports\AReport::parseDefs
     */
    protected function prepareMetaData()
    {
        $matadataFile = strtr('custom/lib/Modules/AOR_Reports/<reportName>/reportdefs.php', [
            '<reportName>' => $this->name,
        ]);
        if (!file_exists($matadataFile)) {
            throw new \Exception('No defs found');
        }

        $reports = [];
        include $matadataFile;

        if (!isset($reports[$this->name], $reports[$this->name]['fields'])) {
            throw new \Exception('There are no fields defs found');
        }
        $this->defs = $this->parseDefs($reports[$this->name]);

        $this->field_defs = $this->defs['fields'];
        $this->field_name_map = $this->field_defs;

        return $this;
    }

    /**
     * Загружает языковые данные отчёта из папки `custom/lib/Modules/AOR_Reports/<reportName>/language` в соответствии с языковым пакетом пользователя и кеширует их для дальнейшего использования при выводе отчёта в UI.
     * @global \User $current_language
     * @return $this
     */
    protected function processLanguage()
    {
        global $current_language;
        $langFileTpl = 'custom/lib/Modules/AOR_Reports/<Report>/language/<lang>.lang.php';
        $lang = $current_language;
        if (!file_exists(strtr($langFileTpl, [
                '<Report>' => $this->name,
                '<lang>' => $lang,
            ]))
        ) {
            $lang = 'en_us';
        }

        $langFile = strtr($langFileTpl, [
            '<Report>' => $this->name,
            '<lang>' => $lang,
        ]);

        $cache_key = \LanguageManager::getLanguageCacheKey($this->module_dir, $lang);
        // Check for cached value
        $cache_entry = \sugar_cache_retrieve($cache_key);
        if (empty($cache_entry) || !is_array($cache_entry)) {
            require $langFile;
            $mod_strings = \sugarLangArrayMerge($GLOBALS['mod_strings'], $mod_strings);
            \sugar_cache_put($cache_key, $mod_strings);
        }

        return $this;
    }

    /**
     * Преобразует массив с данными в объект - единицу отчёта.
     * @param array $row Массив с данными БД
     * @param array $fieldFilter Массив с именами полей для фильтрации вывоода данных в массив
     * @return \SuiteCRM\Custom\Modules\AOR_Reports\ReportData
     */
    public function processRow($row, $fieldFilter = [])
    {
        $reportData = $this->getReportData();
        $reportData->parseRow($row);

        $this->afterParseRow($reportData);

        $this->dataList[] = $reportData->toArray($fieldFilter);
        $this->dataReports[] = $reportData;

        return $reportData;
    }

    /**
     * Вызывается функция во время преобразования массива с данными в объект - единицу отчёта, после того как был получен объект с данными, но перед сохранением данных в массив. Преднозначен для работы с данными в конечном отчёте.
     * @param \SuiteCRM\Custom\Modules\AOR_Reports\ReportData $reportData объект данных единицы отчёта
     */
    protected function afterParseRow($reportData)
    {
        //empty
    }

    /**
     * @return \SuiteCRM\Custom\Modules\AOR_Reports\ReportData
     */
    protected function getReportData()
    {
        $reportName = $this->id;

        $reportDataClass = '';
        if (file_exists("custom/lib/Modules/AOR_Reports/$reportName/ReportData.php")) {
            $reportDataClass = strtr('SuiteCRM\\Custom\\Modules\\AOR_Reports\\<report_name>\\ReportData', [
                '<report_name>' => $reportName,
            ]);
        }

        if (empty($reportDataClass)) {
            $reportDataClass = 'SuiteCRM\\Custom\\Modules\\AOR_Reports\\ReportData';
        }

        return new $reportDataClass($this->defs['fields']);
    }

    /**
     * Метод добавлен для совместимости  с `SugarBean`  в `SearchForm`
     * @return array
     */
    public function toArray($dbOnly = false, $stringOnly = false, $upperKeys = false)
    {
        $array = array_fill_keys(array_keys($this->field_defs), '');

        return $array;
    }
}
