<?php

namespace App\Functions;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Functions
{
    public static function getValidatedData(array $data, array $rules): array
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
