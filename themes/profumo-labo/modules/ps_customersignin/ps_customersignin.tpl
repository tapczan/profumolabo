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
<div class="col flex-grow-0 header-top__block header-top__block--user header__inner-login">
  <a
    rel="nofollow"
    href="{$urls.pages.my_account}"
    {if $logged}
      title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
      class="header__inner-top-link header__inner-login-link login-link--isloggedin"
    {else}
      title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
      class="header__inner-top-link header__inner-login-link login-link--notloggedin"
    {/if}
  >
    <div class="header-top__icon-container">
      <span class="header__inner-login-label">ZALOGUJ SIĘ</span>
      <img class="header__inner-login-icon" src="{$urls.img_url}home-icon.png">
    </div>
  </a>
</div>
