<?php

namespace Genome;

use Genome\Lib\Component\ButtonBuilder;
use Genome\Lib\Component\CancelPostTrialBuilder;
use Genome\Lib\Component\RebillBuilder;
use Genome\Lib\Component\RefundBuilder;
use Genome\Lib\Component\StopSubscriptionBuilder;
use Genome\Lib\Exception\GeneralGenomeException;
use Genome\Lib\Model\Identity;
use Genome\Lib\Model\IdentityInterface;
use Genome\Lib\Util\SignatureHelper;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class Scriney
 * @package Genome
 */
class Scriney implements ScrineyInterface
{
    /** @var string */
    private $hostBase;

    /** @var LoggerInterface */
    private $logger;

    /** @var IdentityInterface */
    private $identity;

    /**
     * @param string $publicKey Available in your Mportal
     * @param string $privateKey Available in your Mportal
     * @param LoggerInterface|null $logger Any PSR-3 logger
     * @param string $hostBase
     * @throws GeneralGenomeException
     */
    public function __construct(
        $publicKey,
        $privateKey,
        LoggerInterface $logger = null,
        $hostBase = 'https://hpp-service.genome.eu/'
    ) {
        $this->logger = is_null($logger) ? new NullLogger() : $logger;
        $this->hostBase = $hostBase;

        try {
            $this->identity = new Identity($publicKey, $privateKey);
        } catch (GeneralGenomeException $e) {
            $this->logger->error(
                'Wrong init param',
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralGenomeException($ex->getMessage(), $ex);
        }

        $this->logger->info(
            'Scriney object successfully built',
            []
        );
    }

    /**
     * @param string $billToken
     * @param string $userId
     * @return RebillBuilder
     * @throws GeneralGenomeException
     */
    public function createRebillRequest(string $billToken, string $userId): RebillBuilder
    {
        try {
            return new RebillBuilder($this->identity, $billToken, $userId, $this->logger, $this->hostBase);
        } catch (GeneralGenomeException $e) {
            $this->logger->error(
                "Can't init rebill builder",
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Rebill builder initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralGenomeException($ex->getMessage(), $ex);
        }
    }

    /**
     * Method builds integration code of pay button
     *
     * @param string $userId User Id in your system
     * @return ButtonBuilder
     * @throws GeneralGenomeException
     */
    public function buildButton(string $userId): ButtonBuilder
    {
        try {
            return new ButtonBuilder($this->identity, $userId, $this->logger, $this->hostBase);
        } catch (GeneralGenomeException $e) {
            $this->logger->error(
                "Can't init button builder",
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Page builder initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralGenomeException($ex->getMessage(), $ex);
        }
    }

    /**
     * Method stop subscription
     *
     * @param string $transactionId
     * @param string $userId
     * @return array
     * @throws GeneralGenomeException
     */
    public function stopSubscription(string $transactionId, string $userId): array
    {
        try {
            $subscriptionBuilder = new StopSubscriptionBuilder(
                $this->identity,
                $userId,
                $transactionId,
                $this->logger,
                $this->hostBase
            );

            return $subscriptionBuilder->send();
        } catch (GeneralGenomeException $e) {
            $this->logger->error(
                "Can't init stop subscription builder",
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Stop subscription builder initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralGenomeException($ex->getMessage(), $ex);
        }
    }

    /**
     * Method for full/partial refund of transaction.
     *
     * @param string $transactionId
     * @param float $amount Money amount to be refunded.
     * @param string $currencyCode Transaction currency iso code.
     * @return array
     * @throws GeneralGenomeException
     */
    public function refund(string $transactionId, float $amount, string $currencyCode): array
    {
        try {
            $refundBuilder = new RefundBuilder(
                $this->identity,
                $transactionId,
                $this->logger,
                $this->hostBase
            );

            return $refundBuilder->send($amount, $currencyCode);
        } catch (GeneralGenomeException $e) {
            $this->logger->error(
                "Can't init refund builder",
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Refund builder initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralGenomeException($ex->getMessage(), $ex);
        }
    }

    /**
     * Method for validate api result
     *
     * @param array $data result received from Genome API
     * @return bool
     * @throws GeneralGenomeException
     */
    public function validateApiResult(array $data): bool
    {
        try {
            $signatureHelper = new SignatureHelper();
            $checkSum = null;
            $callbackData = [];
            foreach ($data as $k => $v) {
                if ($k !== 'checkSum') {
                    $callbackData[$k] = $v;
                } else {
                    $checkSum = $v;
                }
            }

            if (is_null($checkSum)) {
                $this->logger->error(
                    'checkSum field is required',
                    []
                );

                return false;
            }

            if ($checkSum !== $signatureHelper->generateForArray($callbackData, $this->identity->getPrivateKey())) {
                $this->logger->error(
                    'Checksum validation failure',
                    []
                );

                return false;
            }

            $this->logger->info(
                'Checksum is valid',
                []
            );

            return true;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Checksum validation failure',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralGenomeException($ex->getMessage(), $ex);
        }
    }

    /**
     * Validate if callback data is valid.
     *
     * @param string $data Json data string.
     * @param array $headers Response headers.
     * @return bool
     * @throws GeneralGenomeException
     */
    public function validateCallback(string $data, array $headers): bool
    {
        try {
            $checkSum = $headers['X-Signature'] ?? null;
            $signatureHelper = new SignatureHelper();

            if (is_null($checkSum)) {
                $this->logger->error(
                    'Checksum attribute is required',
                    []
                );

                return false;
            }

            if ($checkSum !== $signatureHelper->generateForString($data, $this->identity->getPrivateKey())) {
                $this->logger->error(
                    'Checksum validation failure',
                    []
                );

                return false;
            }

            $this->logger->info(
                'Checksum is valid',
                []
            );

            return true;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Checksum validation failure',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralGenomeException($ex->getMessage(), $ex);
        }
    }

    /**
     * Method cancel post trial
     *
     * @param string $transactionId
     * @return array
     * @throws GeneralGenomeException
     */
    public function cancelPostTrial(string $transactionId): array
    {
        try {
            $builder = new CancelPostTrialBuilder(
                $this->identity,
                $transactionId,
                $this->logger,
                $this->hostBase
            );

            return $builder->send();
        } catch (GeneralGenomeException $e) {
            $this->logger->error(
                "Can't init cancel post trial builder",
                [
                    'exception' => $e,
                ]
            );

            throw $e;
        } catch (\Exception $ex) {
            $this->logger->error(
                'Cancel post trial builder initialization failed',
                [
                    'exception' => $ex,
                ]
            );

            throw new GeneralGenomeException($ex->getMessage(), $ex);
        }
    }
}
