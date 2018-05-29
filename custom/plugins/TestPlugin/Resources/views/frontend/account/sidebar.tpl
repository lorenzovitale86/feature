{namespace name="frontend/plugins/test_plugin/translation"}
{extends file="parent:frontend/account/sidebar.tpl"}

{block name="frontend_account_menu_link_profile"}
    {$smarty.block.parent}

{if $groupCustomer eq 'DE'}
    <li class="navigation--entry">
        <a href="{url controller='account' action=preferences}" title="{s name="entry_data"}{/s}"  class="navigation--link{if {controllerName} == 'Preference'} is--active{/if}">
{s name="entry_data"}{/s}
        </a>
    </li>
{/if}
{/block}