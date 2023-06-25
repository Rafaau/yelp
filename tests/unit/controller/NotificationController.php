<?php

namespace App\Tests\Unit\Controller;

use App\Interface\NotificationServiceInterface;
use App\Controller\NotificationController as ControllerNotificationController;
use App\Interface\UserServiceInterface;
use App\Tests\unit\entity\FakesFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class NotificationController extends TestCase
{
    private $notificationService;
    private $userService;
    private $userInterface;
    private $fakesFactory;

    protected function setUp(): void
    {
        $this->userInterface = $this->createMock(UserInterface::class);
        $this->userService = $this->createMock(UserServiceInterface::class);
        $this->notificationService = $this->createMock(NotificationServiceInterface::class);
        $this->fakesFactory = new FakesFactory();
    }

    protected function tearDown(): void
    {
        $this->userInterface = null;
        $this->userService = null;
        $this->notificationService = null;
    }

    /** @test */
    public function should_return_ok_when_mark_as_read_method_succeeded()
    {
        $this->notificationService->expects($this->once())
            ->method('markAsRead');

        $controller = new ControllerNotificationController($this->notificationService, $this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $response = $controller->update($this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('ok', $responseData['status']);
    }

    /** @test */
    public function should_return_error_when_mark_as_read_method_failed()
    {
        $error = new \Exception('test');
        $this->notificationService->expects($this->once())
            ->method('markAsRead')
            ->willThrowException($error);

        $controller = new ControllerNotificationController($this->notificationService, $this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $response = $controller->update($this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('test', $responseData['message']);
    }
}