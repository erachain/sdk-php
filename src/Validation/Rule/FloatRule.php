<?php

namespace Erachain\Validation\Rule;

use Erachain\Helpers\Error;
use Sirius\Validation\Rule\AbstractRule;

/** @noinspection PhpUnused */

class FloatRule extends AbstractRule
{
    const MESSAGE = Error::FLOAT;

    public function validate($value, $valueIdentifier = null)
    {
        return (bool)preg_match('/^\d*\.\d*$/i', $value);
    }
}