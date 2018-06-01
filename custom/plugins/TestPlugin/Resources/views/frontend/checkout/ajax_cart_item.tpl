{extends file="parent:frontend/checkout/ajax_cart_item.tpl"}


{block name="frontend_checkout_ajax_cart_articlename_price"}
    {if $sBasketItem['additional_details']['is_gadget'] neq 1}
    <span class="item--price">{if $basketItem.amount}{$basketItem.amount|currency}{else}{s name="AjaxCartInfoFree"}{/s}{/if}{s name="Star"}{/s}</span>
        {else}
        <span class="item--price">{s name="gift"}{/s}</span>
    {/if}
{/block}
