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

<div class="contact-rich mb-4">
  <img src="{$urls.base_url}img/cms/kontaktimage.jpg" class="contact-featured-img">

  <div class="contact-data">
    <div class="contact-data__item">
      <ul class="contact-data__list">
        <li class="contact-data__title">
          PROFUMO LABO SP. Z O.O.
        </li>
        <li>
          Al. Jerozolimskie 98
        </li>
        <li>
          00-807 Warszawa
        </li>
        <li>
          NIP 7011046498
        </li>
        <li>
          KRS: 1111111111
        </li>
      </ul>
    </div>

    <div class="contact-data__item">
      <ul class="contact-data__list">
        <li class="contact-data__title contact-data__title--normal">
          GODZINY PRACY: PN - PT 8:00 - 17:00
        </li>
      </ul>
    </div>

    <div class="contact-data__item">
      <ul class="contact-data__list">
        <li class="contact-data__title">
          TEL.: <a href="tel:11111171111">111 111 711 11</a>
        </li>
        {if $contact_infos.email && $display_email}
          <li class="contact-data__title">
            {l s='EMAIL US:' d='Shop.Theme.Global'}: <a href="mailto:{$contact_infos.email}">{$contact_infos.email}</a>
          </li>
        {/if}
      </ul>
    </div>

    <div class="contact-data__item">
      <ul class="contact-data__list">
        <li class="contact-data__title contact-data__title--gold">
          PYTANIA I ODPOWIEDZI
        </li>
        <li class="contact-data__title contact-data__title--gold">
          SPRAWDŹ STATUS ZAMÓWIENIA
        </li>
      </ul>
    </div>
  </div>

  {*
  <h4>{l s='Store information' d='Shop.Theme.Global'}</h4>
  <div class="block">
    <div class="data">{$contact_infos.address.formatted nofilter}</div>
  </div>
  {if $contact_infos.phone}
    <hr/>
    <div class="block">
      <div class="data">
        {l s='Call us:' d='Shop.Theme.Global'}<br/>
        <a href="tel:{$contact_infos.phone}">{$contact_infos.phone}</a>
       </div>
    </div>
  {/if}
  {if $contact_infos.fax}
    <hr/>
    <div class="block">
      <div class="data">
        {l s='Fax:' d='Shop.Theme.Global'}<br/>
        {$contact_infos.fax}
      </div>
    </div>
  {/if}
  {if $contact_infos.email && $display_email}
    <hr/>
    <div class="block">
      <div class="data email">
        {l s='Email us:' d='Shop.Theme.Global'}<br/>
      </div>
      <a href="mailto:{$contact_infos.email}">{$contact_infos.email}</a>
    </div>
  {/if}
  *}
</div>
