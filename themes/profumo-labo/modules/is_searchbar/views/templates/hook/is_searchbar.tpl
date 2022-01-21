{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<form id="_desktop_search_from" class="search-form js-search-form" data-search-controller-url="{$ajax_search_url}" method="get" action="{$search_controller_url}">
	<div class="search-form__form-group">
		<input type="hidden" name="controller" value="search">
		<input class="js-search-input search-form__input form-control" placeholder="{l s='Szukaj' d='Shop.Istheme'}" type="text" name="s" value="{$search_string}" autocomplete="off">
		<button type="submit" class="search-form__btn btn">
			<img class="header-top__icon header__inner-search-icon" src="{$urls.img_url}search-icon.svg">
		</button>
	</div>
</form>

<a role="button" class="search-toggler header-top__link jsSearchToggleMobile">
	<div class="header-top__icon-container">
		<img class="header-top__icon header__inner-search-icon" src="{$urls.img_url}search-mobile-icon.svg">
	</div>
</a>