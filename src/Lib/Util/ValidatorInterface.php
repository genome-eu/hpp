<?php

namespace Genome\Lib\Util;

use Genome\Lib\Exception\GeneralGenomeException;

/**
 * Interface ValidatorInterface
 * @package Genome\Lib\Util
 */
interface ValidatorInterface
{
    /**
     * Method will return valid value or throw exception
     *
     * @param string $paramName
     * @param string $value
     * @param int $minLength
     * @param int|null $maxLength
     * @return string
     * @throws GeneralGenomeException
     */
    public function validateString(string $paramName, string $value, int $minLength = 1, int $maxLength = null): string;

    /**
     * @param string $paramName
     * @param float|int $value
     * @return float|int
     * @throws GeneralGenomeException
     */
    public function validateNumeric(string $paramName, $value);

    /**
     * @return string
     */
    public function getDefaultEncoding(): string;
}
