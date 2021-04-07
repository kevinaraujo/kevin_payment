<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DocumentRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $document = preg_replace('/\.|\-|\//', '', $value);
        $lengths = [11, 14];

        if (!in_array( strlen($document), $lengths)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'INVALID_DOCUMENT';
    }
}
