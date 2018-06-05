{extends file="parent:frontend/checkout/ajax_cart_item.tpl"}


{block name="frontend_checkout_ajax_cart_articlename_price"}
    {if $sBasketItem['additional_details']['is_gadget'] neq 1}
    <span class="item--price">{if $basketItem.amount}{$basketItem.amount|currency}{else}{s name="AjaxCartInfoFree"}{/s}{/if}{s name="Star"}{/s}</span>
        {else}
        <span class="item--price">{s name="gift"}{/s}</span>
    {/if}
{/block}
{* Article actions *}
{block name='frontend_checkout_ajax_cart_actions'}
    {if $sBasketItem.additional_details.is_gadget<1 || $showRemoveGadget eq true}
    <div class="action--container">
        {$deleteUrl = {url controller="checkout" action="ajaxDeleteArticleCart" sDelete=$basketItem.id}}

        {if $basketItem.modus == 2}
            {$deleteUrl = {url controller="checkout" action="ajaxDeleteArticleCart" sDelete="voucher"}}
        {/if}

        {if $basketItem.modus != 4}
            <form action="{$deleteUrl}" method="post">
                <button type="submit" class="btn is--small action--remove" {if $sBasketItem.additional_details.is_gadget>0}data-type="gadget"{/if} title="{s name="AjaxCartRemoveArticle"}{/s}">
                    <i class="icon--cross"></i>
                </button>
            </form>
        {/if}
    </div>
    {/if}
{/block}