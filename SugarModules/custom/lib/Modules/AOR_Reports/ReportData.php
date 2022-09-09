<?php

namespace SuiteCRM\Custom\Modules\AOR_Reports;

use SuiteCRM\Custom\Utility\TableCssGenerator;

/**
 * Класс единицы отчёта.
 *
 * @author Алексей Дубровский <alaxji@gmail.com>
 */
class ReportData
{
    public $field_defs;

    public function __construct($fieldDefs)
    {
        // NOTE: Initializing an object fields
        $this->field_defs = $fieldDefs;
        foreach ($fieldDefs as $key => $fieldDef) {
            $this->$key = null;
        }
    }

    public function parseRow($row)
    {
        foreach ($this as $key => $value) {
            if (!isset($row[$key])) {
                continue;
            }
            $fieldData = $row[$key];

            $type = isset($this->field_defs[$key]['custom_type']) ? $this->field_defs[$key]['custom_type'] : $this->field_defs[$key]['type'];

            if ($type === 'datetime' || $type === 'datetimecombo') {
                $fieldData = $this->convertDateTime($fieldData, true, true);
            } elseif ($type === 'date') {
                $fieldData = $this->convertDate($fieldData, true);
            }
            $this->$key = $fieldData;
        }
    }

    public function colorizedColumns($listViewDefs)
    {
        foreach ($listViewDefs as $fieldname => $listViewDef) {
            if ($fieldname === 'colorize' || (empty($listViewDef['colorize']) && empty($listViewDef['conditions']))) {
                continue;
            }

            // TODO: Убрать false и добавать обработку строки, когда появится возможность раскрашивать по условию
            if (false && !empty($listViewDef['conditions'])) {
                // colorize by conditions
                // not implemented
            } elseif (!empty($listViewDef['colorize'])) {
                // default colorize
                $cssGenerator = new TableCssGenerator($listViewDef['colorize'], TableCssGenerator::TYPE_COLUMN, $fieldname);
                $this->field_dom_classes[$fieldname] = ($cssGenerator->getStyleArray())['class'];
            }
        }

        return $this;
    }

    public function toArray($filter = [])
    {
        $data = [];
        foreach ($this as $key => $value) {
            if (!empty($filter) && !in_array($key, $filter)) {
                continue;
            }
            $data[strtoupper($key)] = $value;
        }

        $data['columnClasses'] = $this->field_dom_classes;

        return $data;
    }

    protected function convertDateTime($date, $meridiem = true, $convert_tz = true, $user = null)
    {
        $timedate = \TimeDate::getInstance();

        return $timedate->to_display_date_time($date, $meridiem, $convert_tz);
    }

    protected function convertDate($date, $convert_tz = true)
    {
        $timedate = \TimeDate::getInstance();

        return $timedate->to_display_date($date, $convert_tz);
    }
}
