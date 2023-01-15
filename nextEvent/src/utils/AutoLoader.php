<?php

namespace utils;

/**
 * A class loader is an object that permits to load classes.
 * It is used to load classes from the src directory.
 *
 * @author 22013393
 */
class AutoLoader
{

    /**
     *  Save the autoload function.
     *
     * @return void
     */
    public static function init(): void
    {
        spl_autoload_register([__CLASS__, 'load']);
    }

    /**
     * Load a class.
     *
     * @param string $class The name of the class to load.
     * @return void
     */
    public static function load(string $class): void
    {
        // Change the namespace to the directory
        $class = str_replace('\\', '/', $class);
        // If the namespace is App we are in the src directory
        if (strpos($class, 'App') === 0) {
            $class = substr($class, 4);
        }
        $class .= '.php';
        // Load the class
        //var_dump($class);
        require_once $class;

    }

    /**
     * Check if a class exists.
     *
     * @param string $class The name of the class to check.
     * @return bool True if the class exists, false otherwise.
     */
    public static function classExists(string $class): bool
    {
        $class = str_replace('\\', '/', $class);
        $class = 'src/' . $class;
        $class .= '.php';
        return file_exists($class);
    }

}
