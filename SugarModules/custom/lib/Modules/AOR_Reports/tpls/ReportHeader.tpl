<!--script type="text/javascript" src="{sugar_getjspath file='include/javascript/sugar_3.js'}"></script -->
<!--script type="text/javascript" src="{sugar_getjspath file='include/javascript/popup_helper.js'}"></script -->
<script type="text/javascript" src="modules/Favorites/favorites.js"></script>
{sugar_include include=$includes}
<script type="text/javascript">
    {$ASSOCIATED_JAVASCRIPT_DATA}

{literal}
function clearAll() {
   for(i=0; i < document.report_query_form.length; i++) {
       if(/select/i.test(document.report_query_form.elements[i].type)) {
          for(x=0; x < document.report_query_form.elements[i].options.length; x++) {
             document.report_query_form.elements[i].options[x].removeAttribute('selected');
          }
       }
   }
}
{/literal}
</script>
{$SEARCH_FORM_HEADER}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="edit view">
<tr>
<td>
<form action="index.php" method="post" name="report_query_form" id="report_query_form">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td>
{$searchForm}
</td></tr>
<tr>
<td>
<input type="hidden" name="module" value="AOR_Reports" />
<input type="hidden" name="action" value="DetailView" />
<input type="hidden" name="query" value="true" />
<input type="hidden" name="func_name" value="" />
<input type="hidden" name="request_data" value="{$request_data}" />
<input type="hidden" name="populate_parent" value="false" />
<input type="hidden" name="hide_clear_button" value="true" />
<input type="hidden" name="record" value="{$smarty.request.record}" />
<input type="hidden" name="acl_access" value="{$smarty.request.acl_access}" />
{$MODE}
<input type="submit" name="button" class="button" id="search_form_submit"
    title="{$APP.LBL_SEARCH_BUTTON_TITLE}"
    value="{$APP.LBL_SEARCH_BUTTON_LABEL}" />
<input type="reset" onclick="SUGAR.searchForm.clear_form(this.form); return false;" class="button" id="search_form_clear"
    title="{$APP.LBL_CLEAR_BUTTON_TITLE}"
    value="{$APP.LBL_CLEAR_BUTTON_LABEL}"/>
</td>
<td align='right'></td>
</tr>
</table>
</form>
</td>
</tr>
</table>
{{if isset($ADDFORM)}}
<p>
{{if isset($popupMeta)}}
<div id='addformlink'>
<input type="button" name="showAdd" class="button" value="{$popupMeta.create.createButton}" onclick="toggleDisplay('addform');" />
</div>
{{/if}}
<div id='addform' style='display:none;position:relative;z-index:2;left:0px;top:0px;'>
<form name="form_QuickCreate_{$module}" id="form_QuickCreate_{$module}" {*onsubmit="return check_form('form_popupQuickCreate{$module}');"*} method="post" action="index.php">
{$ADDFORMHEADER}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="edit view">
<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td>
<input type="hidden" name="doAction" value="save" />
<input type="hidden" name="query" value="true" />
{$ADDFORM}
</td></tr>
</table></td></tr></table>
</form>
</div>
</p>
{{/if}}
{{if $prerow}}
    <form action="index.php" method="post" name="MassUpdate" id="MassUpdate">
    {$MODE}
<input type="hidden" name="mu" value="false" />
<input type='hidden' name='massupdate' value='true' />
{$massUpdateData}
<input type='hidden' name='Leads_LEAD_offset' value=''><input type='hidden' name='saved_associated_data' value=''><input type='hidden' name='module' value='{$module}'><input type='hidden' name='action' value='Popup'><input type='hidden' name='return_module' value='{$module}'><input type='hidden' name='return_action' value='Popup'><input type='hidden' name='hide_clear_button' value='true'><input type='hidden' name='current_query_by_page' value='{$current_query}'>

    {$multiSelectData}
    <input class="button" type="button" id="MassUpdate_select_button" value='{$APP.LBL_SELECT_BUTTON_LABEL}' onclick="send_back_selected('{$module}',document.MassUpdate,'mass[]','{$APP.ERR_NOTHING_SELECTED}');">
{{/if}}
