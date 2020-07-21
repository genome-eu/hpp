<?php

namespace Genome\Lib\Exception;

/**
 * General Genome exception
 *
 * Class GeneralGenomeException
 * @package Genome\Lib\Exception
 */
class GeneralGenomeException extends \Exception
{
    public function __construct(string $message = "", \Exception $previous = null, int $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
