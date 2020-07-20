<?php

namespace Genome\Lib\Model;

use Genome\Lib\Exception\GeneralGenomeException;
use Genome\Lib\Util\Validator;

/**
 * Class Identity
 * @package Genome\Lib\Model
 */
class Identity implements IdentityInterface
{
    /** @var string */
    private $publicKey;

    /** @var string */
    private $privateKey;

    /**
     * @param string $publicKey
     * @param string $privateKey
     * @throws GeneralGenomeException
     */
    public function __construct(string $publicKey, string $privateKey)
    {
        $validator = new Validator();
        $this->publicKey = $validator->validateString('publicKey', $publicKey);
        $this->privateKey = $validator->validateString('privateKey', $privateKey);
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }
}
