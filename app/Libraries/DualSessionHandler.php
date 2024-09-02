<?php

namespace App\Libraries;

use CodeIgniter\Session\Handlers\DatabaseHandler;
use CodeIgniter\Session\Handlers\FileHandler;
use CodeIgniter\Session\SessionInterface;
use Config\Database;

class DualSessionHandler implements SessionInterface
{
    protected $fileHandler;
    protected $dbHandler;

    public function __construct()
    {
        $this->fileHandler = new FileHandler(config('App'));
        $this->dbHandler = new DatabaseHandler(config('App'), Database::connect());
    }

    public function open($savePath, $name)
    {
        return $this->fileHandler->open($savePath, $name) && $this->dbHandler->open($savePath, $name);
    }

    public function close()
    {
        return $this->fileHandler->close() && $this->dbHandler->close();
    }

    public function read($sessionId)
    {
        $data = $this->dbHandler->read($sessionId);
        if ($data === '') {
            $data = $this->fileHandler->read($sessionId);
        }
        return $data;
    }

    public function write($sessionId, $sessionData)
    {
        return $this->fileHandler->write($sessionId, $sessionData) && $this->dbHandler->write($sessionId, $sessionData);
    }

    public function destroy($sessionId)
    {
        return $this->fileHandler->destroy($sessionId) && $this->dbHandler->destroy($sessionId);
    }

    public function gc($maxlifetime)
    {
        return $this->fileHandler->gc($maxlifetime) && $this->dbHandler->gc($maxlifetime);
    }
}
