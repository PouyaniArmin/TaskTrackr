<?php

namespace App\Core;

class Request
{
    public function path(): string
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, "?");
        if ($position !== false) {
            return rtrim(htmlspecialchars(substr($path, 0, $position)), '/');
        }
        return rtrim(htmlspecialchars($path), '/');
    }
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function isGet(): bool
    {
        return $this->method() === 'get';
    }
    public function isPost(): bool
    {
        return $this->method() === 'post';
    }
    public function isPut(): bool
    {
        return $this->method() === 'put';
    }
    public function isPatch(): bool
    {
        return $this->method() === 'patch';
    }
    public function isDelete(): bool
    {
        return $this->method() === 'delete';
    }
    public function isOption(): bool
    {
        return $this->method() === 'option';
    }
    public function body(): array
    {
        $data = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
        }

        if ($this->isPut() || $this->isPatch() || $this->isDelete()) {
            $input = file_get_contents('php://input');

            $data = json_decode($input, true);
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }
            }
            if ($data === null) {
                $data = [];
            }
        }

        return $data;
    }
}
