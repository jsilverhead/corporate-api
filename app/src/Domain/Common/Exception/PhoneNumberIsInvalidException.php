<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception;

use LogicException;

class PhoneNumberIsInvalidException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Phone number is invalid');
    }
}
