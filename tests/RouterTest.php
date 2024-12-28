<?php

use App\Core\Middleware\AdminMiddleware;
use App\Core\Request;
use App\Core\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;
    public function setUp(): void
    {
        $this->router = new Router(new Request);
    }

    public function testCanRegisterGetRoute()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->router->get('/', function () {
            return 'test';
        });
        $response = $this->router->resolve();
        $this->assertEquals('test', $response);
    }
    public function testCanRegisterPostRoute()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->router->post('/', function () {
            return 'test';
        });
        $response = $this->router->resolve();
        $this->assertEquals('test', $response);
    }
    public function testRouteGetWithParameters()
    {

        $_SERVER['REQUEST_URI'] = '/new/123';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->router->get('/new/{id}', function ($id) {
            return $id;
        });
        $response = $this->router->resolve();
        $this->assertEquals('123', $response);
    }
    public function testRoutePostWithParameters()
    {
        $_SERVER['REQUEST_URI'] = '/new/123';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->router->post('/new/{id}', function ($id) {
            return $id;
        });
        $response = $this->router->resolve();
        $this->assertEquals('123', $response);
    }

    public function testMiddlewareIsExecutedBeforeCallback()
    {
        $_SERVER['REQUEST_URI'] = '/news';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->router->get('/news', function () {
            return "Hi";
        }, [AdminMiddleware::class]);
        $response = $this->router->resolve();
        $this->assertNoTEquals('Hi', $response);
    }

    public function testReturnsNotFoundForUnregisteredRoute()
    {
        $this->router->get('/posts', function () {
            return 'test';
        });
        $response = $this->router->resolve();
        $expectedContent = file_get_contents(__DIR__.'/../app/views/notFound.php');
        $this->assertStringContainsString($expectedContent, $response);
    }
}
