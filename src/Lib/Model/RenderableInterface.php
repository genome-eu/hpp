<?php

namespace Genome\Lib\Model;

/**
 * Interface RenderableInterface
 * @package Genome\Lib\Model
 */
interface RenderableInterface
{
    /**
     * @return string
     */
    public function asString(): string;

    /**
     * @return void
     */
    public function display(): void;
}
