{extends file='parent:frontend/account/profile.tpl'}

{block name="frontend_account_profile_profile_title"}
    <div class="panel--title is--underline">{s name="Preference"}{/s}</div>
{/block}

{block name='frontend_account_profile_profile_input_salutation'}
    <div class="profile--salutation field--select select-field">
        <select name="profile[team]"
                required="required"
                aria-required="true"
        >

            <option value="" disabled="disabled"{if $favoriteTeam eq ""} selected="selected"{/if}>Seleziona Squadra Favorita</option>
            {foreach $teams as $team}
                <option value="{$team.id}" {if $team.id eq $idfavoriteTeam} selected="selected"{/if}>{$team.name}</option>

            {/foreach}
        </select>


    </div>
{/block}
