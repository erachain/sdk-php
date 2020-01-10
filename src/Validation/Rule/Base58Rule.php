<?php

namespace Erachain\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;

/** @noinspection PhpUnused */

class Base58Rule extends AbstractRule
{
    const MESSAGE = 'некорректный base58';

    public function validate($value, $valueIdentifier = null)
    {
        return (bool)preg_match('/^[a-zA-Z0-9]+$/', $value);
    }
}