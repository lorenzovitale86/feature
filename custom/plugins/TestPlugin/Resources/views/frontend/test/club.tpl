{foreach name=outer item=team from=$teams}
    {foreach key=key item=item from=$team}
        {$key}: {$item}<br>
    {/foreach}
{/foreach}

<br>
ddd
{extends file='parent:frontend/account/profile.tpl'}
