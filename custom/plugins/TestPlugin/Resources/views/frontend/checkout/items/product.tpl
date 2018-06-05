{extends file='parent:frontend/checkout/items/product.tpl'}

{block name='frontend_checkout_cart_item_quantity_selection'}
    {if !$sBasketItem.additional_details.laststock || ($sBasketItem.additional_details.laststock && $sBasketItem.additional_details.instock > 0)}
        {if $sBasketItem.additional_details.is_gadget<1}
        <form name="basket_change_quantity{$sBasketItem.id}" class="select-field" method="post" action="{url action='changeQuantity' sTargetAction=$sTargetAction}">
            <select name="sQuantity" data-auto-submit="true">
                {section name="i" start=$sBasketItem.minpurchase loop=$sBasketItem.maxpurchase+1 step=$sBasketItem.purchasesteps}
                    <option value="{$smarty.section.i.index}" {if $smarty.section.i.index==$sBasketItem.quantity}selected="selected"{/if}>
                        {$smarty.section.i.index}
                    </option>
                {/section}
            </select>
            <input type="hidden" name="sArticle" value="{$sBasketItem.id}" />
        </form>
            {else}
            1
        {/if}

    {else}
        {s name="CartColumnQuantityEmpty" namespace="frontend/checkout/cart_item"}{/s}
    {/if}
{/block}

{* Product unit price *}
{block name='frontend_checkout_cart_item_price'}
    <div class="panel--td column--unit-price is--align-right">

        {if !$sBasketItem.modus}
            {block name='frontend_checkout_cart_item_unit_price_label'}
                <div class="column--label unit-price--label">
                    {s name="CartColumnPrice" namespace="frontend/checkout/cart_header"}{/s}
                </div>
            {/block}
        {if $sBasketItem.additional_details.is_gadget<1}
           {$sBasketItem.price|currency}{block name='frontend_checkout_cart_tax_symbol'}{s name="Star" namespace="frontend/listing/box_article"}{/s}{/block}
        {/if}
        {/if}
    </div>
{/block}

{* Accumulated product price *}
{block name='frontend_checkout_cart_item_total_sum'}
    <div class="panel--td column--total-price is--align-right">
        {block name='frontend_checkout_cart_item_total_price_label'}
            <div class="column--label total-price--label">
                {s name="CartColumnTotal" namespace="frontend/checkout/cart_header"}{/s}
            </div>
        {/block}
        {if $sBasketItem.additional_details.is_gadget<1}
        {$sBasketItem.amount|currency}{block name='frontend_checkout_cart_tax_symbol'}{s name="Star" namespace="frontend/listing/box_article"}{/s}{/block}
        {/if}
    </div>
{/block}

{* Remove product from basket *}

{block name='frontend_checkout_cart_item_delete_article'}
    {if $sBasketItem.additional_details.is_gadget<1 || $showRemoveGadget eq true}
        <div class="panel--td column--actions">
            <form {if $sBasketItem.additional_details.is_gadget>0}class="formremovegadget" data-type="gadget"{/if}action="{url action='deleteArticle' sDelete=$sBasketItem.id sTargetAction=$sTargetAction}"
                  method="post">
                <button type="submit" class="btn is--small column--actions-link"
                        title="{"{s name='CartItemLinkDelete'}{/s}"|escape}">
                    <i class="icon--cross"></i>
                </button>
            </form>
        </div>
    {/if}
{/block}
