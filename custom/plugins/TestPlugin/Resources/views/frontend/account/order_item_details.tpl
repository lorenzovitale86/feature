{extends file='parent:frontend/account/order_item_details.tpl'}
{block name="frontend_account_order_item_detail_table_rows"}
    {foreach $offerPosition.details as $article}

        {block name="frontend_account_order_item_detail_table_row"}
            <div class="panel--tr">

                {block name="frontend_account_order_item_info"}
                    <div class="panel--td order--info column--name">

                        {* Name *}
                        {block name="frontend_account_order_item_name"}
                            <p class="order--name is--strong">
                                {$article.name}
                            </p>
                        {/block}

                        {* Unit price *}
                        {block name='frontend_account_order_item_unitprice'}
                            {if $article.article.is_gadget<1}
                                {if $article.purchaseunit}
                                    <div class="order--price-unit">
                                        {block name='frontend_account_order_item_purchaseunit'}
                                            <p>{s name="OrderItemInfoContent"}{/s}:{$article.purchaseunit} {$article.sUnit.description}</p>
                                        {/block}

                                        {block name="frontend_account_order_item_referenceunit"}
                                            {if $article.purchaseunit != $article.referenceunit}
                                                <p>
                                                    {if $article.referenceunit}
                                                        <span class="order--base-price">{s name="OrderItemInfoBaseprice"}{/s}:</span>
                                                        {$article.referenceunit} {$article.sUnit.description} = {$article.referenceprice|currency}
                                                        {s name="Star" namespace="frontend/listing/box_article"}{/s}
                                                    {/if}
                                                </p>
                                            {/if}
                                        {/block}
                                    </div>
                                {/if}
                            {/if}
                        {/block}

                        {* Current price *}
                        {block name='frontend_account_order_item_currentprice'}
                            {if $article.article.is_gadget<1}
                            {if $article.currentPrice}
                                <div class="order--current-price">
                                    {block name="frontend_account_order_item_currentprice_label"}
                                        <span>{s name="OrderItemInfoCurrentPrice"}{/s}:</span>
                                    {/block}

                                    {block name="frontend_account_order_item_currentprice_value"}
                                        <span>
                                                {$article.currentPrice|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
                                            </span>
                                    {/block}

                                    {block name="frontend_account_order_item_pseudo_price"}
                                        {if $article.currentHas_pseudoprice}
                                            <span class="price--pseudo">
                                                    {block name="frontend_account_order_item_pseudo_price_before"}
                                                        {s name="priceDiscountLabel" namespace="frontend/detail/data"}{/s}
                                                    {/block}

                                                <span class="order--pseudo-price is--italic is--soft is--line-through">
                                                        {$article.currentPseudoprice|currency}
                                                    {s name="Star" namespace="frontend/listing/box_article"}{/s}
                                                    </span>

                                                {block name="frontend_account_order_item_pseudo_price_after"}
                                                    {s name="priceDiscountInfo" namespace="frontend/detail/data"}{/s}
                                                {/block}
                                                </span>
                                        {/if}
                                    {/block}
                                </div>
                            {/if}
                            {/if}
                        {/block}

                        {* availability warning*}
                        {block name='frontend_account_order_item_availability'}
                            {if $article.modus == 0 && ($article.active == 0 || !$article.article.isAvailable)}
                                {* show warning if article is not active or not available *}
                                {include file="frontend/_includes/messages.tpl" type="error" content="{s name='OrderItemInfoNotAvailable'}{/s}"}
                            {/if}
                        {/block}

                        {* If ESD-Article *}
                        {block name='frontend_account_order_item_downloadlink'}
                            {if $article.esdarticle && $offerPosition.cleared|in_array:$sDownloadAvailablePaymentStatus}
                                <div class="order--download is--strong">
                                    <a href="{$article.esdLink}" class="btn is--small">
                                        {s name="OrderItemInfoInstantDownload"}{/s}
                                    </a>
                                </div>
                            {/if}
                        {/block}
                    </div>
                {/block}

                {* Order item quantity *}
                {block name='frontend_account_order_item_quantity'}
                    <div class="panel--td order--quantity column--quantity">

                        {block name='frontend_account_order_item_quantity_label'}
                            <div class="column--label">{s name="OrderItemColumnQuantity"}{/s}</div>
                        {/block}

                        {block name='frontend_account_order_item_quantity_value'}
                            <div class="column--value">{$article.quantity}</div>
                        {/block}
                    </div>
                {/block}

                {* Order item price *}
                {block name='frontend_account_order_item_price'}
                    <div class="panel--td order--price column--price">

                        {block name='frontend_account_order_item_price_label'}
                            <div class="column--label">{s name="OrderItemColumnPrice"}{/s}</div>
                        {/block}

                        {block name='frontend_account_order_item_price_value'}
                            {if $article.article.is_gadget<1}
                            <div class="column--value">
                                {if $article.price}
                                    {if $offerPosition.currency_position == "32"}
                                        {$offerPosition.currency_html} {$article.price} *
                                    {else}
                                        {$article.price} {$offerPosition.currency_html} *
                                    {/if}
                                {else}
                                    {s name="OrderItemInfoFree"}{/s}
                                {/if}
                            </div>
                            {/if}
                        {/block}
                    </div>
                {/block}

                {* Order item total amount *}
                {block name='frontend_account_order_item_amount'}
                    <div class="panel--td order--amount column--total">

                        {block name='frontend_account_order_item_amount_label'}
                            <div class="column--label">{s name="OrderItemColumnTotal"}{/s}</div>
                        {/block}

                        {block name='frontend_account_order_item_amount_value'}
                            {if $article.article.is_gadget<1}
                            <div class="column--value">
                                {if $article.amount}
                                    {if $offerPosition.currency_position == "32"}
                                        {$offerPosition.currency_html} {$article.amount}  *
                                    {else}
                                        {$article.amount} {$offerPosition.currency_html} *
                                    {/if}
                                {else}
                                    {s name="OrderItemInfoFree"}{/s}
                                {/if}
                            </div>
                            {/if}
                        {/block}
                    </div>
                {/block}
            </div>
        {/block}
    {/foreach}
{/block}