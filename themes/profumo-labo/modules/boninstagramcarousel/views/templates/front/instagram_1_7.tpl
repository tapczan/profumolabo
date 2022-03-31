{*
* 2015-2021 Bonpresta
*
* Bonpresta Instagram Carousel Social Feed Photos
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future.
*
* @author Bonpresta
* @copyright 2015-2021 Bonpresta
* @license http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{extends file=$layout}

{block name='content'}
    <div class="blockinstagram">
        <div class="blockinstagram__inner">
            <h2 class="blockinstagram__title">
                {l s='Instagram' mod='boninstagramcarousel'}
            </h2>

            <div class="blockinstagram__feed">
                {for $i=1 to $limit}
                    {if $i == 1 || $i == 5 || $i == 7}
                        <div class="{if $i == 5}blockinstagram__big{else}blockinstagram__small{/if}">
                    {/if}
                            <div class="blockinstagram__item">
                                <a href="https://www.instagram.com/{if $instagram_type == "tagged"}explore/tags/{$user_tag|escape:'htmlall':'UTF-8'}{else}{$user_id|escape:'htmlall':'UTF-8'}{/if}/" target="_blank" rel="nofollow">
                                    <img src="{_MODULE_DIR_}boninstagramcarousel/views/img/parseImg/sample-{$i|escape:'htmlall':'UTF-8'}.jpg" alt="instagram-{$i|escape:'htmlall':'UTF-8'}">
                                    <span class="blockinstagram__overlay"></span>
                                    <svg class="blockinstagram__overlay-icon" x="0px" y="0px" viewBox="0 0 611.977 611.977" style="enable-background:new 0 0 611.977 611.977;" xml:space="preserve">
                                        <g>
                                            <path d="M604.056,284.039c-2.196-2.196-4.461-5.216-5.902-7.412c-48.18-52.641-103.018-92.654-162.317-120.108
                                                c-83.046-37.817-167.533-39.258-249.824-3.706c-63.005,26.698-120.108,68.221-172.68,125.255
                                                c-17.776,18.531-17.776,37.062,0,56.348C54.1,379.644,102.28,416.706,157.118,445.6c20.041,10.364,40.013,19.286,60.054,25.188
                                                c29.649,8.922,60.054,14.07,91.145,14.07l0,0c7.412,0,14.825,0,22.237-0.755c13.315-1.51,28.14-2.951,42.278-6.657
                                                c84.487-21.482,160.876-69.662,231.979-148.247C614.488,315.884,614.488,298.108,604.056,284.039z M582.574,310.737
                                                c-67.466,74.124-138.639,120.108-217.91,140.08c-12.628,2.951-26.698,4.461-38.572,5.902c-34.111,2.196-68.908-1.51-102.263-11.873
                                                c-18.531-5.902-37.062-13.315-54.838-23.747c-51.886-27.453-97.871-63.005-136.374-105.969c-8.167-8.922-8.167-11.119,0-19.286
                                                c50.377-54.083,103.773-93.409,163.072-118.598c36.307-15.58,73.369-23.747,109.675-23.747c39.258,0,78.585,8.922,117.843,27.453
                                                c56.348,25.943,108.234,64.515,154.15,114.137c1.51,1.51,2.951,3.706,4.461,5.216C584.839,304.079,585.525,307.785,582.574,310.737
                                                z M303.855,204.012c-57.103,0-103.773,46.67-103.773,103.773s46.67,103.773,103.773,103.773
                                                c57.103,0,103.773-46.67,103.773-103.773C408.383,250.683,361.713,204.012,303.855,204.012z M303.855,384.86
                                                c-42.278,0-76.32-34.111-76.32-76.32s34.111-76.388,76.32-76.388s76.32,34.111,76.32,76.32
                                                C380.999,349.995,346.133,384.86,303.855,384.86z"/>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                    {if $i == 4 || $i == 6 || $i == 10 }
                        </div>
                    {/if}
                {/for}
            </div>
        </div>
    </div>
{/block}