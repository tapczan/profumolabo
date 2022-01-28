{*
* 2019 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*
*  @author Areama <contact@areama.net>
*  @copyright  2019 Areama
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*}

<li data-id-product="{$product.id_product|intval}" data-id-product-attribute="{$product.id_product_attribute|intval}" itemscope itemtype="http://schema.org/Product">
    
    
    <div class="row">

        <div class="col-md-6 arpl-thumbx">
            {include file="./custom/custom_compact_product_thumb.tpl"}
        </div>

        <div class="col-md-6 product-single__info arpl-contentx products-list__blockx">
            {include file="./custom/custom_compact_product_details.tpl"}





            {*
            <div class="comments_note">
                {if ($averageTotal>0 && Configuration::get('PRODUCT_COMMENTS_LIST') == 1) || $PRODUCT_COMMENTS_LIST_ALL == true}
                    <div class="star_content clearfix">
                        {section name="i" start=0 loop=5 step=1}
                            {if $averageTotal le $smarty.section.i.index}
                                <div class="star {if $averageTotal == 0 && $SHOW_FIVE_STARS == true}star_on{/if}"></div>
                            {else}
                                <div class="star star_on"></div>
                            {/if}
                        {/section}
                    </div>
                {/if}
            </div>
            

            <div class="comments_note">
                <div class="star_content clearfix">
                <div class="star star_on"></div>
                <div class="star star_on"></div>
                <div class="star star_on"></div>
                <div class="star star_on"></div>
                <div class="star "></div>
                </div>
                <span>0 Review(s)&nbsp;</span>
            </div>
             
            
            <div class="arpl-content-title">
                <h3 class="h3 product-title" itemprop="name">
                    <a href="{$product.url|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a>
                </h3>
            </div>
            {if $product.features}
                <div class="arpl-content-features">
                    <p>
                        {foreach $product.features as $feature}
                            {$feature.name|escape:'htmlall':'UTF-8'} - {$feature.value|escape:'htmlall':'UTF-8'}
                        {/foreach}
                    </p>
                </div>
            {/if}
            <div class="arpl-content-price">
                {block name='product_price_and_shipping'}
                    {if $product.show_price}
                        <div class="product-price-and-shipping">
                        {if $product.has_discount}
                            {hook h='displayProductPriceBlock' product=$product type="old_price"}

                            <span class="sr-only">{l s='Regular price' mod='arproductlists'}</span>
                            <span class="regular-price">{$product.regular_price|escape:'htmlall':'UTF-8'}</span>
                            {if $product.discount_type === 'percentage'}
                            <span class="discount-percentage discount-product">{$product.discount_percentage|escape:'htmlall':'UTF-8'}</span>
                            {elseif $product.discount_type === 'amount'}
                            <span class="discount-amount discount-product">{$product.discount_amount_to_display|escape:'htmlall':'UTF-8'}</span>
                            {/if}
                        {/if}

                        {hook h='displayProductPriceBlock' product=$product type="before_price"}

                        <span class="sr-only">{l s='Price' mod='arproductlists'}</span>
                        <span itemprop="price" class="price">{$product.price|escape:'htmlall':'UTF-8'}</span>

                        {hook h='displayProductPriceBlock' product=$product type='unit_price'}

                        {hook h='displayProductPriceBlock' product=$product type='weight'}
                        </div>
                    {/if}

                    <span class="product-stock-info">
                        Ostatnie <span class="product-stock-info__num">5</span> sztuk w tej cenie
                    </span>
                {/block}
            </div> 
            {block name='product_variants'}
                {if $product.main_variants}
                {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
                {/if}
            {/block}
            


 
            {if $product.customizable}
                <a class="btn btn-info" href="{$product.url|escape:'htmlall':'UTF-8'}" title="{l s='Customize' mod='arproductlists'}" data-id-product="{$product.id|intval}">
                    <span>{l s='Customize' mod='arproductlists'}</span>
                </a>
            {else}
                <a class="btn btn-primary" href="{$link->getAddToCartURL($product.id, $product.cache_default_attribute)|escape:'htmlall':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='arproductlists'}" data-id-product="{$product.id|intval}">
                    <span>{l s='Add to cart' mod='arproductlists'}</span>
                </a>
            {/if}
            
            <div class="product-sku">
                SYMBOL: P026US100
            </div>

            <div class="product-accordion" id="productSingleAccordion">

                <div class="product-accordion__item">
                    <div class="product-accordion__header" id="productAccordionHeader1" data-toggle="collapse" data-target="#productAccordionContent1" aria-expanded="true" aria-controls="productAccordionContent1">
                    {if $pslanguage == 'pl'}
                        OPIS     
                    {else if $pslanguage == 'en'}
                        DESCRIPTION
                    {/if}
                    </div>
                    <div class="product-accordion__body collapse show" id="productAccordionContent1" aria-labelledby="productAccordionHeader1" data-parent="#productSingleAccordion">
                        
                        
                        <p>{$product.description_short nofilter}</p>
                        
                        <a href="{$product.url|escape:'htmlall':'UTF-8'}">
                        {if $pslanguage == 'pl'}
                        SZCZEGÓŁY PRODUKTU     
                        {else if $pslanguage == 'en'}
                        PRODUCT DETAILS
                        {/if}
                    </a> 
                    </div>
                </div>
            </div>
            *}

        </div>
    
    </div> <!-- end row -->





    {* REMOVED THIS - MERGED TO ARPL CONTENT *}
    {*
    <div class="arpl-buttons">
       
        {if $product.customizable}
            <a class="btn btn-info" href="{$product.url|escape:'htmlall':'UTF-8'}" title="{l s='Customize' mod='arproductlists'}" data-id-product="{$product.id|intval}">
                <span>{l s='Customize' mod='arproductlists'}</span>
            </a>
        {else}
            <a class="btn btn-primary" href="{$link->getAddToCartURL($product.id, $product.cache_default_attribute)|escape:'htmlall':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='arproductlists'}" data-id-product="{$product.id|intval}">
                <span>{l s='Add to cart' mod='arproductlists'}</span>
            </a>
        {/if}
      

        <div class="product-sku">
            SYMBOL: P026US100
        </div>

        <div class="product-accordion" id="productSingleAccordion">

            <div class="product-accordion__item">
                <div class="product-accordion__header" id="productAccordionHeader1" data-toggle="collapse" data-target="#productAccordionContent1" aria-expanded="true" aria-controls="productAccordionContent1">
                {if $pslanguage == 'pl'}
                    OPIS     
                {else if $pslanguage == 'en'}
                    DESCRIPTION
                {/if}
                </div>
                <div class="product-accordion__body collapse show" id="productAccordionContent1" aria-labelledby="productAccordionHeader1" data-parent="#productSingleAccordion">
                    <p>{$product.description|strip_tags|escape:'htmlall':'UTF-8'}</p>
                    <a href="{$product.url|escape:'htmlall':'UTF-8'}">
                    {if $pslanguage == 'pl'}
                    SZCZEGÓŁY PRODUKTU     
                    {else if $pslanguage == 'en'}
                    PRODUCT DETAILS
                    {/if}
                </a> 
                </div>
            </div>
        </div>
    </div>
    *}

</li>






{* BASE TPL

<li data-id-product="{$product.id_product|intval}" data-id-product-attribute="{$product.id_product_attribute|intval}" itemscope itemtype="http://schema.org/Product">
    <div class="arpl-thumb">
        {block name='product_flags'}
            <ul class="product-flags">
              {foreach from=$product.flags item=flag}
                <li class="product-flag {$flag.type|escape:'htmlall':'UTF-8'}">{$flag.label|escape:'htmlall':'UTF-8'}</li>
              {/foreach}
            </ul>
        {/block}
        {if $product.cover}
          <a href="{$product.url|escape:'htmlall':'UTF-8'}" class="thumbnail product-thumbnail">
            <img src="{$product.cover.bySize.home_default.url|escape:'htmlall':'UTF-8'}"
              alt="{if !empty($product.cover.legend)}{$product.cover.legend|escape:'htmlall':'UTF-8'}{else}{$product.name|escape:'htmlall':'UTF-8'}{/if}"
              data-full-size-image-url="{$product.cover.large.url|escape:'htmlall':'UTF-8'}" >
          </a>
        {else}
          <a href="{$product.url|escape:'htmlall':'UTF-8'}" class="thumbnail product-thumbnail">
            <img src="{$urls.no_picture_image.bySize.home_default.url|escape:'htmlall':'UTF-8'}" >
          </a>
        {/if}
    </div>
    <div class="arpl-content">
        <div class="arpl-content-title">
            <h3 class="h3 product-title" itemprop="name"><a href="{$product.url|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a></h3>
        </div>
        {if $product.features}
            <div class="arpl-content-features">
                <p>
                    {foreach $product.features as $feature}
                        {$feature.name|escape:'htmlall':'UTF-8'} - {$feature.value|escape:'htmlall':'UTF-8'}
                    {/foreach}
                </p>
            </div>
        {/if}
        <div class="arpl-content-desc">
            <p>{$product.description_short|strip_tags|escape:'htmlall':'UTF-8'}</p>
        </div>
        
        <div class="arpl-content-price">
            {block name='product_price_and_shipping'}
                {if $product.show_price}
                  <div class="product-price-and-shipping">
                    {if $product.has_discount}
                      {hook h='displayProductPriceBlock' product=$product type="old_price"}

                      <span class="sr-only">{l s='Regular price' mod='arproductlists'}</span>
                      <span class="regular-price">{$product.regular_price|escape:'htmlall':'UTF-8'}</span>
                      {if $product.discount_type === 'percentage'}
                        <span class="discount-percentage discount-product">{$product.discount_percentage|escape:'htmlall':'UTF-8'}</span>
                      {elseif $product.discount_type === 'amount'}
                        <span class="discount-amount discount-product">{$product.discount_amount_to_display|escape:'htmlall':'UTF-8'}</span>
                      {/if}
                    {/if}

                    {hook h='displayProductPriceBlock' product=$product type="before_price"}

                    <span class="sr-only">{l s='Price' mod='arproductlists'}</span>
                    <span itemprop="price" class="price">{$product.price|escape:'htmlall':'UTF-8'}</span>

                    {hook h='displayProductPriceBlock' product=$product type='unit_price'}

                    {hook h='displayProductPriceBlock' product=$product type='weight'}
                  </div>
                {/if}
            {/block}
        </div>
        {block name='product_variants'}
          {if $product.main_variants}
            {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
          {/if}
        {/block}
    </div>
    <div class="arpl-buttons">
        {if $product.customizable}
            <a class="btn btn-info" href="{$product.url|escape:'htmlall':'UTF-8'}" title="{l s='Customize' mod='arproductlists'}" data-id-product="{$product.id|intval}">
                <span>{l s='Customize' mod='arproductlists'}</span>
            </a>
        {else}
            <a class="btn btn-primary" href="{$link->getAddToCartURL($product.id, $product.cache_default_attribute)|escape:'htmlall':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='arproductlists'}" data-id-product="{$product.id|intval}">
                <span>{l s='Add to cart' mod='arproductlists'}</span>
            </a>
        {/if}
    </div>
</li>

*}