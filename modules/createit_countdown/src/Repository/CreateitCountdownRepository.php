<?php

namespace PrestaShop\Module\CreateitCountdown\Repository;

use Doctrine\ORM\EntityRepository;
use PrestaShop\Module\CreateitCountdown\Entity\CreateitCountdown;

class CreateitCountdownRepository extends EntityRepository
{
    /**
     * @param string $setting
     * @return object|null
     */
    public function findSetting(string $setting)
    {
        $countdownSettings = $this->findOneBy(['setting' => $setting]);

        return $countdownSettings;
    }
}