<?php

use App\Core\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    private Validator $validator;
    private array $fields;
    public function setUp(): void
    {
        $this->validator = new Validator;
        $this->fields = [
            'firstname' => 'required, max:255',
            'lastname' => 'required, max: 255',
            'address' => 'required | min: 10, max:255',
            'zipcode' => 'required |between: 5,6',
            'username' => 'required | alphanumeric',
            'email' => 'required | email',
            'password' => 'required | secure',
            'password2' => 'required | same:password'
        ];
    }
    public function testValidData()
    {

        $validData = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'address' => '1234 Main St, Apt 101, Some City, Country',
            'zipcode' => '12345',
            'username' => 'johndoe123',
            'email' => 'john.doe@example.com',
            'password' => 'SecurePass123!',
            'password2' => 'SecurePass123!',
        ];
        $errors = $this->validator->validation($validData, $this->fields);
        $this->assertTrue(empty($errors), 'Validation failed for valid data');
    }

    public function testInvalidRequired()
    {
        $fields = [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required | min: 10, max:255',
            'zipcode' => 'required |between: 5,6',
            'username' => 'required | alphanumeric',
            'email' => 'required | email',
            'password' => 'required | secure',
            'password2' => 'required | same:password'
        ];
        $invalidData = [
            'firstname' => '',
            'lastname' => 'Doe',
            'address' => '1234 Main St, Apt 101, Some City, Country',
            'zipcode' => '12345',
            'username' => 'johndoe123',
            'email' => 'john.doe@example.com',
            'password' => 'SecurePass123!',
            'password2' => 'SecurePass123!',

        ];
        $errors = $this->validator->validation($invalidData, $fields);
        $this->assertTrue(!empty($errors), 'The firstname is required');
        $this->assertContains('The firstname is required', $errors, 'Validation error for firstname');
    }

    public function testInvalidEmail()
    {
        $fields = [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required | min: 10, max:255',
            'zipcode' => 'required |between: 5,6',
            'username' => 'required | alphanumeric',
            'email' => 'required | email',
            'password' => 'required | secure',
            'password2' => 'required | same:password'
        ];
        $invalidData = [
            'firstname' => 'Jon',
            'lastname' => 'Doe',
            'address' => '1234 Main St, Apt 101, Some City, Country',
            'zipcode' => '12345',
            'username' => 'johndoe123',
            'email' => 'invalid-email',
            'password' => 'SecurePass123!',
            'password2' => 'SecurePass123!',

        ];
        $errors = $this->validator->validation($invalidData, $fields);
        $this->assertTrue(!empty($errors), 'The email is not a valid email address');
        $this->assertContains('The email is not a valid email address', $errors, 'Validation error for email');
    }

    public function testInvalidMin()
    {
        $fields = [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required | min:10, max:255',
            'zipcode' => 'required | min:5',
            'username' => 'required',
            'email' => 'required | email',
            'password' => 'required |secure',
            'password2' => 'required |same:password'
        ];
        $invalidData = [
            'firstname' => 'Jon',
            'lastname' => 'Doe',
            'address' => '1234 Main St, Apt 101, Some City, Country',
            'zipcode' => '123',
            'username' => 'johndoe123',
            'email' => 'john.doe@example.com',
            'password' => 'SecurePass123!',
            'password2' => 'SecurePass123!'
        ];
        $errors = $this->validator->validation($invalidData, $fields);
        $this->assertTrue(!empty($errors), 'The zipcode must have at least 5 characters');
    }

    public function testInvalidMax()
    {
        $fields = [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required | min:10, max:255',
            'zipcode' => 'required | max:6',
            'username' => 'required',
            'email' => 'required | email',
            'password' => 'required |secure',
            'password2' => 'required |same:password'
        ];
        $invalidData = [
            'firstname' => 'Jon',
            'lastname' => 'Doe',
            'address' => '1234 Main St, Apt 101, Some City, Country',
            'zipcode' => '12345678',
            'username' => 'johndoe123',
            'email' => 'john.doe@example.com',
            'password' => 'SecurePass123!',
            'password2' => 'SecurePass123!'
        ];
        $errors = $this->validator->validation($invalidData, $fields);
        $this->assertTrue(!empty($errors), 'The zipcode must have at most 6 characters');
    }
    public function testInvalidBetween()
    {
        $fields = [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required | min:10, max:255',
            'zipcode' => 'required | between: 5,6',
            'username' => 'required',
            'email' => 'required | email',
            'password' => 'required |secure',
            'password2' => 'required |same:password'
        ];
        $invalidData = [
            'firstname' => 'Jon',
            'lastname' => 'Doe',
            'address' => '1234 Main St, Apt 101, Some City, Country',
            'zipcode' => '123',
            'username' => 'johndoe123',
            'email' => 'john.doe@example.com',
            'password' => 'SecurePass123!',
            'password2' => 'SecurePass123!'
        ];
        $errors = $this->validator->validation($invalidData, $fields);
        $this->assertTrue(!empty($errors), 'The zipcode must have between 5 and 6 characters');
    }
    public function testInvalidSame()
    {
        $fields = [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required | min:10, max:255',
            'zipcode' => 'required | between: 5,6',
            'username' => 'required',
            'email' => 'required | email',
            'password' => 'required ',
            'password2' => 'required |same:password'
        ];
        $invalidData = [
            'firstname' => 'Jon',
            'lastname' => 'Doe',
            'address' => '1234 Main St, Apt 101, Some City, Country',
            'zipcode' => '12356',
            'username' => 'johndoe123',
            'email' => 'john.doe@example.com',
            'password' => 'SecurePass123',
            'password2' => 'SecurePass123!'
        ];
        $errors = $this->validator->validation($invalidData, $fields);
        $this->assertTrue(!empty($errors), 'The confrimPassword must match with password');
    }

    public function testInvalidAlphanumeric()
    {
        $fields = [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required | min:10, max:255',
            'zipcode' => 'required ',
            'username' => 'required |alphanumeric',
            'email' => 'required | email',
            'password' => 'required ',
            'password2' => 'required |same:password'
        ];
        $invalidData = [
            'firstname' => 'Jon',
            'lastname' => 'Doe',
            'address' => '1234 Main St, Apt 101, Some City, Country',
            'zipcode' => '12356',
            'username' => 'johndoe_123',
            'email' => 'john.doe@example.com',
            'password' => 'SecurePass123!',
            'password2' => 'SecurePass123!'
        ];
        $errors = $this->validator->validation($invalidData, $fields);
        $this->assertTrue(!empty($errors), 'The username should have only letters and numbers');
    }


    public function testInvalidSecure()
    {
        $fields = [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required | min:10, max:255',
            'zipcode' => 'required',
            'username' => 'required |alphanumeric',
            'email' => 'required | email',
            'password' => 'required |secure',
            'password2' => 'required |same:password'
        ];
        $invalidData = [
            'firstname' => 'Jon',
            'lastname' => 'Doe',
            'address' => '1234 Main St, Apt 101, Some City, Country',
            'zipcode' => '12356',
            'username' => 'johndoe123',
            'email' => 'john.doe@example.com',
            'password' => '12345',
            'password2' => '12345'
        ];
        $errors = $this->validator->validation($invalidData, $fields);
        $this->assertTrue(!empty($errors), 'The password must have between 8 and 64 characters and contain at least one number, one upper case letter, one lower case letter and one special character');
    }

}
