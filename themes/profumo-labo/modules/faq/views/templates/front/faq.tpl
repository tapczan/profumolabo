{extends file=$layout}

{block name='content'}

<div class="container collapsed__container">
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

  <h1 class="collapsed__header">
    {l s='FREQUENTLY ASKED QUESTIONS' d='Shop.Theme.Global'}
  </h1>

  <div class="collapsed__tab">
    <ul class="collapsed__tab-nav nav nav-tabs">
      <li class="collapsed__tab-item nav-item">
        <a href="#collapsed__tab-1" class="collapsed__tab-link nav-link active" data-toggle="tab">
          {l s='SHOPPING' d='Shop.Theme.Global'}
        </a>
      </li>
      <li class="collapsed__tab-item nav-item">
        <a href="#collapsed__tab-2" class="collapsed__tab-link nav-link" data-toggle="tab">
          {l s='ORDERS AND DELIVERY' d='Shop.Theme.Global'}
        </a>
      </li>
      <li class="collapsed__tab-item nav-item">
        <a href="#collapsed__tab-3" class="collapsed__tab-link nav-link" data-toggle="tab">
          {l s='RETURNS' d='Shop.Theme.Global'}
        </a>
      </li>
    </ul>

    <div class="collapsed__tab-content tab-content">
      <div id="collapsed__tab-1" class="collapsed__tab-pane tab-pane active">
        <h2 class="collapsed__tab-title js-trigger-collapsed-mobile collapsed__collapse--mobile-active">
          {l s='SHOPPING' d='Shop.Theme.Global'}
        </h2>
        <div class="collapsed__collapse--mobile">
          {assign var='shoppings' value=FrontController::sliceFaqs($faqs,0,10)}
          
          {foreach from=$shoppings item=shopping}
            <details class="collapsed__collapse">
              <summary class="collapsed__collapse-title">
                {$shopping.question nofilter}
              </summary>
              <div class="collapsed__collapse-content">
                {$shopping.answer nofilter}
              </div>
            </details>
          {/foreach}
        </div>
      </div>

      <div id="collapsed__tab-2" class="collapsed__tab-pane tab-pane in">
        <h2 class="collapsed__tab-title js-trigger-collapsed-mobile">
          {l s='ORDERS AND DELIVERY' d='Shop.Theme.Global'}
        </h2>
        <div class="collapsed__collapse--mobile">
          {assign var='ordersanddeliverys' value=FrontController::sliceFaqs($faqs,10, 8)}
          
          {foreach from=$ordersanddeliverys item=ordersanddelivery}
            <details class="collapsed__collapse">
              <summary class="collapsed__collapse-title">
                {$ordersanddelivery.question nofilter}
              </summary>
              <div class="collapsed__collapse-content">
                {$ordersanddelivery.answer nofilter}
              </div>
            </details>
          {/foreach}
        </div>
      </div>

      <div id="collapsed__tab-3" class="collapsed__tab-pane tab-pane in">
        <h2 class="collapsed__tab-title js-trigger-collapsed-mobile">
          {l s='RETURNS' d='Shop.Theme.Global'}
        </h2>
        <div class="collapsed__collapse--mobile">
          {assign var='returns' value=FrontController::sliceFaqs($faqs,18,6)}
          
          {foreach from=$returns item=return}
            <details class="collapsed__collapse">
              <summary class="collapsed__collapse-title">
                {$return.question nofilter}
              </summary>
              <div class="collapsed__collapse-content">
                {$return.answer nofilter}
              </div>
            </details>
          {/foreach}
        </div>
      </div>
    </div>
  </div>

  <div class="collapsed__more">
    <h2 class="collapsed__more-title">
      {l s="DIDN'T FIND THE ANSWER TO YOUR QUESTION?" d='Shop.Theme.Global'}
    </h2>

    <h3 class="collapsed__more-subtitle">
      {l s='CONTACT US THROUGH THE CONTACT FORM' d='Shop.Theme.Global'}
    </h3>

    <ul class="collapsed__more-list">
      <li class="collapsed__more-item">
        <a href="{$urls.pages.contact}" class="collapsed__more-link">
          <img src="{$urls.img_url}faq-paper-icon.svg" class="collapsed__more-icon">

          <span class="collapsed__more-label">
            {l s='CONTACT FORM' d='Shop.Theme.Global'}
          </span>
        </a>
      </li>
      
      <li class="collapsed__more-item">
        <a href="mailto:hello@profumolabo.com" class="collapsed__more-link">
          <img src="{$urls.img_url}faq-envelop-icon.svg" class="collapsed__more-icon">

          <span class="collapsed__more-label">
            {l s='E-MAIL' d='Shop.Theme.Global'}
          </span>
        </a>
      </li>
      
      <li class="collapsed__more-item">
        <a href="tel:11111171111" class="collapsed__more-link">
          <img src="{$urls.img_url}faq-phone-icon.svg" class="collapsed__more-icon">

          <span class="collapsed__more-label">
            {l s='Telephone' d='Shop.Theme.Global'}
          </span>
        </a>
      </li>
    </ul>
  </div>
</div>

{/block}