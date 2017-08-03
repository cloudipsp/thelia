<?php

namespace Fondy\Config;

/**
 * Module configuration keys.
 */
class ConfigKeys
{
    /**
     * MERCHANT_ID.
     * @var int
     */
    const MERCHANT_ID = 'merchant_id';

    /**
     * SECRET_KEY.
     * @var string
     */
    const SECRET_KEY = 'secret_key';

    /**
     * Transaction version.
     * @var string
     */
    const TRANSACTION_VERSION = 'transaction_version';

    /**
     * Payment gateway URL.
     * @var string
     */
    const GATEWAY_URL = 'gateway_url';

    /**
     * The URL to use as the gateway callback.
     * @var string
     */
    const CALLBACK_URL = 'callback_url';
    /**
     * The URL to use as the gateway response.
     * @var string
     */
    const RESPONSE_URL = 'response_url';
    /**
     * Redirect or ...
     * @var string
     */
    const GATEWAY_TYPE = 'gateway_type';
    /**
     * preauth.
     * @var string
     */
    const PREAUTH = 'preauth';
    /**
     * currency of payment.
     * @var string
     */
    const CURRENCY = 'currency';
}
