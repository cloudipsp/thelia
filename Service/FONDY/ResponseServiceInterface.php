<?php

namespace Fondy\Service\FONDY;

use Thelia\Model\Order;

/**
 * Service to process FONDY (Server Integration Method) gateway responses.
 */
interface ResponseServiceInterface
{
    /**
     * Authenticate a gateway response from the hash value.
     * @param array $response Response fields.
     * @return bool Whether the response is valid.
     */
    public function isResponseSignatureValid(array $response);

    /**
     * Change the order status depending on the gateway response.
     * @param array $response Response fields.
     * @param Order $order Order to process.
     * @return bool Whether the order was paid.
     */
    public function payOrderFromResponse(array $response, Order $order);
}
