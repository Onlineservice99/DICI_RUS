<?php
namespace Meven\Helper;

class MException extends \Exception
{
    private $exceptions = null;
    private $fileDebug = '/../debug.txt';
    private static $instance = null;

    public function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param string $message
     * @param int $code
     * @param string $file
     * @param int $line
     * @param \Exception|null $previous
     */
    public function set($message = "", $code = 0, $file = "", $line = 0, \Exception $previous = null)
    {
        $this->exceptions[] = new \Exception($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        $result = [];

        foreach ($this->exceptions as $exc) {
            $result[] = $exc->getMessage();
        }

        return $result;
    }

    /**
     * save all exceptions messages in file
     */
    public function saveMessagesIntoFile(): void
    {
        foreach ($this->exceptions as $exc) {
            $mess = "Error: ".$exc->getMessage().
                "\r\nDateTime: ".\date('d.m.Y H:i:s').
                "\r\nCode: ".$exc->getCode().
                "\r\nFile: ".$exc->getFile().
                "\r\nLine: ".$exc->getLine();
            $this->log($mess);

            unset($mess);
        }
    }

    /**
     * @param string $path
     */
    public function setFileDebug(string $path): void
    {
        $this->fileDebug = 'string';
    }

    /**
     * @param string $message
     * @param string $file
     */
    public function log(string $message, string $file = 'debug.txt'): void
    {
        \Bitrix\Main\Diag\Debug::writeToFile($message, '', $this->fileDebug);
    }

    /**
     * @return bool
     */
    public function isHasErrors(): bool
    {
        return count($this->exceptions) > 0 ? true : false;
    }
}
