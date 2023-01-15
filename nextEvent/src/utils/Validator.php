<?php

namespace utils;

/**
 * Validator is a class that permits to validate data.
 *
 * The user can validate data with different methods.
 *
 * @author 22013393
 * @version 1.0
 */
class Validator
{
    /**
     * Check if the data is empty.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is empty, false otherwise.
     */
    public static function isEmpty($data): bool
    {
        return empty($data);
    }

    /**
     * Check if a string is empty.
     *
     * @param string $string The string to check.
     */
    public static function isStringEmpty(string $string): bool
    {
        // We trim the string
        $string = trim($string);
        // A string is empty if it is equal to an empty string
        return $string === '';
    }

    /**
     * Check if the data is a string.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is a string, false otherwise.
     */
    public static function isString($data): bool
    {
        return is_string($data);
    }

    /**
     * Check if the data is an integer.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is an integer, false otherwise.
     */
    public static function isInteger($data): bool
    {
        return is_int($data);
    }

    /**
     * Check if the data is a float.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is a float, false otherwise.
     */
    public static function isFloat($data): bool
    {
        return is_float($data);
    }

    /**
     * Check if the data is a boolean.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is a boolean, false otherwise.
     */
    public static function isBoolean($data): bool
    {
        return is_bool($data);
    }


    /**
     * Check if the data is an array.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is an array, false otherwise.
     */
    public static function isArray($data): bool
    {
        return is_array($data);
    }

    /**
     * Check if the data is an object.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is an object, false otherwise.
     */
    public static function isObject($data): bool
    {
        return is_object($data);
    }

    /**
     * Check if the data is a resource.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is a resource, false otherwise.
     */
    public static function isResource($data): bool
    {
        return is_resource($data);
    }

    /**
     * Check if the data is a valid email.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is a valid email, false otherwise.
     */
    public static function isEmail($data): bool
    {
        return filter_var($data, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check if the data is a valid URL.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is a valid URL, false otherwise.
     */
    public static function isUrl($data): bool
    {
        return filter_var($data, FILTER_VALIDATE_URL);
    }

    /**
     * Check if the data is a valid IP.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is a valid IP, false otherwise.
     */
    public static function isIp($data): bool
    {
        return filter_var($data, FILTER_VALIDATE_IP);
    }

    /**
     * Check if the data is a valid MAC address.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is a valid MAC address, false otherwise.
     */
    public static function isMac($data): bool
    {
        return filter_var($data, FILTER_VALIDATE_MAC);
    }

    /**
     * Check if the data is a valid date and not in the past.
     *
     * @param mixed $data The data to check.
     */
    public static function isDateNotInPast($data): bool
    {
        return self::isDate($data) && strtotime($data) >= strtotime(date('Y-m-d'));
    }

    /**
     * Check if the data is a valid date.
     *
     * @param mixed $data The data to check.
     */
    public static function isDate($data): bool
    {
        return strtotime($data) !== false;
    }

    /**
     * Check if the data is contained in the array.
     *
     * @param mixed $data The data to check.
     * @param array $array The array to check.
     * @return bool True if the data is contained in the array, false otherwise.
     */
    public static function isInArray($data, array $array): bool
    {
        return in_array($data, $array, true);
    }

    /**
     * Check if the date is in the past.
     * @param string The date to check.
     * @param $string The time to check.
     * @return bool True if the date is in the past, false otherwise.
     */
    public static function isTimeNotInPast(string $date, string $time)
    {
        return self::isTime($time) && strtotime($date . ' ' . $time) >= strtotime(date('Y-m-d H:i'));
    }

    /**
     * Check if the time is a valid time.
     *
     * @param mixed $data The data to check.
     * @return bool True if the data is a valid time, false otherwise.
     */
    public static function isTime($data): bool
    {
        return preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", $data);
    }

    /**
     * Check if the image has a valid extension.
     *
     * @param Image $image The image to check.
     * @param array $extensions The extensions to check.
     * @return bool True if the image has a valid extension, false otherwise.
     */
    public static function isImageExtensionValid(Image $image, array $extensions): bool
    {
        return in_array($image->getExtension(), $extensions, true);
    }

    /**
     * Check if the image has a valid size.
     *
     * @param Image $image The image to check.
     * @param int $maxSize The maximum size to check.
     * @return bool True if the image has a valid size, false otherwise.
     */
    public static function isImageSizeValid(Image $image, int $maxSize): bool
    {
        return $image->getSize() <= $maxSize;
    }

    /**
     * Check if the file are really an image.
     *
     * @param Image $image The image to check.
     * @return bool True if the file are really an image, false otherwise.
     */
    public static function isImage(Image $image): bool
    {
        return getimagesize($image->getTmpName()) !== false;
    }


}

