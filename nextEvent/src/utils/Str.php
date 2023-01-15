<?php

namespace utils;

/**
 * The helper is a class that permits to help the developer to make some common and conventional tasks.
 *
 * @author 22013393
 * @version 1.0
 */
class Str
{
    /**
     * The constructor of the helper.
     */
    private function __construct()
    {
    }

    /**
     *  Truncate a string to a given length if necessary, optionally splitting in the middle of a word, and
     * appending the $etc string or inserting $etc into the middle.
     *
     * @param string $string The string to truncate.
     * @param int $length The length of returned string, including ellipsis.
     * @param string $etc The string to append to the truncated string.
     * @param bool $breakWords Whether to break words when truncating.
     * @param bool $middle Whether to truncate in the middle of a word.
     * @return string The truncated string.
     */
    public static function truncate(string $string,
                                    int    $length = 80,
                                    string $etc = '...',
                                    bool   $break_words = false,
                                    bool   $middle = false): string
    {
        if ($length == 0) {
            return '';
        }

        if (mb_strlen($string) > $length) {
            $length -= min($length, mb_strlen($etc));
            if (!$break_words && !$middle) {
                $string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length + 1));
            }
            if (!$middle) {
                return mb_substr($string, 0, $length) . $etc;
            }
            return mb_substr($string, 0, $length / 2) . $etc . mb_substr($string, -$length / 2);
        } else {
            return $string;
        }
    }

    /**
     * Transform a string to a slug.
     *
     * @param string $string The string to transform.
     * @return string The slug.
     */
    public static function slugify(string $string): string
    {
        $string = strtolower($string);
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

    /**
     * Return a partial view of a text.
     * @param string $text The text.
     * @param int $length The length of the partial view.
     * @return string The partial view.
     */
    public static function partial(string $text, int $length = 100): string
    {
        if (mb_strlen($text) > $length) {
            return mb_substr($text, 0, $length) . "...";
        }
        return $text;
    }


}

