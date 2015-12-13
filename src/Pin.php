<?php

namespace PiPHP\GPIO;

use PiPHP\GPIO\FileSystem\FileSystemInterface;

final class Pin implements PinInterface
{
    // Paths
    const GPIO_PATH      = '/sys/class/gpio/';
    const GPIO_PREFIX    = 'gpio';

    // Files
    const GPIO_FILE_EXPORT   = 'export';
    const GPIO_FILE_UNEXPORT = 'unexport';

    // Pin files
    const GPIO_PIN_FILE_DIRECTION = 'direction';
    const GPIO_PIN_FILE_VALUE     = 'value';
    const GPIO_PIN_FILE_EDGE      = 'edge';

    private $fileSystem;
    private $number;

    /**
     * Constructor.
     * 
     * @param FileSystemInterface $fileSystem An object that provides file system access
     * @param int                 $number     The number of the pin
     */
    public function __construct(FileSystemInterface $fileSystem, $number)
    {
        $this->fileSystem = $fileSystem;
        $this->number = $number;
    }

    /**
     * {@inheritdoc}
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $this->writePinNumberToFile($this->getFile(self::GPIO_FILE_EXPORT));
    }

    /**
     * {@inheritdoc}
     */
    public function unexport()
    {
        $this->writePinNumberToFile($this->getFile(self::GPIO_FILE_UNEXPORT));
    }

    /**
     * {@inheritdoc}
     */
    public function getDirection()
    {
        $directionFile = $this->getPinFile(self::GPIO_PIN_FILE_DIRECTION);
        return $this->readFromFile($directionFile);
    }

    /**
     * {@inheritdoc}
     */
    public function setDirection($direction)
    {
        $directionFile = $this->getPinFile(self::GPIO_PIN_FILE_DIRECTION);
        $this->writeToFile($directionFile, $direction);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        $valueFile = $this->getPinFile(self::GPIO_PIN_FILE_VALUE);
        return $this->readFromFile($valueFile);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $valueFile = $this->getPinFile(self::GPIO_PIN_FILE_VALUE);
        $this->writeToFile($valueFile, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getEdge()
    {
        $edgeFile = $this->getPinFile(self::GPIO_PIN_FILE_EDGE);
        return $this->readFromFile($edgeFile);
    }

    /**
     * {@inheritdoc}
     */
    public function setEdge($edge)
    {
        $edgeFile = $this->getPinFile(self::GPIO_PIN_FILE_EDGE);
        $this->writeToFile($edgeFile, $edge);
    }

    /**
     * Get the path of the import or export file.
     * 
     * @param string $file The type of file (import/export)
     * 
     * @return string The file path
     */
    private function getFile($file)
    {
        return self::GPIO_PATH . $file;
    }

    /**
     * Get the path of a pin access file.
     * 
     * @param string $file The type of pin file (edge/value/direction)
     * 
     * @return string
     */
    private function getPinFile($file)
    {
        return self::GPIO_PATH . self::GPIO_PREFIX . $this->getNumber() . '/' . $file;
    }

    /**
     * Write the pin number to a file.
     * 
     * @param string $file The file to write to
     */
    private function writePinNumberToFile($file)
    {
        $this->writeToFile($file, $this->getNumber());
    }

    /**
     * Write a value to a file.
     * 
     * @param string $file  The file to write to
     * @param string $value The value to write
     */
    private function writeToFile($file, $value)
    {
        $stream = $this->fileSystem->open($file, "w");
        $stream->write($value);
        $stream->close();
    }

    /**
     * Read a value from a file.
     * 
     * @param string $file The file to read from
     * 
     * @return string The value read
     */
    private function readFromFile($file)
    {
        $stream = $this->fileSystem->open($file, "r");
        $result = $stream->read(1024);
        $stream->close();

        return trim($result);
    }
}