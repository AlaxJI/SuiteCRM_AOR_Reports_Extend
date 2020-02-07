<?php

require_once('include/MVC/View/SugarView.php');

class AOR_ReportsViewCustom extends SugarView
{

    public function AOR_ReportsViewCustom()
    {
        parent::SugarView();
    }

    public function display()
    {
        $classname = $this->bean->id;
        if (file_exists("custom/modules/AOR_Reports/include/$classname.php")) {
            require_once "custom/modules/AOR_Reports/include/$classname.php";
        } elseif (file_exists("custom/modules/AOR_Reports/reports/$classname/$classname.php")) {
            require_once "custom/modules/AOR_Reports/reports/$classname/$classname.php";
        } else {
            $reportBean = new AOR_Report();
            $reportBean->mark_deleted($classname);
            SugarApplication::redirect("index.php?module=AOR_Reports&action=index");
        }

        /** @var ICustomReport $report*/
        $report = new $classname();
        $report->generate();

        $sugarSmarty = new Sugar_Smarty();

        $sugarSmarty->assign('CALENDAR_FORMAT', $GLOBALS['timedate']->get_cal_date_format());
        $sugarSmarty->assign('CALENDAR_FDOW', $GLOBALS['current_user']->get_first_day_of_week());
        $sugarSmarty->assign("report", (array) $report);

        echo $sugarSmarty->display($report->template);
    }
}
