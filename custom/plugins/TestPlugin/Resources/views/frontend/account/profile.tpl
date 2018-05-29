{namespace name="frontend/plugins/test_plugin/translation"}
{extends file='parent:frontend/account/profile.tpl'}


{block name="frontend_account_profile_profile_form"}

{$smarty.block.parent}

    <form name="preferenceForm" id="preferenceForm" action="{url controller=test action=savePreference}" method="post">
        <div class="panel has--border is--rounded">
            <div class="panel--title is--underline">{s name="preferences"}{/s}</div>
            <div class="panel--body is--wide">
        <div class="profile--salutation field--select select-field">
            <select name="profile[team]" data-quantity-error="true"
                    required="required"
                    aria-required="true"
                    id="selteam"
            >

                    <option value="" disabled="disabled"{if $testUserData.additional.user.team eq ""} selected="selected"{/if}>{s name="choose_favorite_team"}{/s}*</option>
                {foreach $teams as $team}
                    <option value="{$team.id}" {if $team.id eq $testUserData.additional.user.team} selected="selected"{/if}>{$team.name}</option>
                {/foreach}
            </select>
        </div>
        <div class="profile--salutation field--select select-field">
            <select name="profile[player]"
                    id="selplayer"
            >
                    <option value="" disabled="disabled"{if $idfavoritePlayer eq ""} selected="selected"{/if}>{s name="choose_favorite_player"}{/s}*</option>
                  {if $testUserData.additional.user.team neq '' }
                    {foreach $players as $player}
                     <option value="{$player.id}" {if $player.id eq $testUserData.additional.user.player} selected="selected"{/if}>{$player.name} {$player.surname}</option>
                    {/foreach}
                  {/if}
            </select>
        </div>
        </div>
            <div class="panel--actions is--wide">
                <button class="btn is--block is--primary" type="submit" id="savePreverence" data-preloader-button="true">
                   Ajax  {s name="save_preference"}{/s}
                </button>
                <span id="msg"></span>
            </div>
    </div>
    </form>
{/block}
{block name="frontend_index_javascript_async_ready"}
    {$smarty.block.parent}
    <script>

        let testFileOptions = {
            playerUrl:"/shopware/test/teamPlayers/",
            preferenceUrl:"/shopware/test/ajaxSavePreference",
            loadingLabel:"{s name='loading_label'}{/s}",
            favoritePlayer:"{s name='favorite_player'}{/s}",
            updateOk:"{s name='update_ok'}{/s}",
            updateError:"{s name='update_error'}{/s}"
        };

        document.asyncReady(function () {
            StateManager.addPlugin('form[name="preferenceForm"]', 'testfile',testFileOptions);
        });
    </script>
{/block}