{literal}
    <style type="text/css">
        .view th {text-align:center !important;}
        .view td {text-align:right !important;}
        .list.view tr th {background-color: #534d64 !important;}
    </style>
{/literal}
<div class="moduleTitle">
    <h2><a href='index.php?module=AOR_Reports&action=index'>Отчёты</a> » {$report.title}</h2>
</div>
{include file="custom/modules/AOR_Reports/tpls/FilterByBEDate.tpl"}
{if sizeof($report.errors) > 0}
    {foreach from=$report.errors item=error}
        <div class="error">{$error}</div>
    {/foreach}
{/if}
{if sizeof($report.results) > 0}
    <table cellpadding="0" cellspacing="0" width="100%" border="0" class="list view">
        <tr>
            {foreach from=$report.headers item=header}
                <th>{$header}</th>
                {/foreach}
        </tr>
        {foreach from=$report.results item=result key=result_id name=results}
            <tr class="{if $smarty.foreach.results.iteration % 2 == 1}oddListRowS1{else}evenListRowS1{/if}">
                {foreach from=$report.columns item=column}
                    <td>{$result[$column]}</td>
                {/foreach}
            </tr>
        {/foreach}
    </table>
    <a href="{$report.export_url}"><input type="button" value="Экспорт"></a>
{/if}