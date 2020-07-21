<?php

namespace Genome\Lib\Util;

use Genome\Lib\Exception\GeneralGenomeException;

/**
 * Interface ClientInterface
 * @package Genome\Lib\Util
 */
interface ClientInterface
{
    /**
     * @param mixed[] $data
     * @return mixed[]
     * @throws GeneralGenomeException
     */
    public function send(array $data);
}
