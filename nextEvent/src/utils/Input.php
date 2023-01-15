<?php

namespace utils;

/**
 *  The Input class is a class that permits to sanitize the input of the user.
 *
 * @author 22013393
 * @version 1.0
 */
class Input
{
    /**
     * The constructor of the input.
     */
    private function __construct()
    {
    }

    /**
     * Sanitize the input of the user.
     *
     * @param string $input The input to sanitize.
     * @return string The sanitized input.
     */
    public static function sanitize(string $input): string
    {
        return filter_var($input, FILTER_SANITIZE_STRING);
    }

    /**
     * Sanitize the input of the user.
     *
     * @param string $input The input to sanitize.
     * @return string The sanitized input.
     */
    public static function sanitizeEmail(string $input): string
    {
        return filter_var($input, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize the input of the user.
     *
     * @param string $input The input to sanitize.
     * @return string The sanitized input.
     */
    public static function sanitizeUrl(string $input): string
    {
        return filter_var($input, FILTER_SANITIZE_URL);
    }

    /**
     * Sanitize the input of the user.
     *
     * @param string $input The input to sanitize.
     * @return string The sanitized input.
     */
    public static function sanitizeInt(string $input): string
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Sanitize the input of the user.
     *
     * This methode f
     *
     * @param string $input The input to sanitize.
     * @return string The sanitized input.
     */
    public static function sanitizeFloat(string $input): string
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT);
    }

    /**
     * Sanitize the input of the user.
     *
     * This methode filter the input to remove all the tags.
     *
     * @param string $input The input to sanitize.
     * @return string The sanitized input.
     */
    public static function sanitizeSpecialChars(string $input): string
    {
        return filter_var($input, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    /**
     * Sanitize the input of the user.
     *
     * This method filters the input to remove all characters except letters, digits and $characters.
     *
     * @param string $input The input to sanitize.
     * @return string The sanitized input.
     */
    public static function sanitizeFullSpecialChars(string $input): string
    {
        // Trim the input.
        $input = trim($input);
        // Remove all the special characters.
        $input = filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // Remove all the spaces that are not between words.
        $input = preg_replace('/\s+/', ' ', $input);
        // Return the sanitized input.
        return $input;
    }


}
