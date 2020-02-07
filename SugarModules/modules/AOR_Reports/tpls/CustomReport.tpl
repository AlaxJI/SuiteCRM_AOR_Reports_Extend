{if !empty($tpl.errors)}
    <div>
        {foreach from=$tpl.errors item=error}
            <div class="error">{$error}</div>
        {/foreach}
    </div>
{/if}