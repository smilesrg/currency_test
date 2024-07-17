<?php declare(strict_types=1);

namespace App\Service\Currency\Synchronizer\Exception;

use RuntimeException;

class SynchronizerException extends RuntimeException
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct('Error happened during rates synchronization', 0, $previous);
    }
}
