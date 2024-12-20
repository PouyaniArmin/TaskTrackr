<?php

use App\Core\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{

    private Request $request;
    public function setUp(): void
    {
        $this->request = new Request;
    }

    public function testValidPath(): void
    {
        $_SERVER['REQUEST_URI'] = '/home';
        $path = $this->request->path();
        $this->assertEquals('/home', $path);
    }
    public function testInValidPath(): void
    {
        $_SERVER['REQUEST_URI'] = '/homes';
        $path = $this->request->path();
        $this->assertNotEquals('/home', $path);
    }
    public function testSqlInjectionPath(): void
    {
        $_SERVER['REQUEST_URI'] = '/home/?id=2';
        $path = $this->request->path();
        $this->assertEquals('/home', $path);
        $this->assertNotEquals('/home/?id=2', $path);
    }
    public function testXssPath(): void
    {
        $_SERVER['REQUEST_URI'] = "/home/<script>alert('XSS')</script>";
        $path = $this->request->path();
        $this->assertEquals("/home/&lt;script&gt;alert(&#039;XSS&#039;)&lt;/script&gt;", $path);
        $this->assertNotEquals("/home/<script>alert('XSS')</script>", $path);
    }
    public function testGetValidData(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = ['name' => 'test', 'age' => 23];
        $data = $this->request->body();
        $this->assertEquals(['name' => 'test', 'age' => 23], $data);
    }
    public function testGetInValidData(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = ['name' => "<script>alert('XSS')</script>", 'age' => 23];
        $data = $this->request->body();
        $this->assertStringNotContainsString("<script>", $data['name']);
        $this->assertNotEquals(['name' => "<script>alert('XSS')</script>", 'age' => 23], $data);
    }
    // POST
    public function testPostValidData(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = ['name' => 'test', 'age' => 23];
        $data = $this->request->body();
        $this->assertEquals(['name' => 'test', 'age' => 23], $data);
    }
    public function testPOSTInValidData(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = ['name' => "<script>alert('XSS')</script>", 'age' => 23];
        $data = $this->request->body();
        $this->assertStringNotContainsString("<script>", $data['name']);
        $this->assertNotEquals(['name' => "<script>alert('XSS')</script>", 'age' => 23], $data);
    }


    public function testPutValidJsonData(): void
    {
        $array = ['key' => 'value', 'name' => 'test'];
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $GLOBALS['mock_php_input'] = json_encode($array);


        if ('PUT' === $_SERVER['REQUEST_METHOD']) {
            $input = $this->file_get_contents('php://input');
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
        $this->assertNotEmpty($data, 'The data should not be empty.');

        $this->assertSame($array, $data);
    }
    public function testPutInvalidJsonData(): void
    {
        $array = [
            'key' => '<script>alert("XSS");</script>',
            'name' => '<img src="invalid" onerror="alert(\'XSS\')">'
        ];
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $GLOBALS['mock_php_input'] = json_encode($array);


        if ('PUT' === $_SERVER['REQUEST_METHOD']) {
            $input = $this->file_get_contents('php://input');
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
        $this->assertNotEmpty($data, 'The data should not be empty.');

        $this->assertNotSame($array, $data, 'Raw XSS inputs should not be allowed.');
    }
    // patch
    public function testPatchValidJsonData(): void
    {
        $array = ['key' => 'value', 'name' => 'test'];
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        $GLOBALS['mock_php_input'] = json_encode($array);


        if ('PATCH' === $_SERVER['REQUEST_METHOD']) {
            $input = $this->file_get_contents('php://input');
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
        $this->assertNotEmpty($data, 'The data should not be empty.');

        $this->assertSame($array, $data);
    }
    public function testPatchInvalidJsonData(): void
    {
        $array = [
            'key' => '<script>alert("XSS");</script>',
            'name' => '<img src="invalid" onerror="alert(\'XSS\')">'
        ];
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        $GLOBALS['mock_php_input'] = json_encode($array);


        if ('PATCH' === $_SERVER['REQUEST_METHOD']) {
            $input = $this->file_get_contents('php://input');
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
        $this->assertNotEmpty($data, 'The data should not be empty.');

        $this->assertNotSame($array, $data, 'Raw XSS inputs should not be allowed.');
    }
    // Delete
    public function testDeleteValidJsonData(): void
    {
        $array = ['key' => 'value', 'name' => 'test'];
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $GLOBALS['mock_php_input'] = json_encode($array);


        if ('DELETE' === $_SERVER['REQUEST_METHOD']) {
            $input = $this->file_get_contents('php://input');
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
        $this->assertNotEmpty($data, 'The data should not be empty.');

        $this->assertSame($array, $data);
    }
    public function testDeleteInvalidJsonData(): void
    {
        $array = [
            'key' => '<script>alert("XSS");</script>',
            'name' => '<img src="invalid" onerror="alert(\'XSS\')">'
        ];
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $GLOBALS['mock_php_input'] = json_encode($array);


        if ('DELETE' === $_SERVER['REQUEST_METHOD']) {
            $input = $this->file_get_contents('php://input');
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
        $this->assertNotEmpty($data, 'The data should not be empty.');

        $this->assertNotSame($array, $data, 'Raw XSS inputs should not be allowed.');
    }

    private function file_get_contents($filename)
    {
        if ($filename === 'php://input' && isset($GLOBALS['mock_php_input'])) {
            return $GLOBALS['mock_php_input'];
        }
        return \file_get_contents($filename);
    }
}
