<?php

namespace Fondy;

use Fondy\Config\ConfigKeys;
use Fondy\Service\FONDY\RequestServiceInterface;
use Thelia\Model\Order;
use Thelia\Module\AbstractPaymentModule;

class Fondy extends AbstractPaymentModule
{
    protected static $defaultConfigValues = [
        ConfigKeys::GATEWAY_URL => 'https://api.fondy.eu/api/checkout/redirect/',
        ConfigKeys::TRANSACTION_VERSION => '1.0',
    ];

    public function pay(Order $order)
    {
        /** @var RequestServiceInterface $FONDYRequestService */
        $FONDYRequestService = $this->getContainer()->get('fondy.service.fondy.request');

        return $this->generateGatewayFormResponse(
            $order,
            $FONDYRequestService->getGatewayURL(),
            $FONDYRequestService->getRequestFields($order, $this->getRequest())
        );
    }

    public function isValidPayment()
    {
        return true;
    }

    public static function getConfigValue($variableName, $defaultValue = null, $valueLocale = null)
    {
        if ($defaultValue === null && isset(static::$defaultConfigValues[$variableName])) {
            $defaultValue = static::$defaultConfigValues[$variableName];
        }

        return parent::getConfigValue($variableName, $defaultValue, $valueLocale);
    }
}
