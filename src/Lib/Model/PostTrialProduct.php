<?php

namespace Genome\Lib\Model;

/**
 * Class PostTrialProduct
 * @package Genome\Lib\Model
 */
class PostTrialProduct extends BaseProduct
{
    /**
     * @param string $productId
     * @param string $productName
     * @param float $amount
     * @param string $currency
     * @param string $postTrialProductId Existing product id from Mportal
     * @param int $trialLength
     * @param string $trialPeriod
     * @param string|null $productDescription
     * @throws \Genome\Lib\Exception\GeneralGenomeException
     */
    public function __construct(
        string $productId,
        string $productName,
        float $amount,
        string $currency,
        string $postTrialProductId,
        int $trialLength,
        string $trialPeriod,
        string $productDescription = null
    ) {
        parent::__construct(
            self::TYPE_TRIAL,
            $productId,
            $productName,
            $currency,
            $amount,
            null,
            null,
            $productDescription,
            null,
            null,
            null,
            null,
            $postTrialProductId,
            $trialLength,
            $trialPeriod
        );
    }
}
