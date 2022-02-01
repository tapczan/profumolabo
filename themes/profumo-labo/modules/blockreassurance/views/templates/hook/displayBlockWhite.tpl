{*
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{* Block Instagram *}
{if $page.page_name == 'index'}

<div class="blockinstagram">
    <div class="blockinstagram__inner">
        <h2 class="blockinstagram__title">
            ZOBACZ NAS NA INSTAGRAMIE
        </h2>

        <div class="blockinstagram__feed">
            <div class="blockinstagram__small">
                <div class="blockinstagram__item">
                    <img class="blockinstagram__img" src="{$urls.img_url}instagram/ig1.png" alt="Instagram 1">
                </div>
                <div class="blockinstagram__item">
                    <img class="blockinstagram__img" src="{$urls.img_url}instagram/ig2.png" alt="Instagram 2">
                </div>
                <div class="blockinstagram__item">
                    <img class="blockinstagram__img" src="{$urls.img_url}instagram/ig3.png" alt="Instagram 3">
                </div>
                <div class="blockinstagram__item">
                    <img class="blockinstagram__img" src="{$urls.img_url}instagram/ig4.png" alt="Instagram 4">
                </div>
            </div>

            <div class="blockinstagram__big">
                <div class="blockinstagram__item">
                    <img class="blockinstagram__img" src="{$urls.img_url}instagram/ig5.png" alt="Instagram 5">
                </div>
                <div class="blockinstagram__item">
                    <img class="blockinstagram__img" src="{$urls.img_url}instagram/ig6.png" alt="Instagram 6">
                </div>
            </div>

            <div class="blockinstagram__small">
                <div class="blockinstagram__item">
                    <img class="blockinstagram__img" src="{$urls.img_url}instagram/ig7.png" alt="Instagram 7">
                </div>
                <div class="blockinstagram__item">
                    <img class="blockinstagram__img" src="{$urls.img_url}instagram/ig8.png" alt="Instagram 8">
                </div>
                <div class="blockinstagram__item">
                    <img class="blockinstagram__img" src="{$urls.img_url}instagram/ig9.png" alt="Instagram 9">
                </div>
                <div class="blockinstagram__item">
                    <img class="blockinstagram__img" src="{$urls.img_url}instagram/ig10.png" alt="Instagram 10">
                </div>
            </div>
        </div>
    </div>
</div>

{* Block Instagram *}

<div class="container">
  <div class="row">
    <div class="blockreassurance col-sm-12 pb-5 mb-md-5 mx-auto">
        {assign var=numCols value=$blocks|@count}
        {assign var=numColsRemaining_md value=($numCols % 4)}
        {assign var=numColsRemaining_sm value=($numCols % 2)}
        <div class="row">
            <div class="col-12 blockreassurance__header">
                <h2 class="h2 blockreassurance__title text-uppercase fw-regular">{l s='What makes us stand out' d='Shop.Theme.Global'}</h2>
            </div>
            <div class="js-block-reassurance-slider blockreassurance__slider">
                {foreach from=$blocks item=$block key=$key name=blocks}
                    {assign var=idxCol value=($smarty.foreach.blocks.index + 1)}
                    {assign var=sizeCol_md value=3}
                    {assign var=offsetCol_md value="offset-md-0"}
                    {assign var=sizeCol_sm value=6}
                    {assign var=offsetCol_sm value="offset-sm-0"}
                    {if $idxCol > ($numCols - $numColsRemaining_md)}
                        {if $numColsRemaining_md == 2}
                            {if !$smarty.foreach.blocks.last}
                                {assign var=offsetCol_md value="offset-md-3"}
                            {/if}
                        {else}
                            {assign var=sizeCol_md value=(12 / $numColsRemaining_md)}
                        {/if}
                    {/if}
                    {if $idxCol > ($numCols - $numColsRemaining_sm)}
                        {if $numColsRemaining_md == 1}
                            {assign var=offsetCol_sm value="offset-sm-3"}
                        {else}
                            {assign var=sizeCol_sm value=(12 / $numColsRemaining_md)}
                        {/if}
                    {/if}
                    <div>
                        <div {if $block['type_link'] !== $LINK_TYPE_NONE && !empty($block['link'])} style="cursor:pointer;" onclick="window.open('{$block['link']}')"{/if}>
                            <div class="block-icon">
                                {if $block['icon'] != 'undefined'}
                                    {if $block['icon']}
                                        <img class="svg invisible" src="{$block['icon']}">
                                    {elseif $block['custom_icon']}
                                        <img {if $block['is_svg']}class="svg invisible" {/if}src="{$block['custom_icon']}">
                                    {/if}
                                {/if}
                            </div>
                            <div class="block-title text-uppercase fw-regular mb-2 pb-2" style="color:{$textColor}">{$block['title']}</div>
                            <p class="fw-light mx-auto" style="color:{$textColor};">{$block['description'] nofilter}</p>
                        </div>
                    </div>
                {if $idxCol % 4 == 0}</div><div class="row">{/if}
                {/foreach}
            </div>
        </div>
    </div>
    </div>
</div>
{/if}