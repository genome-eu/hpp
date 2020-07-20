<?php

namespace Genome;

use Genome\Lib\Component\ButtonBuilder;
use Genome\Lib\Component\RebillBuilder;
use Genome\Lib\Exception\GeneralGenomeException;

/**
 * Interface ScrineyInterface
 * @package Genome
 */
interface ScrineyInterface
{
    /**
     * Method build integration code of pay button
     *
     * @param string $userId User Id in your system
     * @return ButtonBuilder
     * @throws GeneralGenomeException
     */
    public function buildButton(string $userId): ButtonBuilder;

    /**
     * Method will return builder which allow to create and send rebill request
     *
     * @param string $billToken
     * @param string $userId
     * @return RebillBuilder
     * @throws GeneralGenomeException
     */
    public function createRebillRequest(string $billToken, string $userId): RebillBuilder;

    /**
     * @param string $transactionId
     * @param string $userId
     * @return array
     * @throws GeneralGenomeException
     */
    public function stopSubscription(string $transactionId, string $userId): array;

    /**
     * @param string $transactionId
     * @param float $amount Money amount to be refunded.
     * @param string $currencyCode Transaction currency iso code.
     * @return array
     * @throws GeneralGenomeException
     */
    public function refund(string $transactionId, float $amount, string $currencyCode): array;

    /**
     * Method for validate callback
     *
     * @param string $data callback json string data from Genome.
     * @param array $headers headers from response from Genome.
     * @return bool
     * @throws GeneralGenomeException
     */
    public function validateCallback(string $data, array $headers): bool;
}
