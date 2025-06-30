<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception;

use LogicException;

class UrlIsInvalidException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Url is invalid');
    }
}
