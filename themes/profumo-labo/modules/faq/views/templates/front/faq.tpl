{extends file=$layout}

{block name='content'}

<div class="container faq__container">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{$urls.pages.index}">
        {l s='Home' d='Shop.Theme.Global'}
      </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
      {l s='FAQ' d='Shop.Theme.Global'}
    </li>
  </ol>

  <h1 class="faq__header">
    {l s='FREQUENTLY ASKED QUESTIONS' mod='faq'}
  </h1>

  <div class="faq__tab">
    <ul class="faq__tab-nav nav nav-tabs">
      <li class="faq__tab-item nav-item">
        <a href="#faq__tab-1" class="faq__tab-link nav-link active" data-toggle="tab">
          {l s='SHOPPING' mod='faq'}
        </a>
      </li>
      <li class="faq__tab-item nav-item">
        <a href="#faq__tab-2" class="faq__tab-link nav-link" data-toggle="tab">
          {l s='ORDERS AND DELIVERY' mod='faq'}
        </a>
      </li>
      <li class="faq__tab-item nav-item">
        <a href="#faq__tab-3" class="faq__tab-link nav-link" data-toggle="tab">
          {l s='RETURNS' mod='faq'}
        </a>
      </li>
    </ul>

    <div class="faq__tab-content tab-content">
      <div id="faq__tab-1" class="faq__tab-pane tab-pane active">
        <h2 class="faq__tab-title js-trigger-faq-mobile faq__collapse--mobile-active">
          {l s='SHOPPING' mod='faq'}
        </h2>
        <div class="faq__collapse--mobile">
          {assign var='shoppings' value=FrontController::sliceFaqs($faqs,0,10)}
          
          {foreach from=$shoppings item=shopping}
            <details class="faq__collapse">
              <summary class="faq__collapse-title">
                {$shopping.question nofilter}
              </summary>
              <div class="faq__collapse-content">
                {$shopping.answer nofilter}
              </div>
            </details>
          {/foreach}
        </div>
      </div>

      <div id="faq__tab-2" class="faq__tab-pane tab-pane in">
        <h2 class="faq__tab-title js-trigger-faq-mobile">
          {l s='ORDERS AND DELIVERY' mod='faq'}
        </h2>
        <div class="faq__collapse--mobile">
          {assign var='ordersanddeliverys' value=FrontController::sliceFaqs($faqs,10, 8)}
          
          {foreach from=$ordersanddeliverys item=ordersanddelivery}
            <details class="faq__collapse">
              <summary class="faq__collapse-title">
                {$ordersanddelivery.question nofilter}
              </summary>
              <div class="faq__collapse-content">
                {$ordersanddelivery.answer nofilter}
              </div>
            </details>
          {/foreach}
        </div>
      </div>

      <div id="faq__tab-3" class="faq__tab-pane tab-pane in">
        <h2 class="faq__tab-title js-trigger-faq-mobile">
          {l s='RETURNS' mod='faq'}
        </h2>
        <div class="faq__collapse--mobile">
          {assign var='returns' value=FrontController::sliceFaqs($faqs,18,6)}
          
          {foreach from=$returns item=return}
            <details class="faq__collapse">
              <summary class="faq__collapse-title">
                {$return.question nofilter}
              </summary>
              <div class="faq__collapse-content">
                {$return.answer nofilter}
              </div>
            </details>
          {/foreach}
        </div>
      </div>
    </div>
  </div>

  <div class="faq__more">
    <h2 class="faq__more-title">
      {l s="DIDN'T FIND THE ANSWER TO YOUR QUESTION?" mod='faq'}
    </h2>

    <h3 class="faq__more-subtitle">
      {l s='CONTACT US THROUGH THE CONTACT FORM' mod='faq'}
    </h3>

    <ul class="faq__more-list">
      <li class="faq__more-item">
        <a href="{$urls.pages.contact}" class="faq__more-link">
          <img src="{$urls.img_url}faq-paper-icon.svg" class="faq__more-icon">

          <span class="faq__more-label">
            {l s='CONTACT FORM' mod='faq'}
          </span>
        </a>
      </li>
      
      <li class="faq__more-item">
        <a href="mailto:m.mikolaszek@createit.com" class="faq__more-link">
          <img src="{$urls.img_url}faq-envelop-icon.svg" class="faq__more-icon">

          <span class="faq__more-label">
            {l s='E-MAIL' mod='faq'}
          </span>
        </a>
      </li>
      
      <li class="faq__more-item">
        <a href="tel:11111171111" class="faq__more-link">
          <img src="{$urls.img_url}faq-phone-icon.svg" class="faq__more-icon">

          <span class="faq__more-label">
            {l s='Telephone' mod='faq'}
          </span>
        </a>
      </li>
    </ul>
  </div>
</div>

{/block}