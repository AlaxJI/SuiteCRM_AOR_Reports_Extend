<div class="edit view search basic" style="overflow:hidden;">
    <form action="{$report.action_url}" style="margin:10px;">
        <input type="hidden" name="module" value="AOR_Reports"/>
        <input type="hidden" name="action" value="{$report.action}"/>
        <input type="hidden" name="record" value="{$report.record}"/>
        <div style="width:500px;float:left;">

            <div style="overflow:hidden;{if $report.form.totals}display:none;{/if}" id="begdate_wrap">
                <div style="width:200px;float:left;line-height:31px;" id="begdate_label">Дата начала периода:</div>
                <div style="float:left;">
                    <input type='text' name='beg_date' id="begdate_field" value="{$report.form.beg_date}" />
                    <img border="0" src="themes/SuiteP/images/jscalendar.gif" alt="Enter Date" id="begdate_trigger" align="absmiddle" />
                </div>
            </div>
            <div style="overflow:hidden;{if $report.form.totals}display:none;{/if}" id="enddate_wrap">
                <div style="width:200px;float:left;line-height:31px;" id="enddate_label">Дата окончания периода: </div>
                <div style="float:left;">
                    <input type='text' name='end_date' id="enddate_field" value="{$report.form.end_date}" />
                    <img border="0" src="themes/SuiteP/images/jscalendar.gif" alt="Enter Date" id="enddate_trigger" align="absmiddle" />
                </div>
            </div>
        </div>
        <div style="clear:both;">
            <input type="submit" value="Найти">
            <input type="submit" value="Очистить" onclick="SUGAR.searchForm.clear_form(this.form);
                    return false;">
        </div>
    </form>
</div>
<script type="text/javascript">
    {literal}
        Calendar.setup({
          inputField: "begdate_field",
          button: "begdate_trigger",
          singleClick: true,
          dateStr: "",
          step: 1,
          weekNumbers: false,
          daFormat: "{/literal}{$CALENDAR_FORMAT}{literal}",
          startWeekday: {/literal}{$CALENDAR_FDOW|default:'0'}{literal},
        }
        );

        Calendar.setup({
          inputField: "enddate_field",
          button: "enddate_trigger",
          singleClick: true,
          dateStr: "",
          step: 1,
          weekNumbers: false,
          daFormat: "{/literal}{$CALENDAR_FORMAT}{literal}",
          startWeekday: {/literal}{$CALENDAR_FDOW|default:'0'}{literal},
        }
        );
    {/literal}
</script>