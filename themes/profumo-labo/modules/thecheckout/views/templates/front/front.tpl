{**
* NOTICE OF LICENSE
*
* This source file is subject to the Software License Agreement
* that is bundled with this package in the file LICENSE.txt.
*
*  @author    Peter Sliacky (Zelarg)
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}
{extends file='page.tpl'}

{block name="page_content"}

  {* necessary here, core's checkout.js script looks for #checkout element and binds handlers only then - setUpCheckout() *}
  {*<div id="checkout" class="fool-js-confirmation-controllers"></div>*}
  <script>
    var debug_js_controller = '{$debugJsController}';
    var static_token = '{$static_token}';
    var config_default_payment_method = '{$config->default_payment_method}';
    var config_show_i_am_business = '{$config->show_i_am_business}';
    var config_show_i_am_private = '{$config->show_i_am_private}';
    var config_force_customer_to_choose_country = '{$config->force_customer_to_choose_country}';
    var config_blocks_update_loader = '{$config->blocks_update_loader}';
    var config_refresh_minicart = (true == '{$config->refresh_minicart}');
    var config_postcode_remove_spaces = (true == '{$config->postcode_remove_spaces}');
    var config_separate_payment = (true == '{$config->separate_payment}');
    var separate_payment_key = '{$separatePaymentKeyName}';
    var isEmptyCart = '{$isEmptyCart}';
    var tcModuleBaseUrl = '{$urls.base_url}/modules/thecheckout';
    var forceRefreshShipping = '{$forceRefreshShipping}';
    var sendcloud_script = '{$sendcloud_script}';
    var i18_requiredField = "{l s='Required field' d='Shop.Forms.Errors'}";
    var i18_fixErrorBelow = "{l s='Please fix the error below.' d='Shop.Notifications.Error'}";
    var i18_sdiLength = "{l s='Inserire il codice SDI di sette cifre. Inserire sette volte zero (0000000) se non si possiede un codice SDI' mod='thecheckout'}";
    var i18_popupPaymentNotice = "{l s='Payment popup will appear once the form is confirmed' mod='thecheckout'}";
    var installedModules = [];
    {foreach $installedModules as $moduleName=>$moduleState}
      installedModules['{$moduleName}'] = '{$moduleState}';
    {/foreach}

  </script>
  <style>
    {if !$showShipToDifferentAddress && $isInvoiceAddressPrimary}
    {literal}
    #thecheckout-address-delivery {
      display: none;
    }

    {/literal}
    {/if}
    {if !$showBillToDifferentAddress && !$isInvoiceAddressPrimary}
    {literal}
    #thecheckout-address-invoice {
      display: none;
    }

    {/literal}
    {/if}
    {if !$config->offer_second_address}{literal}
    .second-address {
      display: none;
    }

    {/literal}{/if}
    {if !$config->show_block_reassurance}{literal}
    #block-reassurance {
      display: none;
    }

    {/literal}{/if}

    {if !$config->show_order_message}{literal}
    #thecheckout-order-message {
      display: none;
    }

    {/literal}{/if}
    {if !$config->using_material_icons}{literal}
    span.custom-radio input[type=radio] {
      opacity: 1;
    }

    span.custom-radio {
      border: none;
    }

    span.custom-radio input[type=radio]:checked + span {
      display: none;
    }

    i.material-icons.checkbox-checked {
      display: none;
    }

    .custom-checkbox input[type=checkbox] {
      opacity: 1;
    }

    .custom-checkbox input[type=checkbox] + span {
      opacity: 0;
      pointer-events: none;
    }

    {/literal}{/if}

    {*
    {if $config->show_i_am_business}{literal}
    #thecheckout-address-invoice .form-group.business-field {
      order: -1; /* Put business field in front if we have this checkbox */
    }

    {/literal}{/if}
    *}

    /* BEGIN Custom CSS styles from config page */
    {$config->custom_css nofilter}
    /* END Custom CSS styles from config page */
  </style>
  <script>
    /* BEGIN Custom JS code from config page */
    {$config->custom_js nofilter}
    /* END Custom JS code from config page */
  </script>
  {*<div id="checkout">*}
  <!-- this div tag is required due to core.js events registrations -->
  {*</div>*}

  {*{debug}*}
  <div id="empty-cart-notice">
    <h1>{l s='Cart is empty' d='Shop.Notifications.Error'}</h1>
    <a class="label" href="{$urls.pages.index}">
      <span class="laquo">«</span>{l s='Continue shopping' d='Shop.Theme.Actions'}
    </a>
  </div>
  <div id="is-test-mode-notice">
    <div class="notice">{l s='Test mode is enabled, only you can see The Checkout module active.' mod='thecheckout'}</div>
    <a class="close-notice" href="javascript:$('#is-test-mode-notice').fadeOut();">{l s='OK, close' mod='thecheckout'}</a>
  </div>
  {assign 'k' 1}
  
  <div class="container">
    <nav aria-label="breadcrumb" data-depth="2">
      <ol class="breadcrumb" style="font-family:Jost, sans-serif">         
        <li class="breadcrumb-item">
          <a href="{$urls.pages.index}">
            {if $pslanguage == 'pl'}STRONA GŁÓWNA {else} HOME {/if}
          </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
          <a href="{$urls.pages.order}">
            {if $pslanguage == 'pl'}PANEL KLIENTA {else} CUSTOMER PANEL {/if}
          </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {if $pslanguage == 'pl'}TWOJE DANE OSOBISTE {else} YOUR PERSONAL DATA {/if}
        </li>
      </ol>
    </nav>
  </div>
  
  {function blockContainer}
    {foreach $data as $key=>$sub_block}
      {if "blocks" === $key}
        <div class="blocks checkout-area-{$k++}{if $data.size<=35} width-below-35pct{/if}{if $data.size<=50} width-below-50pct{/if}{if $data.size<=70} width-below-70pct{/if}" style="flex-basis: {$data.size}%; min-width: {$data.size}%;">{foreach $sub_block as $checkout_block}
            {foreach $checkout_block as $blockName=>$classes}
              {if !in_array($blockName, $excludeBlocks)}
                <div class="tc-block-placeholder thecheckout-{$blockName}"></div>
                <div class="checkout-block{if $blockName|in_array:['login-form','account','address-invoice','address-delivery','shipping','payment']} main-block{/if} {$classes}" id="thecheckout-{$blockName}">
                  <div class="inner-area">
                    {if "cart-summary" == $blockName}
                      {*cart-summary block loaded via Ajax, display dummy container only*}
                      <section id="main">
                        <div class="cart-grid row">
                          <div class="card cart-container">
                            <div
                              class="block-header shopping-cart-header">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</div>
                          </div>
                        </div>
                      </section>
                      {include file='module:thecheckout/views/templates/front/_partials/blocks-loader.tpl'}
                      <div class="card cart-summary">
                      </div>
                    {/if}
                    {if "login-form" == $blockName}
                      {*won't be set in front.php for logged-in customers*}
                      {include file='module:thecheckout/views/templates/front/blocks/login-form.tpl'}
                    {/if}
                    {if "account" == $blockName}
                      {include file='module:thecheckout/views/templates/front/blocks/account.tpl'}
                    {/if}
                    {if "address-invoice" == $blockName}
                      {include file='module:thecheckout/views/templates/front/blocks/address-invoice.tpl'}
                    {/if}
                    {if "address-delivery" == $blockName}
                      {include file='module:thecheckout/views/templates/front/blocks/address-delivery.tpl'}
                    {/if}
                    {if "shipping" == $blockName}
                      {*shipping block loaded via Ajax, display dummy container only*}
                      <div
                        class="block-header shipping-method-header">{l s='Shipping Method' d='Shop.Theme.Checkout'}</div>
                      {include file='module:thecheckout/views/templates/front/_partials/blocks-loader.tpl'}
                    {/if}
                    {if "payment" == $blockName}
                      <section id="checkout-payment-step" class="js-current-step">
                        <div class="dynamic-content">
                          {*payment block loaded via Ajax, display dummy container only*}
                          <div
                            class="block-header shipping-method-header">{l s='Payment method' d='Shop.Theme.Checkout'}</div>
                          {include file='module:thecheckout/views/templates/front/_partials/blocks-loader.tpl'}
                        </div>
                      </section>
                      <div class="static-content"></div>
                      <div class="popup-payment-content">
                        <div class="popup-header">
                          <div class="popup-close-icon"></div>
                          <div class="popup-shop-info">
                            <div class="popup-shop-logo"><img src="{$shop.logo}"></div>
                            <div class="popup-shop-name">{$shop.name}</div>
                          </div>
                        </div>
                        <div class="popup-body">
                          <div class="popup-payment-form"></div>
                          <div class="popup-payment-button">
                            {include file='module:thecheckout/views/templates/front/_partials/payment-confirmation-button.tpl'}
                          </div>
                        </div>
                      </div>
                    {/if}
                    {if "order-message" == $blockName}
                      {include file='module:thecheckout/views/templates/front/blocks/order-message.tpl'}
                    {/if}
                    {if "confirm" == $blockName}
                      {include file='module:thecheckout/views/templates/front/blocks/confirm.tpl'}
                    {/if}
                    {if "html-box-1" == $blockName}
                      {if $logged}
                        {$config->html_box_1 nofilter}
                      {/if}
                    {/if}
                    {if "html-box-2" == $blockName}
                      {if !$logged}
                        {$config->html_box_2 nofilter}
                      {/if}
                    {/if}
                    {if "html-box-3" == $blockName}
                      {$config->html_box_3 nofilter}
                    {/if}
                    {if "html-box-4" == $blockName}
                      {$config->html_box_4 nofilter}
                    {/if}
                    {if "required-checkbox-1" == $blockName && isset($separateModuleFields['thecheckout_required-checkbox-1'])}
                      <form class="account-fields module-account-fields {$blockName}">
                        {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' field=$separateModuleFields['thecheckout_required-checkbox-1']}
                      </form>
                    {/if}
                    {if "required-checkbox-2" == $blockName && isset($separateModuleFields['thecheckout_required-checkbox-2'])}
                      <form class="account-fields module-account-fields {$blockName}">
                        {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' field=$separateModuleFields['thecheckout_required-checkbox-2']}
                      </form>
                    {/if}
                    {if "newsletter" == $blockName && isset($separateModuleFields['ps_emailsubscription_newsletter'])}
                      <form class="account-fields module-account-fields {$blockName}">
                        {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' field=$separateModuleFields['ps_emailsubscription_newsletter']}
                      </form>
                    {/if}
                    {if "psgdpr" == $blockName && isset($separateModuleFields['psgdpr_psgdpr'])}
                      <form class="account-fields module-account-fields {$blockName}">
                        {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' field=$separateModuleFields['psgdpr_psgdpr']}
                      </form>
                    {/if}
                    {if "data-privacy" == $blockName && isset($separateModuleFields['ps_dataprivacy_customer_privacy'])}
                      <form class="account-fields module-account-fields {$blockName}">
                        {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' field=$separateModuleFields['ps_dataprivacy_customer_privacy']}
                      </form>
                    {/if}
                  </div>
                </div>
              {/if}
            {/foreach}
          {/foreach}</div>
      {elseif "size" === $key} {*intentionally empty*}
      {else}
        {if 0 === $key|strpos:'flex-split'}
          <div class="{$key} checkout-area-{$k++}" style="flex-basis: {$data.size}%">
        {/if}
        {blockContainer data=$sub_block}
        {if 0 === $key|strpos:'flex-split'}
          </div>
        {/if}

      {/if}

    {/foreach}
  {/function}
  <div id="tc-container">

    {blockContainer data=$blocksLayout}

    {* This element will be added by JS script as overlay on binary payment methods *}
    <div class="save-account-overlay hidden">
      <button type="button" class="btn btn-primary center-block" data-link-action="x-save-account-overlay">
        <div class="tc-loader">
          <div class="lds-ellipsis-m">
            <div>
              <div></div>
            </div>
            <div>
              <div></div>
            </div>
            <div>
              <div></div>
            </div>
            <div>
              <div></div>
            </div>
            <div>
              <div></div>
            </div>
          </div>
        </div>
        {l s='Confirm & Show payment' mod='thecheckout'}
      </button>
    </div>
    {* This element is artificaly created, as "parent" element for calling prepareConfirmation *}
    <div id="prepare_confirmation" class="hidden"></div>
    <div class="modal fade" id="modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="js-modal-content"></div>
        </div>
      </div>
    </div>
    <div id="payment_forms_persistence"></div>
  </div>
  <div id="tc-container-mobile"></div>
{/block}
