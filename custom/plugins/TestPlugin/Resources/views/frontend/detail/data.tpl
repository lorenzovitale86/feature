{namespace name="frontend/plugins/test_plugin/translation"}
{extends file="parent:frontend/detail/data.tpl"}

{block name='frontend_detail_data_tax' prepend}
    <p>
      {if $sArticle.team === $testUserData.additional.user.team}

          <i class="icon--star"></i>  {s name="discount"}{/s}
        {/if}
    </p>
{/block}