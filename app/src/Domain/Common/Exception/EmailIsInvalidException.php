<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception;

use LogicException;

class EmailIsInvalidException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Email is invalid');
    }
}
