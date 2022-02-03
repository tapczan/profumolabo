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
<div id="hook_footer_before_wrapper" class="footer-paralax" data-show-translation="Show more">
  {block name='hook_footer_before'}
    {hook h='displayFooterBefore'}
  {/block}
</div>
<div class="footer-container">
  <div class="container">
    <div class="row">
      <div class="col-12 text-center">
        <img class="footer-container__logo img-fluid" src="{$urls.img_url}footer_logo.svg" alt="">
      </div>
      {block name='hook_footer'}
      <div class="col-12">
        <div class="row justify-content-between px-md-0 px-5">
        {hook h='displayFooter'}
        </div>
      </div>
      {/block}
      <div class="col-12 mt-md-0 mt-5">
        <hr>
      </div>
      <div class="col-7 d-md-none">
        <div class="pl-4">
          {widget name='ps_socialfollow'}
        </div>
      </div>
      <div class="col-lg-12 col-5">
        <img class="footer-container__payment img-fluid" src="{$urls.img_url}payment.png" alt="">
      </div>
    </div>
    <div class="row">
      {block name='hook_footer_after'}
        {hook h='displayFooterAfter'}
      {/block}
    </div>
  </div>
</div>
