{namespace name="frontend/plugins/test_plugin/translation"}
{extends file="parent:frontend/listing/product-box/product-badges.tpl"}

{block name="frontend_listing_box_article_badges"}

    <div class="product--badges">

        {* Discount badge *}
        {block name='frontend_listing_box_article_discount'}
            {if $sArticle.has_pseudoprice }
                <div class="product--badge badge--discount">
                    <i class="icon--percent2"></i>
                </div>
            {/if}
        {/block}

        {* Highlight badge *}
        {block name='frontend_listing_box_article_hint'}
            {if $sArticle.highlight}
                <div class="product--badge badge--recommend">
                    {s name="listing_box_tip"}{/s}
                </div>
            {/if}
        {/block}

        {* Newcomer badge *}
        {block name='frontend_listing_box_article_new'}
            {if $sArticle.newArticle}
                <div class="product--badge badge--newcomer">
                    {s name="listing_box_new"}{/s}
                </div>
            {/if}
        {/block}

        {* ESD product badge *}
        {block name='frontend_listing_box_article_esd'}
            {if $sArticle.esd}
                <div class="product--badge badge--esd">
                    <i class="icon--download"></i>
                </div>
            {/if}
        {/block}

        {* PRODUCT WITH FAVORITE TEAM OF CUSTOMER*}
          {if ($sArticle.team === $testUserData.additional.user.team) &&($testUserData.additional.user.team!=null)}
            <div class="product--badge badge--discount">
               {s name="listing_box_bonus"}{/s}

            </div>
        {/if}
    </div>
{/block}