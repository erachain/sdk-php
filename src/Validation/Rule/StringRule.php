<?php

namespace Erachain\Validation\Rule;

use Erachain\Helpers\Error;
use Sirius\Validation\Rule\AbstractRule;

/** @noinspection PhpUnused */

class StringRule extends AbstractRule
{
    const MESSAGE = Error::STRING;

    public function validate($value, $valueIdentifier = null)
    {
        return (bool)is_string($value);
    }
}