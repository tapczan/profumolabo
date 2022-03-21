{*
* 2007-2014 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{extends file='page.tpl'}

{block name='page_header_container'}
<header class="page-header cms-block__title cms-block__title--center">
	<h1 class="h1">{$post->title}</h1>
</header>
{/block}

{block name='hook_after_body_opening_tag' append}{strip}
{if Configuration::get('PH_BLOG_FB_INIT')}
    <div id="fb-root"></div>
    <script>
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = 'https://connect.facebook.net/{$language.locale|replace:'-':'_'}/sdk.js#xfbml=1&version=v3.2&appId=&autoLogAppEvents=1';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
{/if}
{/strip}{/block}

{block name='head_seo_title'}{strip}
	{if !empty($post->meta_title)}
		{$post->meta_title} - {$page.meta.title}
	{else}
		{$post->title} - {$page.meta.title}
	{/if}
{/strip}{/block}

{if !empty($post->meta_description)}
    {block name='head_seo_description'}{$post->meta_description}{/block}
{/if}

{if !empty($post->meta_keywords)}
	{block name='head_seo_keywords'}{$post->meta_keywords}{/block}
{/if}

{block name='page_content'}
{assign var='post_type' value=$post->post_type}
<div class="simpleblog__post blog-mb">
	{if $post->featured_image && Configuration::get('PH_BLOG_DISPLAY_FEATURED')}
		<a href="{$post->featured_image}" title="{$post->title}" class="fancybox simpleblog__post-featured text-xs-center d-block">
			<img src="{$post->featured_image}" alt="{$post->title}" class="img-fluid" />
		</a>
	{/if}
    <div class="simpleblog__post__content card-block pb-1">
        {capture name='elementorContent'}{hook h='displayBlogElementor'}{/capture}
        {if !empty($smarty.capture.elementorContent)}
            {block name='hook_blog_elementor'}
            {$smarty.capture.elementorContent nofilter}
            {/block}
        {else}
            {$post->content nofilter}
        {/if}

        {if $post_type == 'gallery' && $post->gallery|@count}
        <div class="post-gallery">
            {if $post->gallery_style == 'masonry'}
            <div class="post-gallery__gallery-js">
                {foreach $post->gallery as $image}
                    <a rel="post-gallery-{$post->id_simpleblog_post}" class="blog-fancybox gallery-js__elem" href="{$gallery_dir}{$image.image}.jpg" title="{l s='View full' mod='ph_simpleblog'}"><img src="{$gallery_dir}{$image.image}-thumb.jpg" class="img-fluid" /></a>
                {/foreach}
            </div><!-- .post-gallery -->
            {else}
            <div class="post-gallery__row">
                {foreach $post->gallery as $image}
                {if $post->gallery_style == '3columns'}
                {$galleryCols = 'col-lg-4 col-md-4'}
                {elseif $post->gallery_style == '4columns'}
                {$galleryCols = 'col-lg-3 col-md-4'}
                {else}
                {$galleryCols = 'col-lg-6 col-md-6'}
                {/if}
                <div class="{$galleryCols} col-xs-6 post-gallery__elem">
                    <a rel="post-gallery-{$post->id_simpleblog_post}" class="blog-fancybox" href="{$gallery_dir}{$image.image}.jpg" title="{l s='View full' mod='ph_simpleblog'}"><img src="{$gallery_dir}{$image.image}-square.jpg" class="img-fluid" /></a>
                </div>
                {/foreach}
            </div><!-- .post-gallery -->
            {/if}
        </div>
		{elseif $post_type == 'video'}
		<div class="post-video" itemprop="video">
			{$post->video_code nofilter}
		</div><!-- .post-video -->
		{/if}
    </div>
    <div class="simpleblog__post__after-content" id="displayPrestaHomeBlogAfterPostContent">
		{hook h='displayPrestaHomeBlogAfterPostContent'}
	</div><!-- #displayPrestaHomeBlogAfterPostContent -->
    <div class="simpleblog__post__related pt-6" id="displaySimpleBlogRecentPosts">
		{hook h='displaySimpleBlogRecentPosts'}
	</div><!-- #displaySimpleBlogRecentPosts -->
</div>

{if $post->author->active|default:false && Configuration::get('PH_BLOG_DISPLAY_AUTHOR')}
    {include file='module:ph_simpleblog/views/templates/front/1.7/_partials/post-author.tpl' author=$post->author}
{/if}

{if Configuration::get('PH_BLOG_DISPLAY_RELATED') && $related_products}
	{include file="module:ph_simpleblog/views/templates/front/1.7/_partials/post-single-related-products.tpl"}
{/if}

<script type="application/ld+json">
{$jsonld nofilter}
</script>
{/block}


