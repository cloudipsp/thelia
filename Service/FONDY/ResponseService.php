<?php

namespace Fondy\Service\FONDY;

use Fondy\Fondy;
use Fondy\Config\ConfigKeys;
use Fondy\ResponseCode;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\Order;
use Thelia\Model\OrderQuery;
use Thelia\Model\OrderStatus;
use Thelia\Model\OrderStatusQuery;

/**
 * Implementation of the FONDY (Server Integration Method) gateway response processing.
 */
class ResponseService implements ResponseServiceInterface
{
    /**
     * Event dispatcher.
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher Event dispatcher.
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function isResponseSignatureValid(array $response)
    {
        if (Fondy::getConfigValue(ConfigKeys::MERCHANT_ID) != $response['merchant_id']) {
            return 'An error has occurred during payment. Merchant data is incorrect.';
        }

        $responseSignature = $response['signature'];
        if (isset($response['response_signature_string'])){
            unset($response['response_signature_string']);
        }
        if (isset($response['signature'])){
            unset($response['signature']);
        }
        if ($this->getSignature(Fondy::getConfigValue(ConfigKeys::MERCHANT_ID), Fondy::getConfigValue(ConfigKeys::SECRET_KEY), $response) != $responseSignature) {
            return 'An error has occurred during payment. Signature is not valid.';
        }
        return true;
    }

    public function payOrderFromResponse(array $response, Order $order)
    {
        $orderEvent = new OrderEvent($order);

        $responseCode = $response['order_status'];
        switch ($responseCode) {
            case ResponseCode::APPROVED:
                $orderStatusPaid = OrderStatusQuery::create()->findOneByCode(OrderStatus::CODE_PAID);
                $orderEvent->setStatus($orderStatusPaid->getId());
                $this->eventDispatcher->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $orderEvent);
                return true;
            case ResponseCode::DECLINED:
                $orderStatusPaid = OrderStatusQuery::create()->findOneByCode(OrderStatus::CODE_CANCELED);
                $orderEvent->setStatus($orderStatusPaid->getId());
                $this->eventDispatcher->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $orderEvent);
                return true;
            case ResponseCode::PROCESSING:
                $orderStatusPaid = OrderStatusQuery::create()->findOneByCode(OrderStatus::CODE_PROCESSING);
                $orderEvent->setStatus($orderStatusPaid->getId());
                $this->eventDispatcher->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $orderEvent);
                return true;
            default:
                return false;
        }
    }

    protected function getSignature($merchant_id, $password , array &$response)
    {
        $params['merchant_id'] = $merchant_id;
        $params = array_filter($response,'strlen');
        ksort($params);
        $params = array_values($params);
        array_unshift( $params , $password );
        $params = join('|',$params);
        return(sha1($params));
    }
}
