<?php

namespace App\Tests\Unit\Controller;

use App\Controller\MessageController as ControllerMessageController;
use App\Interface\MessageServiceInterface;
use App\Interface\UserServiceInterface;
use App\Tests\unit\entity\FakesFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageController extends TestCase
{
    private $messageService;
    private $userService;
    private $userInterface;
    private $fakesFactory;

    protected function setUp(): void
    {
        $this->userInterface = $this->createMock(UserInterface::class);
        $this->userService = $this->createMock(UserServiceInterface::class);
        $this->messageService = $this->createMock(MessageServiceInterface::class);
        $this->fakesFactory = new FakesFactory();
    }

    protected function tearDown(): void
    {
        $this->userInterface = null;
        $this->userService = null;
        $this->messageService = null;
    }

    /** @test */
    public function should_return_ok_when_post_method_succeeded()
    {
        $this->messageService->expects($this->once())
            ->method('postMessage')
            ->willReturn($this->fakesFactory->generateUser());

        $controller = new ControllerMessageController($this->messageService, $this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'name' => 'test',
        ]));

        $response = $controller->post($request, $this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('ok', $responseData['status']);
    }

    /** @test */
    public function should_return_error_when_post_method_failed()
    {
        $error = new \Exception('test');
        $this->messageService->expects($this->once())
            ->method('postMessage')
            ->willThrowException($error);

        $controller = new ControllerMessageController($this->messageService, $this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'name' => 'test',
        ]));

        $response = $controller->post($request, $this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('test', $responseData['message']);
    }

    /** @test */
    public function should_return_ok_when_get_method_succeeded()
    {
        $this->messageService->expects($this->once())
            ->method('getMessages')
            ->willReturn([
                $this->fakesFactory->generateMessage(),
                $this->fakesFactory->generateMessage(),
            ]);

        $this->userService->expects($this->once())
            ->method('getUserById')
            ->willReturn($this->fakesFactory->generateUser());

        $controller = new ControllerMessageController($this->messageService, $this->userService);

        $request = new Request();
        $request->query->add([
            'receiverId' => '1',
            'senderId' => '2',
        ]);

        $response = $controller->get($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('messages', $responseData);
        $this->assertArrayHasKey('receiver', $responseData);
    }

    /** @test */
    public function should_return_error_when_get_method_failed()
    {
        $error = new \Exception('test');
        $this->messageService->expects($this->once())
            ->method('getMessages')
            ->willThrowException($error);

        $controller = new ControllerMessageController($this->messageService, $this->userService);

        $request = new Request();
        $request->query->add([
            'receiverId' => '1',
            'senderId' => '2',
        ]);

        $response = $controller->get($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('test', $responseData['message']);
    }

    /** @test */
    public function should_return_ok_when_get_conversations_method_succeeded()
    {
        $expected = [
                $this->fakesFactory->generateMessage(),
                $this->fakesFactory->generateMessage(),
        ];
        $this->messageService->expects($this->once())
            ->method('getConversations')
            ->willReturn($expected);

        $controller = new ControllerMessageController($this->messageService, $this->userService);

        $response = $controller->getConversations($this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('conversations', $responseData);
    }

    /** @test */
    public function should_return_error_when_get_conversations_method_failed()
    {
        $error = new \Exception('test');
        $this->messageService->expects($this->once())
            ->method('getConversations')
            ->willThrowException($error);

        $controller = new ControllerMessageController($this->messageService, $this->userService);

        $response = $controller->getConversations($this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals('test', $responseData['message']);
    }
}