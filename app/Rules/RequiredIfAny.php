<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredIfAny implements Rule
{
    protected $field;
    protected $values;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($field, ...$values)
    {
        $this->field = $field;
        $this->values = $values;
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
        $input = request()->input($this->field);
        return in_array($input, $this->values);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The field :attribute is required.';
    }
}
