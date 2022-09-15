<?php

namespace App\Rules\Geolocation;

use Illuminate\Contracts\Validation\Rule;
use function is_numeric;
use function is_string;
use function preg_match;

/**
 * Determines whether the value is a valid longitude or not. A longitude should be a
 * number between -180 and 180
 *
 * @package App\Rules\Geolocation
 *
 * @author  Peter Cortez <innov.petercortez@gmail.com>
 */
class Longitude implements Rule
{
    /**
     * The regex pattern of a longitude that is between -180 and 180
     *
     * @const string
     */
    const PATTERN = '/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return (is_string($value) || is_numeric($value))
            && preg_match(self::PATTERN, $value) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be a number between -180 and 180.';
    }
}
