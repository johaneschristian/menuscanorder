<?php

namespace App\Utils;

class Validator
{
    public static function validate($rules, $errorMessages, $data) {
        $validation = \Config\Services::validation();
        $validation->setRules($rules, $errorMessages);

        if (!$validation->run($data)) {
            return implode("\n", $validation->getErrors());

        } else {
            return TRUE; 
        }
    }
}