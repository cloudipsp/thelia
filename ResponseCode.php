<?php

namespace Fondy;

/**
 * Fondy payment gateway response codes.
 */
class ResponseCode
{
    /**
     * Transaction approved.
     * @var int
     */
    const APPROVED = 'approved';

    /**
     * Transaction declined.
     * @var int
     */
    const DECLINED = 'declined';
    /**
     * Transaction proccesing.
     * @var int
     */
    const PROCESSING = 'processing';
}
