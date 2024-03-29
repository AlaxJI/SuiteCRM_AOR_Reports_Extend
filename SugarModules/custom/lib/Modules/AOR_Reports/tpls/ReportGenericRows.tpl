{if $styles}
    <style>
    {literal}
        .defaultListRowS1:hover td {
            background: #FAF7CF;
          }
        .toggle-label {
            width: 100%;
            text-align: center;
        }
        .toggle-checkbox {
          display: none !important;
        }
        .toggle-minus{
            display: none;
        }
        .toggle-checkbox:checked + .toggle-label .toggle-minus {
            display: inline;
        }
        .toggle-checkbox:checked + .toggle-label .toggle-plus {
            display: none;
        }
    {/literal}
    {foreach from=$styles key=selectorName item=styleData}
        {$selectorName} {ldelim}
            {$styleData}
        {rdelim}
    {/foreach}
    </style>
{/if}

{* support rows data *}
<div class="popupBody">
{assign var="alt_start" value=$navStrings.start}
{assign var="alt_next" value=$navStrings.next}
{assign var="alt_prev" value=$navStrings.previous}
{assign var="alt_end" value=$navStrings.end}

{{include file=$headerTpl}}
{$jsLang}
{$LIST_HEADER}
{if $should_process}
    <table cellpadding='0' cellspacing='0' width='100%' border='0' class='list view'>
        <tr class='pagination'  role='presentation'>
            <td colspan='{$fieldCount+1}' align='right'>
                <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                    <tr>
                        <td align='left' {if $prerow}class="selectCol">{sugar_action_menu id=$link_select_id params=$selectLink}{else}>&nbsp;{/if}</td>
                        <td  align='right' nowrap='nowrap'>
                            {if $pageData.urls.startPage}
                                <button type='button' id='popupViewStartButton' title='{$navStrings.start}' class='button' {if $prerow}onclick='return sListView.save_checks(0, "{$moduleString}");'{else} onClick='location.href="{$pageData.urls.startPage}"' {/if}>
                                    {sugar_getimage name="start" ext=".png" other_attributes='align="absmiddle" border="0" ' alt="$alt_start"}
                                </button>
                                <!--<a href='{$pageData.urls.startPage}' {if $prerow}onclick="return sListView.save_checks(0, '{$moduleString}')"{/if} >{sugar_getimage name="start" ext=".png" alt=$navStrings.start other_attributes='align="absmiddle" border="0" '}&nbsp;{$navStrings.start}</a>&nbsp;-->
                            {else}
                                <button type='button' id='popupViewStartButton' title='{$navStrings.start}' class='button' disabled>
                                    {sugar_getimage name="start_off" ext=".png" alt=$navStrings.start other_attributes='align="absmiddle" border="0" '}
                                </button>
                                <!--{sugar_getimage name="start_off" ext=".png" alt=$navStrings.start other_attributes='align="absmiddle" border="0" '}&nbsp;{$navStrings.start}&nbsp;&nbsp;-->
                            {/if}
                            {if $pageData.urls.prevPage}
                                <button type='button' id='popupViewPrevButton' title='{$navStrings.previous}' class='button' {if $prerow}onclick='return sListView.save_checks({$pageData.offsets.prev}, "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.prevPage}"'{/if}>
                                    {sugar_getimage name="previous" ext=".png" other_attributes='align="absmiddle" border="0" ' alt="$alt_prev"}
                                </button>
                                <!--<a href='{$pageData.urls.prevPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.prev}, '{$moduleString}')"{/if} >{sugar_getimage name="previous" ext=".png" alt=$navStrings.previous other_attributes='align="absmiddle" border="0" '}&nbsp;{$navStrings.previous}</a>&nbsp;-->
                            {else}
                                <button type='button' id='popupViewPrevButton' class='button' disabled title='{$navStrings.previous}'>
                                    {sugar_getimage name="previous_off" ext=".png" alt=$navStrings.previous other_attributes='align="absmiddle" border="0" '}
                                </button>
                                <!--{sugar_getimage name="previous_off" ext=".png" alt=$navStrings.previous other_attributes='align="absmiddle" border="0" '}&nbsp;{$navStrings.previous}&nbsp;-->
                            {/if}
                                <span class='pageNumbers'>({if $pageData.offsets.lastOffsetOnPage == 0}0{else}{$pageData.offsets.current+1}{/if} - {$pageData.offsets.lastOffsetOnPage} {$navStrings.of} {if $pageData.offsets.totalCounted}{$pageData.offsets.total}{else}{$pageData.offsets.total}{if $pageData.offsets.lastOffsetOnPage != $pageData.offsets.total}+{/if}{/if})</span>
                            {if $pageData.urls.nextPage}
                                <button type='button' id='popupViewNextButton' title='{$navStrings.next}' class='button' {if $prerow}onclick='return sListView.save_checks({$pageData.offsets.next}, "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.nextPage}"'{/if}>
                                    {sugar_getimage name="next" ext=".png" other_attributes='align="absmiddle" border="0" ' alt="$alt_next"}
                                </button>
                                <!--&nbsp;<a href='{$pageData.urls.nextPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.next}, '{$moduleString}')"{/if} >{$navStrings.next}&nbsp;{sugar_getimage name="next" ext=".png" alt=$navStrings.next other_attributes='align="absmiddle" border="0" '}</a>&nbsp;-->
                            {else}
                                <button type='button' id='popupViewNextButton' class='button' title='{$navStrings.next}' disabled>
                                    {sugar_getimage name="next_off" ext=".png" alt=$navStrings.next other_attributes='align="absmiddle" border="0" '}
                                </button>
                                <!--&nbsp;{$navStrings.next}&nbsp;{sugar_getimage name="next_off" ext=".png" alt=$navStrings.next other_attributes='align="absmiddle" border="0" '}-->
                            {/if}
                            {if $pageData.urls.endPage  && $pageData.offsets.total != $pageData.offsets.lastOffsetOnPage}
                                <button type='button' id='popupViewEndButton' title='{$navStrings.end}' class='button' {if $prerow}onclick='return sListView.save_checks("end", "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.endPage}"'{/if}>
                                    {sugar_getimage name="end" ext=".png" other_attributes='align="absmiddle" border="0" ' alt="$alt_end"}
                                </button>
                                <!--<a href='{$pageData.urls.endPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.end}, '{$moduleString}')"{/if} >{$navStrings.end}&nbsp;{sugar_getimage name="end" ext=".png" alt=$navStrings.end other_attributes='align="absmiddle" border="0" '}</a></td>-->
                            {elseif !$pageData.offsets.totalCounted || $pageData.offsets.total == $pageData.offsets.lastOffsetOnPage}
                                <button type='button' id='popupViewEndButton' class='button' disabled title='{$navStrings.end}'>
                                     {sugar_getimage name="end_off" ext=".png" alt=$navStrings.end other_attributes='align="absmiddle" border="0" '}
                                </button>
                                <!--&nbsp;{$navStrings.end}&nbsp;{sugar_getimage name="end_off" ext=".png" alt=$navStrings.end other_attributes='align="absmiddle" border="0" '}-->
                            {/if}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <thead>
        <tr height='20'>
            {if $prerow}
                <td nowrap="nowrap" width="43px" class="selectCol td_alt">&nbsp;</td>
                <td class='td_alt' nowrap="nowrap" width='1%'>&nbsp;</td>
            {/if}
            {counter start=0 name="colCounter" print=false assign="colCounter"}
            {foreach from=$displayFields key=colHeader item=params}
                {if $colCounter == '0'}<th scope='col' width='{$params.width}%' data-toggle="true">
                {else}<th scope='col' width='{$params.width}%' data-hide="phone,tablet">{/if}
                    <div style='white-space: normal; width:100%; text-align:{$params.align|default:'left'}'>
                    {if $params.sortable|default:false}
                                <a href="{$pageData.urls.orderBy}{$params.orderBy|default:$colHeader|lower}" onclick='sListView.save_checks(0, "{$moduleString}");' class='listViewThLinkS1'>{sugar_translate label=$params.label module=$pageData.bean.moduleDir}&nbsp;&nbsp;
                        {if $params.orderBy|default:$colHeader|lower == $pageData.ordering.orderBy}
                            {if $pageData.ordering.sortOrder == 'ASC'}
                                {capture assign="arrowAlt"}{sugar_translate label='LBL_ALT_SORT_DESC'}{/capture}
                                {capture assign="imageName"}arrow_down.{$arrowExt}{/capture}
                                {sugar_getimage name="$imageName" ext='gif' width="$arrowWidth" height="$arrowHeight" alt="$arrowAlt" other_attributes='align="absmiddle" border="0"'}
                            {else}
                                {capture assign="arrowAlt"}{sugar_translate label='LBL_ALT_SORT_ASC'}{/capture}
                                {capture assign="imageName"}arrow_up.{$arrowExt}{/capture}
                                {sugar_getimage name="$imageName" ext='gif'  width="$arrowWidth" height="$arrowHeight" alt="$arrowAlt" other_attributes='align="absmiddle" border="0"'}
                            {/if}
                        {else}
                            {capture assign="arrowAlt"}{sugar_translate label='LBL_ALT_SORT'}{/capture}
                            {capture assign="imageName"}arrow.{$arrowExt}{/capture}
                            {sugar_getimage name="$imageName" ext='gif' width="$arrowWidth" height="$arrowHeight" alt="$arrowAlt" other_attributes='align="absmiddle" border="0"'}
                        {/if}
                    {else}
                        {sugar_translate label=$params.label module=$pageData.bean.moduleDir}
                    {/if}
                    </div>
                </th>
                {counter name="colCounter"}
            {/foreach}
            {if !empty($quickViewLinks)}
            <td class='td_alt' nowrap="nowrap" width='1%'>&nbsp;</td>
            {/if}
        </tr>
        </thead>
        {foreach name=rowIteration from=$data key=id item=rowData}
            {if $smarty.foreach.rowIteration.iteration is odd}
                {assign var='_rowColor' value=$rowColor[0]}
            {else}
                {assign var='_rowColor' value=$rowColor[1]}
            {/if}
            <tr height='20' class='{$_rowColor}S1'>
                {if $prerow}
                <td width='1%' nowrap='nowrap'>
                        <input onclick='sListView.check_item(this, document.MassUpdate)' type='checkbox' class='checkbox' name='mass[]' value='{$rowData.ID}'>
                </td>
                <td width='1%' nowrap='nowrap'>
                        {$pageData.additionalDetails.$id}
                </td>
                {/if}
                {counter start=0 name="colCounter" print=false assign="colCounter"}
                {foreach from=$displayFields key=col item=params}
                    {if $rowData.columnClasses && $rowData.columnClasses.$col}
                        {assign var='_columnColor' value=$rowData.columnClasses.$col}
                    {else}
                        {assign var='_columnColor' value=''}
                    {/if}
                    <td scope='row' align='{$params.align|default:'left'}' valign=top class='{$_columnColor}' fieldname="{$col}">
                        {if $params.link && !$params.customCode}

                            <{$pageData.tag.$id[$params.ACLTag]|default:$pageData.tag.$id.MAIN} href='javascript:void(0)' onclick="send_back('{if $params.dynamic_module}{$rowData[$params.dynamic_module]}{else}{$params.module|default:$pageData.bean.moduleDir}{/if}','{$rowData[$params.id]|default:$rowData.ID}');">{$rowData.$col}</{$pageData.tag.$id[$params.ACLTag]|default:$pageData.tag.$id.MAIN}>

                        {elseif $params.customCode}
                            {sugar_evalcolumn_old var=$params.customCode rowData=$rowData}
                        {elseif $params.currency_format}
                            {sugar_currency_format
                                var=$rowData.$col
                                round=$params.currency_format.round
                                decimals=$params.currency_format.decimals
                                symbol=$params.currency_format.symbol
                                convert=$params.currency_format.convert
                                currency_symbol=$params.currency_format.currency_symbol
                            }
                        {elseif $params.type == 'bool'}
                                <input type='checkbox' disabled=disabled class='checkbox'
                                {if !empty($rowData[$col])}
                                    checked=checked
                                {/if}
                                />
                        {elseif $params.type == 'multienum' }
                                {counter name="oCount" assign="oCount" start=0}
                                {multienum_to_array string=$rowData.$col assign="vals"}
                                {foreach from=$vals item=item}
                                    {counter name="oCount"}
                                    {sugar_translate label=$params.options select=$item}{if $oCount !=  count($vals)},{/if}
                                {/foreach}
                        {else}
                            {sugar_field parentFieldArray=$rowData vardef=$params displayType=ListView field=$col}
                        {/if}
                    </td>
                    {counter name="colCounter"}
                {/foreach}

        {/foreach}
        <tr class='pagination' role='presentation'>
            <td colspan='{$fieldCount+1}' align='right'>
                <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                    <tr>
                        <td align='left' >
                            &nbsp;</td>
                        <td  align='right' nowrap='nowrap'>
                            {if $pageData.urls.startPage}
                                <button type='button' title='{$navStrings.start}' class='button' {if $prerow}onclick='return sListView.save_checks(0, "{$moduleString}");'{else} onClick='location.href="{$pageData.urls.startPage}"' {/if}>
                                    {sugar_getimage name="start" ext=".png" other_attributes='align="absmiddle" border="0" ' alt="$alt_start"}
                                </button>
                                <!--<a href='{$pageData.urls.startPage}' {if $prerow}onclick="return sListView.save_checks(0, '{$moduleString}')"{/if} >{sugar_getimage name="start" ext=".png" alt=$navStrings.start other_attributes='align="absmiddle" border="0" '}&nbsp;{$navStrings.start}</a>&nbsp;-->
                            {else}
                                <button type='button' title='{$navStrings.start}' class='button' disabled>
                                    {sugar_getimage name="start_off" ext=".png" alt=$navStrings.start other_attributes='align="absmiddle" border="0" '}
                                </button>
                                <!--{sugar_getimage name="start_off" ext=".png" alt=$navStrings.start other_attributes='align="absmiddle" border="0" '}&nbsp;{$navStrings.start}&nbsp;&nbsp;-->
                            {/if}
                            {if $pageData.urls.prevPage}
                                <button type='button' title='{$navStrings.previous}' class='button' {if $prerow}onclick='return sListView.save_checks({$pageData.offsets.prev}, "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.prevPage}"'{/if}>
                                    {sugar_getimage name="previous" ext=".png" other_attributes='align="absmiddle" border="0" ' alt="$alt_prev"}
                                </button>
                                <!--<a href='{$pageData.urls.prevPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.prev}, '{$moduleString}')"{/if} >{sugar_getimage name="previous" ext=".png" alt=$navStrings.previous other_attributes='align="absmiddle" border="0" '}&nbsp;{$navStrings.previous}</a>&nbsp;-->
                            {else}
                                <button type='button' class='button' disabled title='{$navStrings.previous}'>
                                    {sugar_getimage name="previous_off" ext=".png" alt=$navStrings.previous other_attributes='align="absmiddle" border="0" '}
                                </button>
                                <!--{sugar_getimage name="previous_off" ext=".png" alt=$navStrings.previous other_attributes='align="absmiddle" border="0" '}&nbsp;{$navStrings.previous}&nbsp;-->
                            {/if}
                                <span class='pageNumbers'>({if $pageData.offsets.lastOffsetOnPage == 0}0{else}{$pageData.offsets.current+1}{/if} - {$pageData.offsets.lastOffsetOnPage} {$navStrings.of} {if $pageData.offsets.totalCounted}{$pageData.offsets.total}{else}{$pageData.offsets.total}{if $pageData.offsets.lastOffsetOnPage != $pageData.offsets.total}+{/if}{/if})</span>
                            {if $pageData.urls.nextPage}
                                <button type='button' title='{$navStrings.next}' class='button' {if $prerow}onclick='return sListView.save_checks({$pageData.offsets.next}, "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.nextPage}"'{/if}>
                                    {sugar_getimage name="next" ext=".png" other_attributes='align="absmiddle" border="0" ' alt="$alt_next"}
                                </button>
                                <!--&nbsp;<a href='{$pageData.urls.nextPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.next}, '{$moduleString}')"{/if} >{$navStrings.next}&nbsp;{sugar_getimage name="next" ext=".png" alt=$navStrings.next other_attributes='align="absmiddle" border="0" '}</a>&nbsp;-->
                            {else}
                                <button type='button' class='button' title='{$navStrings.next}' disabled>
                                    {sugar_getimage name="next_off" ext=".png" alt=$navStrings.next other_attributes='align="absmiddle" border="0" '}
                                </button>
                                <!--&nbsp;{$navStrings.next}&nbsp;{sugar_getimage name="next_off" ext=".png" alt=$navStrings.next other_attributes='align="absmiddle" border="0" '}-->
                            {/if}
                            {if $pageData.urls.endPage  && $pageData.offsets.total != $pageData.offsets.lastOffsetOnPage}
                                <button type='button' title='{$navStrings.end}' class='button' {if $prerow}onclick='return sListView.save_checks({$pageData.offsets.end}, "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.endPage}"'{/if}>
                                    {sugar_getimage name="end" ext=".png" other_attributes='align="absmiddle" border="0" ' alt="$alt_end"}
                                </button>
                                <!--<a href='{$pageData.urls.endPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.end}, '{$moduleString}')"{/if} >{$navStrings.end}&nbsp;{sugar_getimage name="end" ext=".png" alt=$navStrings.end other_attributes='align="absmiddle" border="0" '}</a></td>-->
                            {elseif !$pageData.offsets.totalCounted || $pageData.offsets.total == $pageData.offsets.lastOffsetOnPage}
                                <button type='button' class='button' disabled title='{$navStrings.end}'>
                                     {sugar_getimage name="end_off" ext=".png" alt=$navStrings.end other_attributes='align="absmiddle" border="0" '}
                                </button>
                                <!--&nbsp;{$navStrings.end}&nbsp;{sugar_getimage name="end_off" ext=".png" alt=$navStrings.end other_attributes='align="absmiddle" border="0" '}-->
                            {/if}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    {if $prerow}
    <script>
    {literal}function lvg_dtails(id){return SUGAR.util.getAdditionalDetails( '{/literal}{$module}{literal}',id, 'adspan_'+id);}{/literal}
    </script>
    {/if}
{else}
    <h4>{$APP.LBL_SEARCH_POPULATE_ONLY}</h4>
{/if}
{{include file=$footerTpl}}
</div>