<?php

namespace utils;

use App\Router;

/**
 * Class that are responsible for the image upload.
 *
 * @author 22013393
 * @version 1.0
 */
class Image
{
    /**
     * The name of the file.
     * @var string
     */
    private string $name;

    /**
     * The type of the file.
     * @var string
     */
    private string $type;

    /**
     * The size of the file.
     * @var int
     */
    private int $size;

    /**
     * The temporary name of the file.
     * @var string
     */
    private string $tmpName;

    /**
     * The error code of the file.
     * @var int
     */
    private int $error;


    /**
     * The constructor of the image.
     *
     * @param string $name The name of the file.
     * @param string $type The type of the file.
     * @param int $size The size of the file.
     * @param string $tmpName The temporary name of the file.
     * @param int $error The error code of the file.
     */
    public function __construct(string $name, string $type, int $size, string $tmpName, int $error)
    {
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->tmpName = $tmpName;
        $this->error = $error;
    }

    /**
     * Get the upload directory.
     *
     * @return string The upload directory.
     */
    public static function getUploadDir(): string
    {
    }

    /**
     * Upload the image.
     *
     * @return string The path of the image.
     */
    public function upload(): string
    {
        // We get the extension of the file
        $extension = $this->getExtension();

        // We generate a random name for the file
        $randomName = hash('sha256', $this->name . time()) . '.' . $extension;

        // We move the file to the path
        $isUploaded = move_uploaded_file($this->tmpName, Router::getUploadDirectory() . "images/" . $randomName);

        // We return the result
        return $isUploaded ? $randomName : '';
    }

    /**
     * Get the extension of the file.
     *
     * @return string The extension of the file.
     */
    public function getExtension(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    /**
     * Get the name of the file.
     *
     * @return string The name of the file.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the type of the file.
     *
     * @return string The type of the file.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the size of the file.
     *
     * @return int The size of the file.
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Get the temporary name of the file.
     *
     * @return string The temporary name of the file.
     */
    public function getTmpName(): string
    {
        return $this->tmpName;
    }

    /**
     * Get the error code of the file.
     *
     * @return int The error code of the file.
     */
    public function getError(): int
    {
        return $this->error;
    }

}
