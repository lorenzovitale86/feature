{namespace name="frontend/plugins/test_plugin/translation"}
{extends file='parent:frontend/account/profile.tpl'}
{block name='frontend_index_left_categories_inner'}

    {$msg}
    {/block}

        {block name='frontend_index_content'}


    <div class="account--profile">
        <form name="createTeamForm" action="{url controller=preference action=createTeam}" method="post">
            <div class="panel has--border is--rounded">
                <div class="panel--title is--underline">{s name="add_team"}{/s}</div>
                <div class="panel--body is--wide">
                    <div class="profile--preference--team--nome">
                        <input name="teamName"
                               type="text"
                               required="required"
                               aria-required="true"
                               placeholder="{s name="team_name"}{/s}"
                               value="" class="profile--field is--required"
                        />

                    </div>

                    <div class="profile--preference--team--logo">
                        <input name="teamLogo"
                               type="text"
                               required="required"
                               aria-required="true"
                               placeholder="{s name="team_logo"}{/s}"
                               value="" class="profile--field is--required"
                        />

                    </div>
                </div>

                <div class="panel--actions is--wide">


                    <button class="btn is--block is--primary" type="submit" id="savePreference" data-preloader-button="true">
                        {s name="save_team"}{/s}
                    </button>
                    <span id="msg"></span>
                </div>
                </div>

        </form>


                <form name="createPlayerForm" action="{url controller=preference action=createPlayer}" method="post">
                    <div class="panel has--border is--rounded">
                        <div class="panel--title is--underline">{s name="add_player"}{/s}</div>
                        <div class="panel--body is--wide">
                            <div class="profile--preference--team--nome">
                                <input name="playerName"
                                       type="text"
                                       required="required"
                                       aria-required="true"
                                       placeholder="{s name="player_name"}{/s}"
                                       value="" class="profile--field is--required"
                                />

                            </div>

                            <div class="profile--preference--player--name">
                                <input name="playerSurname"
                                       type="text"
                                       required="required"
                                       aria-required="true"
                                       placeholder="{s name="player_surname"}{/s}"
                                       value="" class="profile--field is--required"
                                />

                            </div>

                            <div class="profile--preference--player--age">
                                <input name="playerAge"
                                       type="text"
                                       required="required"
                                       aria-required="true"
                                       placeholder="{s name="player_age"}{/s}"
                                       value="" class="profile--field is--required"
                                />

                            </div>
                        </div>

                        <div class="panel--body is--wide">
                            <div class="profile--salutation field--select select-field">
                                 <select name="playerTeam" data-quantity-error="true"
                                    required="required"
                                    aria-required="true"

                                  >

                                <option value="" disabled="disabled" selected="selected" >{s name="choose_team_label"}{/s}*</option>
                                {foreach $teams as $team}
                                    <option value="{$team->getId()}" >{$team->getName()}</option>

                                {/foreach}
                            </select>

                            </div>
                        </div>


                        <div class="panel--actions is--wide">


                            <button class="btn is--block is--primary" type="submit" id="savePreference" data-preloader-button="true">
                                {s name="save_player"}{/s}
                            </button>
                            <span id="msg"></span>
                        </div>
                    </div>

                </form>

            </div>

        {/block}