<?php

namespace App\Core;

class Request
{
    /**
     * Get the request path without query parameters.
     *
     * @return string The sanitized request path.
     */
    public function path(): string
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, "?");
        if ($position !== false) {
            return rtrim(htmlspecialchars(substr($path, 0, $position)), '/');
        }
        if (preg_match('/[a-zA-Z0-9]/', $path)) {
            return rtrim(htmlspecialchars($path), '/');
        }
        return htmlspecialchars($path);
    }
    /**
     * Get the HTTP request method (GET, POST, PUT, etc.).
     *
     * @return string The HTTP request method.
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    /**
     * Check if the request method is GET.
     *
     * @return bool True if the request method is GET, otherwise false.
     */
    public function isGet(): bool
    {
        return $this->method() === 'get';
    }
    /**
     * Check if the request method is POST.
     *
     * @return bool True if the request method is POST, otherwise false.
     */
    public function isPost(): bool
    {
        return $this->method() === 'post';
    }
    /**
     * Check if the request method is PUT.
     *
     * @return bool True if the request method is PUT, otherwise false.
     */
    public function isPut(): bool
    {
        return $this->method() === 'put';
    }
    /**
     * Check if the request method is PATCH.
     *
     * @return bool True if the request method is PATCH, otherwise false.
     */
    public function isPatch(): bool
    {
        return $this->method() === 'patch';
    }
    /**
     * Check if the request method is DELETE.
     *
     * @return bool True if the request method is DELETE, otherwise false.
     */
    public function isDelete(): bool
    {
        return $this->method() === 'delete';
    }
    /**
     * Check if the request method is OPTIONS.
     *
     * @return bool True if the request method is OPTIONS, otherwise false.
     */
    public function isOption(): bool
    {
        return $this->method() === 'option';
    }
    /**
     * Get the body data of the request, sanitized for security.
     *
     * This method handles the input data based on the HTTP method (GET, POST, PUT, PATCH, DELETE).
     * It returns an array of sanitized data.
     *
     * @return array The sanitized request body data.
     */
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
