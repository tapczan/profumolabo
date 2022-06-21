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

<div id="arpl-group-{$model->id|intval}" class="arpl-group arpl-group-tabbed">

    {* Bestseller Title*}
    {if $model->id|intval == 5 || $model->id|intval == 17 || $model->id|intval == 22 }
        <div class="arpl-header">
            <h2 class="h2 arpl-title  arpl-text-center products-section-title text-center text-uppercase">{l s='Bestseller' d='Shop.Theme.Global'}</h2>
        </div>
    {/if}

    <ul class="nav nav-tabs">
        {foreach $lists as $k => $list}
            <li class="nav-item">
                {assign var='mobileTitle' value=FrontController::getMobileTitle($list.title|escape:'htmlall':'UTF-8')}
                <a href="#arpl-tab-{$list.model->id|intval}" data-group="{$model->id|intval}" data-id="{$list.model->id|intval}" class="{if $list.ajax}arpl-tab-ajax{/if} nav-link {if $k == 0}active{/if}" data-toggle="tab">
                    {if $isMobile}
                        {$mobileTitle}
                    {else}
                        {$list.title|escape:'htmlall':'UTF-8'}
                    {/if}
                </a>
                {if $list.ajax and $k == 0}
                <script>
                    window.addEventListener('load', function(){
                        arPL.loadAjaxTab({$model->id|intval}, {$list.model->id|intval}, 0);
                    });
                </script>
                {/if}
            </li>
        {/foreach}

        {if $model->id|intval == 20}
            <li class="nav-item">
                 <a href="#arpl-tab-custom1" data-group="c1" data-id="custom1" class="nav-link" data-toggle="tab">
                    {if $isMobile} 
                        {l s='SIMILAR' d='Modules.Createitrelatedproducts.Admin'}
                    {else}
                        {l s='SIMILAR PRODUCTS' d='Modules.Createitrelatedproducts.Admin'}
                    {/if}
                </a>
            </li>
        {/if}

    </ul>
    <div class="arpl-tab-content tab-content">
        {foreach $lists as $k => $list}
            <div id="arpl-tab-header-{$list.model->id|intval}" class="arpl-tab-header">
                <a href="#arpl-tab-{$list.model->id|intval}" data-group="{$model->id|intval}" data-id="{$list.model->id|intval}" class="{if $list.ajax and $k != 0}arpl-tab-ajax{/if} {if $k == 0}active{/if}" data-toggle="tab">
                    {$list.title|escape:'htmlall':'UTF-8'}
                </a>
            </div>
            <div id="arpl-tab-{$list.model->id|intval}" class="arpl-tab-pane tab-pane {if $k == 0}in active{/if}">
                {$list.content nofilter}
            </div>
        {/foreach}
        {if $model->id|intval == 20}
            <div id="arpl-tab-custom1" class="arpl-tab-pane tab-pane ">
                {block name='product_single_ct_related_products'}
                    {hook h='displayRelatedProducts' product=$product}
                {/block}
            </div>
         {/if}
    </div>
</div>
