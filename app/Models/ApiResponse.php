<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiResponse implements \JsonSerializable
{   
    protected $data = [];
    protected $message = '';
    protected $success = false;
    protected $error_code = 500;

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function setErrorCode(int $error_code): void
    {
        $this->error_code = $error_code;
    }

    public function jsonSerialize()
    {
        return [
            'data' => $this->data,
            'message' => $this->message,
            'success' => $this->success,
            'error_code' => $this->error_code,
        ];
    }
}
