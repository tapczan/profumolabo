{**
  * 2007-2019 PrestaShop.
  *
  * NOTICE OF LICENSE
  *
  * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
  * that is bundled with this package in the file LICENSE.txt.
  * It is also available through the world-wide-web at this URL:
  * https://opensource.org/licenses/AFL-3.0
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
  * @author    PrestaShop SA <contact@prestashop.com>
  * @copyright 2007-2019 PrestaShop SA
  * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
  * International Registered Trademark & Property of PrestaShop SA
  *}
<div>
  {if isset($displayedFacets) && $displayedFacets|count}
    <div id="search_filters" class="js-search-filters search-filters card">
      <div class="list-group list-group-flush">
        {block name='facets_clearall_button'}
          {if $activeFilters|count}
            <div class="clear-all-wrapper card-body">
              <button data-search-url="{$clear_all_link}"
                class="btn btn-sm btn-block btn-outline-secondary btn-sm js-search-filters-clear-all">
                <i class="material-icons font-reset align-middle">&#xE14C;</i>
                {l s='Clear all' d='Shop.Theme.Actions'}
              </button>
            </div>
          {/if}
        {/block}

        {*
        {if $category.level_depth <= 2 && $category.id != 140}
        <section class="search-filters__block list-group-item">
          {assign var=_collapse value=true}
          <div class="search-filters__header d-flex justify-content-between align-items-center h5 position-relative">
              <span class="search-filters__title">{l s='For Whom?' d='Shop.Theme.Global'}</span>
              <a href="#facet_forwhom" class="icon-collapse stretched-link text-reset d-block" data-toggle="collapse"
                {if !$_collapse} aria-expanded="true" {/if}>
                <span class="material-icons">&#xE313;</span>
              </a>
          </div>
           <div id="facet_forwhom" class="search-filters__collapse collapse{if !$_collapse} show{/if}">
            <div>
              <div class="custom-control custom-checkbox"> 
                <a href="{$link->getCategoryLink(10)}" style="text-decoration:none;color:#595959;">{l s='%title%' sprintf=['%title%' => FrontController::getCategoryName(10)] d='Shop.Theme.Global'}</a>
              </div>
              <div class="custom-control custom-checkbox">
                <a href="{$link->getCategoryLink(11)}" style="text-decoration:none;color:#595959;">{l s='%title%' sprintf=['%title%' => FrontController::getCategoryName(11)] d='Shop.Theme.Global'}</a>
              </div>
              <div class="custom-control custom-checkbox">
                <a href="{$link->getCategoryLink(141)}" style="text-decoration:none;color:#595959;">{l s='%title%' sprintf=['%title%' => FrontController::getCategoryName(141)] d='Shop.Theme.Global'}</a>
              </div>
            </div>
          </div>
        </section>
        {/if}
        *}
        
        {foreach from=$displayedFacets item="facet"}
 
          {assign var=_expand_id value=10|mt_rand:100000}
          {assign var=_collapse value=true}
          {foreach from=$facet.filters item="filter"}
            {if $filter.active}{assign var=_collapse value=false}{/if}
          {/foreach}
          <section class="search-filters__block list-group-item">
            <div class="search-filters__header d-flex justify-content-between align-items-center h5 position-relative">
              <span class="search-filters__title">
                {if $facet.label eq "Brand" || $facet.label eq "Marka" } 
                  {l s='Brand Inspiration' d='Shop.Theme.Global'} 
                {else} 
                  {if $facet.label eq 'Grade'}
                      {l s='Grade' d='Shop.Theme.Global'}
                  {else}
                      {$facet.label}
                  {/if}
                {/if}
              </span>
              <a href="#facet_{$_expand_id}" class="{$facet.type}{if isset($facet.properties.id_feature)}_{$facet.properties.id_feature}{/if} icon-collapse stretched-link text-reset d-block" data-toggle="collapse"
                {if !$_collapse} aria-expanded="true" {/if}>
                <span class="material-icons">&#xE313;</span>
              </a>
            </div>

            {if in_array($facet.widgetType, ['radio', 'checkbox'])}
              {block name='facet_item_other'}
                <div id="facet_{$_expand_id}" class="{$facet.type}{if isset($facet.properties.id_feature)}_{$facet.properties.id_feature}{/if} search-filters__collapse collapse{if !$_collapse} show{/if}">
                  {foreach from=$facet.filters key=filter_key item="filter"}
                    {if !$filter.displayed}
                      {continue}
                    {/if}
                    <div>
                      <div
                        class="custom-control custom-{if $facet.multipleSelectionAllowed}checkbox{if isset($filter.properties.color) || isset($filter.properties.texture)}-color{/if}{else}radio{/if}">
                        <input id="facet_input_{$_expand_id}_{$filter_key}" data-search-url="{$filter.nextEncodedFacetsURL}"
                          type="{if $facet.multipleSelectionAllowed}checkbox{else}radio{/if}" class="custom-control-input"
                          {if $filter.active } checked{/if}>
                        <label for="facet_input_{$_expand_id}_{$filter_key}" {if isset($filter.properties.color)}
                            class="custom-control-label custom-control-label-{if Tools::getBrightness($filter.properties.color) > 128}dark{else}bright{/if}"
                          {else} class="custom-control-label"
                          {/if}>
                          {if isset($filter.properties.color)}
                            <span class="custom-control-input-color" style="background-color:{$filter.properties.color}"></span>
                          {elseif isset($filter.properties.texture)}
                            <span class="custom-control-input-color texture"
                              style="background-image:url({$filter.properties.texture})"></span>
                          {/if}
                          {$filter.label}
                          {*{if $filter.magnitude and $show_quantities}*}
                            {*<span class="magnitude">({$filter.magnitude})</span>*}
                          {*{/if}*}
                        </label>
                      </div>
                    </div>
                  {/foreach}
                </div>
              {/block}

            {elseif $facet.widgetType == 'dropdown'}
              {block name='facet_item_dropdown'}
                <div>
                  <div id="facet_{$_expand_id}" class="{$facet.type}{if isset($facet.properties.id_feature)}_{$facet.properties.id_feature}{/if} search-filters__collapse  collapse{if !$_collapse} show{/if}">
                    <select class="custom-select">
                      <option value="">---</option>
                      {foreach from=$facet.filters item="filter"}
                        <option value="{$filter.nextEncodedFacetsURL}" {if $filter.active} selected="selected" {/if}>
                          {$filter.label}
                          {if $filter.magnitude and $show_quantities}
                            ({$filter.magnitude})
                          {/if}
                        </option>
                      {/foreach}
                    </select>

                  </div>
                </div>
              {/block}

            {elseif $facet.widgetType == 'slider'}
              {block name='facet_item_slider'}
                {foreach from=$facet.filters item="filter"}

                  <div>
                    <div id="facet_{$_expand_id}" class="{$facet.type}{if isset($facet.properties.id_feature)}_{$facet.properties.id_feature}{/if} search-filters__collapse  collapse{if !$_collapse} show{/if} search-filters__slider">
                      <div class="js-input-range-slider-container">
                        <div class="search-slider-input-wrapper">
                          <div class="search-filters__input-group">
                            <span class="js-input-range-slider form-control form-control-sm text-center search-filters__input">
                              {if $facet.filters.0.value}
                                {$facet.filters.0.value.0} {$facet.properties.unit}
                              {else}
                                {$facet.properties.min} {$facet.properties.unit}
                              {/if}
                            </span>
                          </div>
                          <span class="search-slider-separator">
                            -
                          </span>
                          <div class="search-filters__input-group">
                            <span class="js-input-range-slider form-control form-control-sm text-center search-filters__input">
                              {if $facet.filters.0.value}
                                {$facet.filters.0.value.1} {$facet.properties.unit}
                              {else}
                                {$facet.properties.max} {$facet.properties.unit}
                              {/if}
                            </span>
                          </div>
                          <div class="js-range-slider" data-slider-min="{$facet.properties.min}"
                            data-slider-max="{$facet.properties.max}" data-slider-id="{$_expand_id}"
                            data-slider-values="{$filter.value|@json_encode}" data-slider-unit="{$facet.properties.unit}"
                            data-slider-label="{$facet.label}"
                            data-slider-specifications="{$facet.properties.specifications|@json_encode}"
                            data-slider-encoded-url="{$filter.nextEncodedFacetsURL}" id="slider-range_{$_expand_id}"></div>
                      </div>
                    </div>
                  </div>
                {/foreach}
              {/block}
            {/if}
          </section>
        {/foreach}
        
       
        {if $category.level_depth <= 2 && $category.id != 140}
        <section class="search-filters__block list-group-item" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #212529">
          <input type="checkbox" id="drogeria" name="drogeria" data-url="{$link->getCategoryLink(140)}"/>
          <label for="drogeria">{l s='Show other brands only (Drogeria)' d='Shop.Theme.Global'}</label>
        </section>
        {/if}


      </div>
    </div>

  {/if}

</div>
