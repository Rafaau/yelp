<?php

namespace App\Tests\Unit\Controller;

use App\Interface\UserServiceInterface;
use App\Controller\UserController as ControllerUserController;
use App\Tests\unit\entity\FakesFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends TestCase
{
    private $userService;
    private $userInterface;
    private $fakesFactory;

    protected function setUp(): void
    {
        $this->userInterface = $this->createMock(UserInterface::class);
        $this->userService = $this->createMock(UserServiceInterface::class);
        $this->fakesFactory = new FakesFactory();
    }

    protected function tearDown(): void
    {
        $this->userInterface = null;
        $this->userService = null;
        $this->userService = null;
    }

    /** @test */
    public function should_return_ok_when_get_current_user_method_succeeded()
    {
        $expected = array(
            'id' => 1,
            'name' => 'test',
            'email' => ''
        );

        $this->userService->expects($this->once())
            ->method('getCurrentUser')
            ->willReturn($expected);

        $controller = new ControllerUserController($this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $response = $controller->getCurrentUser($this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($expected, $responseData['user']);
    }

    /** @test */
    public function should_return_null_when_get_current_user_method_failed()
    {
        $this->userService->expects($this->once())
            ->method('getCurrentUser')
            ->willReturn(array());

        $controller = new ControllerUserController($this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $response = $controller->getCurrentUser($this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(null, $responseData['user']);
    }

    /** @test */
    public function should_return_ok_when_update_method_succeeded()
    {
        $this->userService->expects($this->once())
            ->method('addFriend');

        $controller = new ControllerUserController($this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'id' => 1,
        ]));

        $response = $controller->update($request, $this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('ok', $responseData['status']);
    }

    /** @test */
    public function should_return_error_when_update_method_failed()
    {
        $error = new \Exception('test');
        $this->userService->expects($this->once())
            ->method('addFriend')
            ->willThrowException($error);

        $controller = new ControllerUserController($this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'id' => 1,
        ]));

        $response = $controller->update($request, $this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
    }

    /** @test */
    public function should_return_ok_when_get_user_details_method_succeeded()
    {
        $expected = array(
            'id' => 1,
            'name' => 'test',
            'email' => ''
        );

        $this->userService->expects($this->once())
            ->method('getUserDetails')
            ->willReturn($expected);

        $controller = new ControllerUserController($this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $response = $controller->getUserDetails(1, $this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($expected, $responseData['user']);
    }

    /** @test */
    public function should_return_null_when_get_user_details_method_failed()
    {
        $error = new \Exception('test');
        $this->userService->expects($this->once())
            ->method('getUserDetails')
            ->willThrowException($error);

        $controller = new ControllerUserController($this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $response = $controller->getUserDetails(1, $this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
    }
}