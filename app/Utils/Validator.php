<?php

namespace App\Utils;

class Validator
{
    /**
     * Wrap codeigniter validator for error message standardization.
     *
     * @param array $rules The validation rules.
     * @param array $errorMessages The error messages.
     * @param array $data The input data to validate.
     * @return mixed Returns TRUE if validation passes, otherwise returns validation errors as a string.
     */
    public static function validate($rules, $errorMessages, $data)
    {
        // Get an instance of the validation service
        $validation = \Config\Services::validation();

        // Set validation rules and error messages
        $validation->setRules($rules, $errorMessages);

        // Run validation
        if (!$validation->run($data)) {
            // If validation fails, return validation errors as a string
            return implode("\n", $validation->getErrors());
            
        } else {
            // If validation passes, return TRUE
            return TRUE;
        }
    }
}
