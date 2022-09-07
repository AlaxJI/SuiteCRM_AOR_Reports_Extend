<?php

namespace SuiteCRM\Custom\Modules\AOR_Reports;

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

    public function toArray($filter = [])
    {
        $data = [];
        foreach ($this as $key => $value) {
            if (!empty($filter) && !in_array($key, $filter)) {
                continue;
            }
            $data[strtoupper($key)] = $value;
        }

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
