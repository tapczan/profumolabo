{*
 * 2015-2017 Bonpresta
 *
 * Bonpresta Responsive Carousel Feed Instagram Images
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    Bonpresta
 *  @copyright 2015-2017 Bonpresta
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div class="instagram-carousel-container clearfix block">
    <h4>{l s='Instagram' mod='boninstagramcarousel'}</h4>
    <div class="block_content">
        <ul class="instagram-list {if $display_caroucel}owl-carousel-instagram owl-carousel owl-theme owl-loaded owl-drag{else}clearfix row{/if}">
            {if $instagram_type == 'tagged'}
                {if isset($instagram_param) && $instagram_param}
                    {foreach from=$instagram_param item=media name=media}
                        {if $smarty.foreach.media.iteration <= $limit}
                            <li class="instagram-item {if !$display_caroucel}col-xs-12 col-sm-4 col-md-3{/if}" >
                                <a class="load-image" href="https://www.instagram.com/p/{$media.shortcode|escape:'htmlall':'UTF-8'}/"  target="_blank" rel="nofollow" title="instagram">
                                    <img class="lazy" src="{$media.thumbnail_src|escape:'htmlall':'UTF-8'}" data-src="{$media.thumbnail_src|escape:'htmlall':'UTF-8'}" alt="instagram" title="instagram">
                                   {* {assign var="instagramIMG" value=$media.thumbnail_src|escape:'htmlall':'UTF-8'}
                                    <img src="data:image/jpg;base64,base64_encode({$instagramIMG|file_get_contents})" alt="instagram" title="instagram">
                                    <img
                                        class="rounded img-fluid lazyload"
                                        {generateImagesSources image=$instagramIMG size='home_default' lazyload=true}
                                        width="300"
                                        height="300"
                                        loading="lazy"
                                    />*}
                                    <span class="instagram_cover"></span>
                                    <span class="instagram_likes">{$media.edge_liked_by.count|escape:'htmlall':'UTF-8'}</span>
                                    <span class="instagram_comment">{$media.edge_media_to_comment.count|escape:'htmlall':'UTF-8'}</span>
                                </a>
                            </li>
                        {/if}
                    {/foreach}
                {else}
                    <p>{l s='No select user or tag in module settings' mod='boninstagramcarousel'}</p>
                {/if}
            {/if}
        </ul>
    </div>
</div>
