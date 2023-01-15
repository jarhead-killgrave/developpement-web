<?php

namespace model\user;

use Exception;
use utils\Validator;

class UserBuilder
{
    /**
     * The name of the user.
     *
     * @var string
     */
    public static string $NAME_REF = "name";

    /**
     * Login of the user.
     *
     * @var string
     */
    public static string $LOGIN_REF = "login";

    /**
     * Password of the user.
     *
     * @var string
     */
    public static string $PASSWORD_REF = "password";

    /**
     * The confirmation of the password.
     *
     * @var string
     */
    public static string $PASSWORD_CONFIRM_REF = "passwordConfirm";

    /**
     * The data of the user.
     *
     * @var array
     */
    private array $data;

    /**
     * The errors of the user.
     *
     * @var array
     */
    private array $errors;


    /**
     * The constructor of the user builder.
     *
     * @param array $data The data of the user.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->errors = [];
    }

    /**
     * Get data.
     *
     * @param string $key The key of the data.
     * @return string The data.
     */
    public function getData(string $key): string
    {
        return $this->data[$key] ?? "";
    }

    /**
     * Return the errors of the user.
     *
     * @return array The errors of the user.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Return the data of the user.
     *
     * @return array The data of the user.
     */
    public function getDatas(): array
    {
        return $this->data;
    }

    /**
     * Return the user.
     *
     * @return User The user.
     * @throws Exception If the user is not valid.
     */
    public function getUser(): User
    {
        // If we have all keys then we can create the user
        if (count(array_diff(self::getNeededKeys(), array_keys($this->data))) == 0) {
            return new User($this->data[self::$NAME_REF], $this->data[self::$LOGIN_REF], $this->data[self::$PASSWORD_REF], 'user');
        }
        // If we don't have all keys then we can't create the user
        throw new Exception("The user can't be created because we don't have all keys.");
    }

    /**
     * Return the needed keys of the user.
     *
     * @return array The needed keys of the user.
     */
    public static function getNeededKeys(): array
    {
        return [
            self::$NAME_REF,
            self::$LOGIN_REF,
            self::$PASSWORD_REF,
            self::$PASSWORD_CONFIRM_REF
        ];
    }

    /**
     * Check if the user is valid.
     *
     * @return bool True if the user is valid, false otherwise.
     */
    public function isValid(): bool
    {
        // Check if the user is valid
        $this->checkName();
        $this->checkLogin();
        $this->checkPassword();

        // Return if the user is valid
        return count($this->errors) == 0;
    }

    /**
     * Check if the name of the user is valid.
     */
    private function checkName(): void
    {
        // Check if the name is not empty
        if (Validator::isStringEmpty($this->data[self::$NAME_REF] ?? '')) {
            $this->errors[self::$NAME_REF] = "The name can't be empty.";
        } // Check if the name is not too long
        else if (strlen($this->data[self::$NAME_REF]) > 255) {
            $this->errors[self::$NAME_REF] = "The name can't be longer than 255 characters.";
        } // Check if the name is not too short
        else if (strlen($this->data[self::$NAME_REF]) < 3) {
            $this->errors[self::$NAME_REF] = "The name can't be shorter than 3 characters.";
        } // Check if the name contains only letters
        else if (!preg_match("/^[a-zA-Z ]*$/", $this->data[self::$NAME_REF])) {
            $this->errors[self::$NAME_REF] = "The name can only contain letters.";
        }

    }

    /**
     * Check if the login of the user is valid.
     */
    private function checkLogin(): void
    {
        // Check if the login is not empty
        if (Validator::isStringEmpty($this->data[self::$LOGIN_REF] ?? '')) {
            $this->errors[self::$LOGIN_REF] = "The login can't be empty.";
        } // Check if the login is not too long
        else if (strlen($this->data[self::$LOGIN_REF]) > 255) {
            $this->errors[self::$LOGIN_REF] = "The login can't be longer than 255 characters.";
        } // Check if the login is not too short
        else if (strlen($this->data[self::$LOGIN_REF]) < 3) {
            $this->errors[self::$LOGIN_REF] = "The login can't be shorter than 3 characters.";
        }

    }

    /**
     * Check if the password of the user is valid.
     */
    private function checkPassword(): void
    {
        // Check if the password is not empty
        if (Validator::isStringEmpty($this->data[self::$PASSWORD_REF] ?? '')) {
            $this->errors[self::$PASSWORD_REF] = "The password can't be empty.";
        } // Check if the password confirmation is not empty
        else if (Validator::isStringEmpty($this->data[self::$PASSWORD_CONFIRM_REF] ?? '')) {
            $this->errors[self::$PASSWORD_CONFIRM_REF] = "The password confirmation can't be empty.";
        } // Check if the password confirmation is the same as the password
        else if ($this->data[self::$PASSWORD_REF] !== $this->data[self::$PASSWORD_CONFIRM_REF]) {
            $this->errors[self::$PASSWORD_CONFIRM_REF] = "The password confirmation is not the same as the password.";
        } // Check if the password is not too long
        else if (strlen($this->data[self::$PASSWORD_REF]) > 255) {
            $this->errors[self::$PASSWORD_REF] = "The password can't be longer than 255 characters.";
        } // Check if the password is not too short(for a security reason minimum is 8)
        else if (strlen($this->data[self::$PASSWORD_REF]) < 8) {
            $this->errors[self::$PASSWORD_REF] = "The password can't be shorter than 8 characters.";
        } // Check if the password contains at least one number
        else if (!preg_match("/[0-9]/", $this->data[self::$PASSWORD_REF])) {
            $this->errors[self::$PASSWORD_REF] = "The password must contain at least one number.";
        } // Check if the password contains at least one letter
        else if (!preg_match("/[a-zA-Z]/", $this->data[self::$PASSWORD_REF])) {
            $this->errors[self::$PASSWORD_REF] = "The password must contain at least one letter.";
        } // Check if the password contains at least one special character
        else if (!preg_match("/[^a-zA-Z0-9]/", $this->data[self::$PASSWORD_REF])) {
            $this->errors[self::$PASSWORD_REF] = "The password must contain at least one special character.";
        }
    }

    /**
     * Build the user from the data.
     */
    public function buildUser(): User
    {
        // Check that we have all the data
        if (array_key_exists(self::$NAME_REF, $this->data) && array_key_exists(self::$LOGIN_REF, $this->data) && array_key_exists(self::$PASSWORD_REF, $this->data)) {
            // Create the user
            return new User($this->data[self::$NAME_REF], $this->data[self::$LOGIN_REF], password_hash($this->data[self::$PASSWORD_REF], PASSWORD_DEFAULT));
        }

        // If we don't have all the data
        throw new Exception("The data is not valid.");
    }

    /**
     * Set errors.
     *
     * @param string $key The key of the error.
     * @param string $value The value of the error.
     */
    public function setError(string $key, string $value): void
    {
        $this->errors[$key] = $value;
    }

}
