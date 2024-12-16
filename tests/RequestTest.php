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
}
