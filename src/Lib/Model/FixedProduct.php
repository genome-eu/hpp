<?php

namespace Genome\Lib\Model;

/**
 * Class FixedProduct
 * @package Genome\Lib\Model
 */
class FixedProduct extends BaseProduct
{
    /**
     * @param string $productId
     * @param string $productName
     * @param float $amount
     * @param string $currency
     * @param float|null $discount
     * @param string|null $discountType
     * @param string|null $productDescription
     * @throws \Genome\Lib\Exception\GeneralGenomeException
     */
    public function __construct(
        string $productId,
        string $productName,
        float $amount,
        string $currency,
        float $discount = null,
        string $discountType = null,
        string $productDescription = null
    ) {
        parent::__construct(
            self::TYPE_FIXED,
            $productId,
            $productName,
            $currency,
            $amount,
            $discount,
            $discountType,
            $productDescription
        );
    }
}
