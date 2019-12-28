<?php
declare(strict_types=1);

namespace Artica\Exception\IOException;

use Exception;

/**
 * Class EntityException
 *
 * @package Artica\Exception\I
 */
class IOException extends \RuntimeException
{
    private $path;

    public function __construct($message, $path = null, $code = 0, Exception $previous = null)
    {
        $this->path = $path;

        parent::__construct($message, $code, $previous);
    }

    public function getPath()
    {
        return $this->path;
    }
}