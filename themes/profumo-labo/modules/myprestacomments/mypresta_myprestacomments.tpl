{*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
*  MODIFIED BY MYPRESTA.EU FOR PRESTASHOP 1.7 PURPOSES !
*
*}

<script type="text/javascript">
    var myprestacomments_controller_url = '{$myprestacomments_controller_url nofilter}';
    var confirm_report_message = '{l s='Are you sure that you want to report this comment?' mod='myprestacomments' js=1}';
    var secure_key = '{$secure_key}';
    var myprestacomments_url_rewrite = '{$myprestacomments_url_rewriting_activated}';
    var MyprestaComment_added = '{l s='Your comment has been added!' mod='myprestacomments' js=1}';
    var MyprestaComment_added_moderation = '{l s='Your comment has been submitted and will be available once approved by a moderator.' mod='myprestacomments' js=1}';
    var MyprestaComment_title = '{l s='New comment' mod='myprestacomments' js=1}';
    var MyprestaComment_ok = '{l s='OK' mod='myprestacomments' js=1}';
    var moderation_active = {$moderation_active};
</script>
<div id="myprestacommentsBlock" class="product-comment">
    <div class="product-comment__header">
        <h2 class="product-comment__title">{l s='Reviews' mod='myprestacomments'}</h2>

        {if ($too_early == false AND ($logged OR $allow_guests))}
            <div class="product-comment__controls">
                <a class="product-comment__btn-form js-product-comment-btn-form" href="javascript:;">{l s='Write your review' mod='myprestacomments'}</a>
            </div>
        {/if}
        <span class="product-comment__close js-comment-close"></span>
    </div>
    
    <div id="new_comment_form_ok" class="product-comment__alert js-comment-alert" style="display:none;"></div>
    
    <div id="product_comments_block_tab" class="product-comment__list js-comment-form">
        {if $comments}
            {foreach from=$comments item=comment}
                {if $comment.content}
                    <div class="product-comment__item" itemprop="review" itemscope itemtype="https://schema.org/Review">
                        <ul class="star-rating" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                            {section name="i" start=0 loop=5 step=1}
                                {if $comment.grade le $smarty.section.i.index}
                                    <li class="star-rating__list">
                                        <span class="star-rating__icon"></span>
                                    </li>
                                {else}
                                    <li class="star-rating__list">
                                        <span class="star-rating__icon star-rating__icon--active"></span>
                                    </li>
                                {/if}
                            {/section}

                            <meta itemprop="worstRating" content = "0" />
                            <meta itemprop="ratingValue" content = "{$comment.grade}" />
                            <meta itemprop="bestRating" content = "5" />
                        </ul>
                        
                        <div class="comment-details">
                            {if $PRODUCT_COMMENT_BODY == 1}
                                <div class="comment-body" itemprop="reviewBody">{$comment.content|escape:'html':'UTF-8'|nl2br nofilter}</div>
                            {/if}

                            {hook::exec('displayMyprestaComment', $comment) nofilter}
                        </div>

                        <div class="comment-footer" >
                            {if $PRODUCT_COMMENT_AUTHOR == 1}
                                <span itemprop="author">{$comment.customer_name|escape:'html':'UTF-8'}</span>
                            {/if}
                            <span class="comment-footer--normal">dodano</span>
                            {if $PRODUCT_COMMENT_DATE == 1}
                                <span>{dateFormat date=$comment.date_add|escape:'html':'UTF-8' full=0}</span>
                            {/if}

                            <meta itemprop="datePublished" content="{dateFormat date=$comment.date_add|escape:'html':'UTF-8' full=0}" />
                        </div>
                    </div>
                {/if}
            {/foreach}
        {else}
            {if (!$too_early AND ($logged OR $allow_guests))}
                <div class="product-comment__alert-notice">
                    {l s='Be the first to write your review' mod='myprestacomments'}
                </div>
            {else}
                <div class="product-comment__alert-notice">
                    {l s='No customer reviews for the moment.' mod='myprestacomments'}
                </div>
            {/if}
        {/if}
    </div>

    {if isset($myprestacomments_product) && $myprestacomments_product}
        <!-- Popup -->
        <div class="product-comment__form--overlay js-product-comment-form--overlay">
            <div id="new_comment_form" class="product-comment__form js-product-comment-form">
                <form id="id_new_comment_form" action="#">
                    <div class="comment-header-wrapper">
                        <h2 class="title">{l s='Write your review' mod='myprestacomments'}</h2>
                        <span class="material-icons js-product-comment-form-close product-comment__form-close">close</span>
                    </div>
                    {if isset($myprestacomments_product) && $myprestacomments_product}
                        <div class="product clearfix">
                            <div class="product_desc comment-form-desc">
                                <p class="product_name"><strong>{if isset($myprestacomments_product->name)}{$myprestacomments_product->name}{elseif isset($myprestacomments_product.name)}{$myprestacomments_product.name}{/if}</strong></p>
                                {if isset($myprestacomments_product->description_short)}{$myprestacomments_product->description_short nofilter}{elseif isset($myprestacomments_product.description_short)}{$myprestacomments_product.description_short nofilter}{/if}
                            </div>
                        </div>
                    {/if}
                    <div class="new_comment_form_content">
                        <h2>{l s='Write your review' mod='myprestacomments'}</h2>
                        <div id="new_comment_form_error" class="error" style="display:none;padding:15px 25px">
                            <ul></ul>
                        </div>
                        {if $criterions|@count > 0}
                            <ul id="criterions_list">
                                {foreach from=$criterions item='criterion'}
                                    <li>
                                        <label class="star-rating-label">Jakość</label>
                                        <div class="star_content">
                                            <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="1"/>
                                            <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="2"/>
                                            <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="3"/>
                                            <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="4"/>
                                            <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="5" checked="checked"/>
                                        </div>
                                        <div class="clearfix"></div>
                                    </li>
                                {/foreach}
                            </ul>
                        {/if}
                        <label for="comment_title" style="display: none;">{l s='Title for your review' mod='myprestacomments'}<sup class="required">*</sup></label>
                        <input id="comment_title" class="js-input-comment" name="title" type="hidden" value="Recenzja"/>

                        <label for="content">{l s='Your review' mod='myprestacomments'}<sup class="required">*</sup></label>
                        <textarea id="content" class="js-textarea-comment" name="content"></textarea>

                        {if $allow_guests == true && !$logged}
                            <label>{l s='Your name' mod='myprestacomments'}<sup class="required">*</sup></label>
                            <input id="commentCustomerName" name="customer_name" type="text" value=""/>
                            <label>{l s='Your email' mod='myprestacomments'}<sup class="required">*</sup></label>
                            <input id="commentCustomerEmail" name="customer_email" type="text" value=""/>
                        {/if}

                        <div id="new_comment_form_footer">
                            <input id="id_product_comment_send" name="id_product" type="hidden" value='{$id_product_comment_form}'/>
                            <p class="required"><sup>*</sup> {l s='Required fields' mod='myprestacomments'}</p>
                            <p class="fr">
                                {if $PRODUCT_COMMENTS_GDPR == 1}
                                {literal}
                                    <input onchange="if($(this).is(':checked')){$('#submitNewMessage').removeClass('gdpr_disabled'); $('#submitNewMessage').removeAttr('disabled'); rebindClickButton();}else{$('#submitNewMessage').addClass('gdpr_disabled'); $('#submitNewMessage').off('click'); $('#submitNewMessage').attr('disabled', 1); }" id="gdpr_checkbox" type="checkbox" >
                                {/literal}
                                    {l s='I accept ' mod='myprestacomments'} <a target="_blank" href="{$link->getCmsLink($PRODUCT_COMMENTS_GDPRCMS)}">{l s='privacy policy' mod='myprestacomments'}</a> {l s='rules' mod='myprestacomments'}
                                {/if}

                                <button {if $PRODUCT_COMMENTS_GDPR == 1}disabled{/if} class="js-comment-button-submit comment-button-submit {if $PRODUCT_COMMENTS_GDPR == 1}gdpr_disabled{/if}" id="submitNewMessage" name="submitMessage" type="submit" data-fancybox-close>{l s='Send' mod='myprestacomments'}</button>&nbsp;
                                {l s='or' mod='myprestacomments'}&nbsp;<a href="#" onclick="$.fancybox.close();" class="js-trigger-click-submit">{l s='Cancel' mod='myprestacomments'}</a>
                            </p>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </form><!-- /end new_comment_form_content -->
            </div>
        </div>
        <!-- End fancybox -->
    {/if}
</div>