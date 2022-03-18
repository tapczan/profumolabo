{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
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
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{extends file='page.tpl'}

{if $page.meta.title != 'O PROFUMO LABO'}
    {block name='page_title'}
      <span class="cms-block__title cms-block__title--center">
        {$cms.meta_title}  
      </span>
    {/block}
{/if}

{block name='page_content_container'}
  <section id="content" class="page-content cms-content page-cms page-cms-{$cms.id}">

    {block name='cms_content'}
      {if $page.meta.title == 'Information' || $page.meta.title == 'INFORMACJE' }
        <div class="container collapsed__container collapsed__container--no-tab js-collapse-no-tab">
          {$cms.content nofilter}
        </div>
        {include file='cms/_partials/contact-details-footer.tpl'}
      {else if $page.meta.title == 'Cooperation' || $page.meta.title == 'WSPÓŁPRACA'}
        {$cms.content nofilter}
        {include file='cms/_partials/contact-details-footer.tpl'}
      {else}
        <div class="cms-content--default">
          {$cms.content nofilter}
        </div>
      {/if}
    {/block}

    {block name='hook_cms_dispute_information'}
      {hook h='displayCMSDisputeInformation'}
    {/block}

    {block name='hook_cms_print_button'}
      {hook h='displayCMSPrintButton'}
    {/block}

  </section>
{/block}
