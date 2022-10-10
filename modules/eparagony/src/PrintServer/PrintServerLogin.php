<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\PrintServer;

use Spark\EParagony\ConfigHelper;
use Spark\EParagony\Entity\EparagonyPrinterToken;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;

class PrintServerLogin
{
    private $em;
    private $config;

    public function __construct(EntityManagerInterface $em, ConfigHelper $configHelper)
    {
        $this->em = $em;
        $this->config = $configHelper::getSavedConfig();
    }

    public function getPrivileges($token)
    {
        #TODO Delete old tokens.
        $repo = $this->em->getRepository(EparagonyPrinterToken::class);
        $token = $repo->findOneBy(['token' => $token]);
        $privileges = null;
        if ($token) {
            assert($token instanceof EparagonyPrinterToken);
            $now = new DateTime();
            if ($now < $token->getValidTo()) {
                $privileges = $token->getPrivileges();
            }
        }

        return $privileges;
    }

    public function checkUsernameAndPassword($username, $password)
    {
        /* The password is in plain text. This is by design because it is for machine processing. */
        return $username === $this->config->printer_username && $password === $this->config->printer_password;
    }

    public function logIn($username, $password) : EparagonyPrinterToken
    {
        if (!$this->checkUsernameAndPassword($username, $password)) {
            throw new LogicException('Emergency credentials check failed.');
        }
        $token = new EparagonyPrinterToken();
        $now = new DateTime();
        $inHour = new DateTime('1 hour');
        $random = bin2hex(random_bytes(16));
        $token
            ->setToken($random)
            ->setCreated($now)
            ->setValidTo($inHour)
            ->setPrivileges(EparagonyPrinterToken::PRIVILEGE_ALL)
        ;
        $this->em->persist($token);
        $this->em->flush();

        return $token;
    }
}
