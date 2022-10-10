<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 *
 * @final since Symfony 3.3
 */
class SmartUpsellAdvancedContainer extends Container
{
    private $parameters = [];
    private $targetDirs = [];

    public function __construct()
    {
        $this->services = [];
        $this->methodMap = [
            'smartupselladvanced.clientbusinesslogicprovider.filter' => 'getSmartupselladvanced_Clientbusinesslogicprovider_FilterService',
            'smartupselladvanced.clientbusinesslogicprovider.link' => 'getSmartupselladvanced_Clientbusinesslogicprovider_LinkService',
            'smartupselladvanced.helper.cart' => 'getSmartupselladvanced_Helper_CartService',
            'smartupselladvanced.helper.price' => 'getSmartupselladvanced_Helper_PriceService',
            'smartupselladvanced.helper.specialoffer' => 'getSmartupselladvanced_Helper_SpecialofferService',
            'smartupselladvanced.helper.upsell' => 'getSmartupselladvanced_Helper_UpsellService',
        ];

        $this->aliases = [];
    }

    public function getRemovedIds()
    {
        return [
            'Psr\\Container\\ContainerInterface' => true,
            'Symfony\\Component\\DependencyInjection\\ContainerInterface' => true,
        ];
    }

    public function compile()
    {
        throw new LogicException('You cannot compile a dumped container that was already compiled.');
    }

    public function isCompiled()
    {
        return true;
    }

    public function isFrozen()
    {
        @trigger_error(sprintf('The %s() method is deprecated since Symfony 3.3 and will be removed in 4.0. Use the isCompiled() method instead.', __METHOD__), E_USER_DEPRECATED);

        return true;
    }

    /**
     * Gets the public 'smartupselladvanced.clientbusinesslogicprovider.filter' shared service.
     *
     * @return \Invertus\SmartUpsellAdvanced\ClientBusinessLogicProvider\SpecialOfferFilterProvider
     */
    protected function getSmartupselladvanced_Clientbusinesslogicprovider_FilterService()
    {
        return $this->services['smartupselladvanced.clientbusinesslogicprovider.filter'] = new \Invertus\SmartUpsellAdvanced\ClientBusinessLogicProvider\SpecialOfferFilterProvider();
    }

    /**
     * Gets the public 'smartupselladvanced.clientbusinesslogicprovider.link' shared service.
     *
     * @return \Invertus\SmartUpsellAdvanced\ClientBusinessLogicProvider\LinkProvider
     */
    protected function getSmartupselladvanced_Clientbusinesslogicprovider_LinkService()
    {
        return $this->services['smartupselladvanced.clientbusinesslogicprovider.link'] = new \Invertus\SmartUpsellAdvanced\ClientBusinessLogicProvider\LinkProvider();
    }

    /**
     * Gets the public 'smartupselladvanced.helper.cart' shared service.
     *
     * @return \Invertus\SmartUpsellAdvanced\Helper\CartHelper
     */
    protected function getSmartupselladvanced_Helper_CartService()
    {
        return $this->services['smartupselladvanced.helper.cart'] = new \Invertus\SmartUpsellAdvanced\Helper\CartHelper();
    }

    /**
     * Gets the public 'smartupselladvanced.helper.price' shared service.
     *
     * @return \Invertus\SmartUpsellAdvanced\Helper\PriceHelper
     */
    protected function getSmartupselladvanced_Helper_PriceService()
    {
        return $this->services['smartupselladvanced.helper.price'] = new \Invertus\SmartUpsellAdvanced\Helper\PriceHelper();
    }

    /**
     * Gets the public 'smartupselladvanced.helper.specialoffer' shared service.
     *
     * @return \Invertus\SmartUpsellAdvanced\Helper\SpecialOfferHelper
     */
    protected function getSmartupselladvanced_Helper_SpecialofferService()
    {
        return $this->services['smartupselladvanced.helper.specialoffer'] = new \Invertus\SmartUpsellAdvanced\Helper\SpecialOfferHelper(${($_ = isset($this->services['smartupselladvanced.clientbusinesslogicprovider.link']) ? $this->services['smartupselladvanced.clientbusinesslogicprovider.link'] : ($this->services['smartupselladvanced.clientbusinesslogicprovider.link'] = new \Invertus\SmartUpsellAdvanced\ClientBusinessLogicProvider\LinkProvider())) && false ?: '_'}, ${($_ = isset($this->services['smartupselladvanced.helper.price']) ? $this->services['smartupselladvanced.helper.price'] : ($this->services['smartupselladvanced.helper.price'] = new \Invertus\SmartUpsellAdvanced\Helper\PriceHelper())) && false ?: '_'});
    }

    /**
     * Gets the public 'smartupselladvanced.helper.upsell' shared service.
     *
     * @return \Invertus\SmartUpsellAdvanced\Helper\UpsellHelper
     */
    protected function getSmartupselladvanced_Helper_UpsellService()
    {
        return $this->services['smartupselladvanced.helper.upsell'] = new \Invertus\SmartUpsellAdvanced\Helper\UpsellHelper(${($_ = isset($this->services['smartupselladvanced.helper.price']) ? $this->services['smartupselladvanced.helper.price'] : ($this->services['smartupselladvanced.helper.price'] = new \Invertus\SmartUpsellAdvanced\Helper\PriceHelper())) && false ?: '_'}, ${($_ = isset($this->services['smartupselladvanced.clientbusinesslogicprovider.link']) ? $this->services['smartupselladvanced.clientbusinesslogicprovider.link'] : ($this->services['smartupselladvanced.clientbusinesslogicprovider.link'] = new \Invertus\SmartUpsellAdvanced\ClientBusinessLogicProvider\LinkProvider())) && false ?: '_'}, ${($_ = isset($this->services['smartupselladvanced.helper.specialoffer']) ? $this->services['smartupselladvanced.helper.specialoffer'] : $this->getSmartupselladvanced_Helper_SpecialofferService()) && false ?: '_'});
    }
}
