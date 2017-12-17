<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TuWenContent implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if (empty($value) || !is_string($value))
        {
            return false;
        }
        $value = json_decode($value, true);
        if (empty($value))
        {
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
        return ':attribute 非法或为空';
    }
}
