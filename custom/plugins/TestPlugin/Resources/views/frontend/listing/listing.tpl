{block name="frontend_listing_list_inline"}
    {foreach $sArticles as $sArticle}
        {if !$sArticle['is_gadget']}
        {include file="frontend/listing/box_article.tpl"}
        {/if}
    {/foreach}
{/block}