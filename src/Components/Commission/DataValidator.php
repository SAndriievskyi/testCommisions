<?php

namespace App\Components\Commission;

use App\Components\Commission\Exceptions\ValidationException;
use App\Enums\Currency;

class DataValidator
{
    /**
     * here we can use framework Validators
     * @throws ValidationException
     */
    public function validate(array $data): bool
    {
        if (!isset($data['bin'], $data['amount'], $data['currency'])) {
            throw new ValidationException('Required parameter not exist!');
        }

        if (!is_int($data['bin']) && mb_strlen($data['bin']) > 8) {
            throw new ValidationException('Incorrect bin!');
        }

        if (!is_numeric($data['amount'])) {
            throw new ValidationException('Incorrect amount!');
        }

        if (!is_string($data['currency']) && !Currency::tryFrom($data['currency'])) {
            throw new ValidationException('Incorrect currency!');
        }

        return true;
    }
}