<?php

namespace Fondy\Service\FONDY;

use Fondy\Fondy;
use Fondy\Config\ConfigKeys;
use Fondy\Config\GatewayFondyType;
use Symfony\Component\Routing\RouterInterface;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\Customer;
use Thelia\Model\Order;
use Thelia\Model\OrderAddress;
use Thelia\Tools\URL;

/**
 * Implementation of the FONDY (Server Integration Method) payment form request builder.
 */
class RequestService implements RequestServiceInterface
{
    /**
     * Router for this module.
     * @var RouterInterface
     */
    protected $moduleRouter;
    /**
     * URL tools.
     * @var URL
     */
    protected $URLTools;

    /**
     * @param RouterInterface $moduleRouter Router for this module.
     * @param URL $URLTools URL tools.
     */
    public function __construct(
        RouterInterface $moduleRouter,
        URL $URLTools
    )
    {
        $this->moduleRouter = $moduleRouter;
        $this->URLTools = $URLTools;
    }

    public function getGatewayURL()
    {
        return Fondy::getConfigValue(ConfigKeys::GATEWAY_URL);
    }

    public function getCallbackURL()
    {
        $callbackURL = Fondy::getConfigValue(ConfigKeys::CALLBACK_URL);
        if (empty($callbackURL)) {
            $callbackURL = $this->URLTools->absoluteUrl(
                $this->moduleRouter->generate('fondy.front.gateway.callback')
            );
        }

        return $callbackURL;
    }

    public function getResponseURL()
    {
        $ResponseURL = Fondy::getConfigValue(ConfigKeys::RESPONSE_URL);
        if (empty($ResponseURL)) {
            $ResponseURL = $this->URLTools->absoluteUrl(
                $this->moduleRouter->generate('fondy.front.gateway.callback')
            );
        }

        return $ResponseURL;
    }

    public function getRequestFields(Order $order, Request $httpRequest)
    {
        $request = [];

        $this->addBaseFields($request, $order);

        $customer = $order->getCustomer();
        $this->addCustomerFields($request, $customer);
        
        if(Fondy::getConfigValue(ConfigKeys::PREAUTH) === true){
            $this->addPreauthField($request);
        }
        switch (Fondy::getConfigValue(ConfigKeys::GATEWAY_TYPE)) {
            case GatewayFondyType::RELAY_RESPONSE:
                $this->addRelayResponseFields($request);
                break;
            default:
                $this->addRelayResponseFields($request);
                break;
        }

        $this->addSignatureFields(Fondy::getConfigValue(ConfigKeys::MERCHANT_ID), Fondy::getConfigValue(ConfigKeys::SECRET_KEY), $request);

        return $request;
    }

    protected function addBaseFields(array &$request, Order $order)
    {
        $request['order_id'] = $order->getId() . '#' . time();
        $request['merchant_id'] = Fondy::getConfigValue(ConfigKeys::MERCHANT_ID);
        $request['order_desc'] = '№' . $order->getId();
        $request['amount'] = round($order->getTotalAmount() * 100);
        $request['version'] = Fondy::getConfigValue(ConfigKeys::TRANSACTION_VERSION);
        $request['currency'] = Fondy::getConfigValue(ConfigKeys::CURRENCY) ? Fondy::getConfigValue(ConfigKeys::CURRENCY) : $order->getCurrency()->getCode();
    }

    protected function addPreauthField(array &$request)
    {
        $request['preauth'] = 'Y';
    }

    protected function addCustomerFields(array &$request, Customer $customer)
    {
        $request['sender_email'] = $customer->getEmail();
    }

    protected function addRelayResponseFields(array &$request)
    {
        $request['server_callback_url'] = $this->getCallbackURL();
        $request['response_url'] = $this->getResponseURL();
    }

    protected function addSignatureFields($merchant_id, $password, array &$request)
    {
        $params['merchant_id'] = $merchant_id;
        $params = array_filter($request, 'strlen');
        ksort($params);
        $params = array_values($params);
        array_unshift($params, $password);
        $params = join('|', $params);
        $request['signature'] = (sha1($params));
    }

}
