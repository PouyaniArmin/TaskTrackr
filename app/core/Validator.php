<?php

namespace App\Core;

use PhpOption\Option;

class Validator
{
    // Default validation error messages
    const DEFAULT_VALIDATION_ERRORS = [
        'required' => 'The %s is required',
        'email' => 'The %s is not a valid email address',
        'min' => 'The %s must have at least %s characters',
        'max' => 'The %s must have at most %s characters',
        'between' => 'The %s must have between %d and %d characters',
        'same' => 'The %s must match with %s',
        'alphanumeric' => 'The %s should have only letters and numbers',
        'secure' => 'The %s must have between 8 and 64 characters and contain at least one number, one upper case letter, one lower case letter and one special character'
    ];
    /** 
     * Validates the input data based on provided field rules.
     * Iterates over each field and applies all validation rules.
     * Collects any validation errors and returns them.
     *
     * @param array $data - The input data to be validated
     * @param array $fields - The rules for validating each field
     * @return array - Returns an array of validation error messages
     */
    public function validation(array $data, array $fields)
    {
        $errors = [];
        // Helper function to split strings by a separator and trim whitespace
        $split = fn($str, $separator) => array_map('trim', explode($separator, $str));
        // Loop through each field and its validation rules 
        foreach ($fields as $field => $option) {
            $rules = $split($option, '|'); // Split multiple rules by pipe (|)
            foreach ($rules as $rule) {
                $params = [];
                // Check if the rule has parameters (e.g., min:8, max:64)
                if ($split($rule, ':')) {
                    [$rule_name, $param_str] = $split($rule, ':');
                    $params = $split($param_str, ',');
                    // print_r(...$params);
                } else {
                    $rule_name = trim($rule); // No parameters, just rule name
                }
                // Build the function name dynamically (e.g., is_required)
                $fn = 'is_' . $rule_name;
                // Check if the rule function exists and is callable
                if (is_callable([$this, $fn])) {
                    $pass = $this->$fn($data, $field, ...$params); // Call the validation method
                    if (!$pass) {
                        // If validation fails, add error message to the errors array
                        $errors[] = sprintf(self::DEFAULT_VALIDATION_ERRORS[$rule_name], $field,...$params);
                    }
                }
            }
        }
        if (!empty($errors)) {
            SessionManager::set('errors', $errors);
        }
        
        return $errors; // Return all errors if there are any
    }
    /** 
     * Validates if the field is required (i.e., not empty or null).
     *
     * @param array $data - The input data
     * @param string $field - The field to check
     * @return bool - Returns true if the field is required and not empty, false otherwise
     */
    public function is_required(array $data, string $field): bool
    {
        return isset($data[$field]) && trim($data[$field]) !== '';
    }

    /** 
     * Validates if the field is a valid email address.
     *
     * @param array $data - The input data
     * @param string $field - The field to check
     * @return bool - Returns true if the field contains a valid email, false otherwise
     */
    public function is_email(array $data, string $field): bool
    {
        if (empty($data[$field])) {
            return true;
        }
        return filter_var($data[$field], FILTER_VALIDATE_EMAIL);
    }
    /** 
     * Validates if the field has a minimum length.
     *
     * @param array $data - The input data
     * @param string $field - The field to check
     * @param int $min - The minimum length required
     * @return bool - Returns true if the field length is greater than or equal to the minimum, false otherwise
     */
    public function is_min(array $data, string $field, int $min):bool
    {

        if (!isset($data[$field])) {
            return true;
        }
        return mb_strlen($data[$field]) >= $min;
    }
    /** 
     * Validates if the field has a maximum length.
     *
     * @param array $data - The input data
     * @param string $field - The field to check
     * @param int $max - The maximum length allowed
     * @return bool - Returns true if the field length is less than or equal to the maximum, false otherwise
     */
    public function is_max(array $data, string $field, int $max): bool
    {
        if (!isset($data[$field])) {
            return true;
        }
        return mb_strlen($data[$field]) <= intval($max);
    }
    /** 
     * Validates if the field length is between a minimum and maximum value.
     *
     * @param array $data - The input data
     * @param string $field - The field to check
     * @param int $min - The minimum length required
     * @param int $max - The maximum length allowed
     * @return bool - Returns true if the field length is within the specified range, false otherwise
     */
    public function is_between(array $data, string $field, int $min, int $max): bool
    {
        if (!isset($data[$field])) {
            return true;
        }
        $len = mb_strlen($data[$field]);
        return $len >= intval($min) && $len <= intval($max);
    }
    /** 
     * Validates if the field contains only alphanumeric characters.
     *
     * @param array $data - The input data
     * @param string $field - The field to check
     * @return bool - Returns true if the field contains only letters and numbers, false otherwise
     */
    public function is_alphanumeric(array $data, string $field): bool
    {
        if (!isset($data[$field])) {
            return true;
        }
        return ctype_alnum($data[$field]); // Check if the field contains only letters and numbers
    }
    /** 
     * Validates if the field matches another field (e.g., password confirmation).
     *
     * @param array $data - The input data
     * @param string $field - The field to check
     * @param string $other - The other field to compare with
     * @return bool - Returns true if the field matches the other, false otherwise
     */
    public function is_same(array $data, string $field, string $other): bool
    {
        if (isset($data[$field], $data[$other])) {
            return $data[$field] === $data[$other]; // Compare both fields
        }
        if (!isset($data[$field]) && !isset($data[$other])) {
            return true; // If both fields are not set, they are considered the same
        }
        return false; // If only one field is set, return false
    }
    /** 
     * Validates if the field is secure (meets password complexity rules).
     *
     * @param array $data - The input data
     * @param string $field - The field to check
     * @return bool - Returns true if the field matches the security pattern, false otherwise
     */
    public function is_secure(array $data, string $field): bool
    {
        if (!isset($data[$field])) {
            return true; // If the field is not set, skip validation
        }
        $pattern = "#.*^(?=.{8,64})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";
        return preg_match($pattern, $data[$field]); // Check if the password matches the complex pattern
    }
}
