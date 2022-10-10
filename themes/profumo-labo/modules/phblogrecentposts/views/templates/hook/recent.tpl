{if isset($recent_posts) && count($recent_posts)}
    <section class="simpleblog m-auto" id="phblogrecentposts">
    <div class="container">
        <div class="page-heading">
            <h1 class="mb-0">{l s='Last Viewed' mod='phblogrecentposts'}</h1>
        </div>
        <div class="row">
            {foreach from=$recent_posts item=post}
                {include file="module:phblogrecentposts/views/templates/hook/single.tpl"}
            {/foreach}
        </div><!-- .row -->
    </div><!-- .container -->
</section><!-- .section-news -->
{/if}