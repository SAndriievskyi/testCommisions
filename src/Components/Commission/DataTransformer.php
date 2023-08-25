<?php

namespace App\Components\Commission;

use App\Components\Commission\Exceptions\ValidationException;
use App\Enums\Currency;

class DataTransformer
{
    public function __construct(
        private DataValidator $validator = new DataValidator(),
    ) {}

    /**
     * @throws ValidationException
     */
    public function transformArrayToObject(array $data): DataObject
    {
        $this->validator->validate($data);

        return new DataObject(
            $data['bin'],
            $data['amount'],
            Currency::from($data['currency']),
        );
    }
}