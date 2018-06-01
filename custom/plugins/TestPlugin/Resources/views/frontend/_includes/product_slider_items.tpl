{block name="frontend_common_product_slider_items"}
    {foreach $articles as $article}
       {if !$article['is_gadget']}
        {include file="frontend/_includes/product_slider_item.tpl"}
       {/if}
    {/foreach}
{/block}