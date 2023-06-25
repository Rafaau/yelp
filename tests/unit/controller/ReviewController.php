<?php

namespace App\Tests\Unit\Controller;

use App\Interface\ReviewServiceInterface;
use App\Controller\ReviewController as ControllerReviewController;
use App\Interface\UserServiceInterface;
use App\Tests\unit\entity\FakesFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewController extends TestCase
{
    private $reviewService;
    private $userService;
    private $userInterface;
    private $fakesFactory;

    protected function setUp(): void
    {
        $this->userInterface = $this->createMock(UserInterface::class);
        $this->userService = $this->createMock(UserServiceInterface::class);
        $this->reviewService = $this->createMock(ReviewServiceInterface::class);
        $this->fakesFactory = new FakesFactory();
    }

    protected function tearDown(): void
    {
        $this->userInterface = null;
        $this->userService = null;
        $this->reviewService = null;
    }

    /** @test */
    public function should_return_ok_when_post_method_succeeded()
    {
        $this->reviewService->expects($this->once())
            ->method('postReview')
            ->willReturn($this->fakesFactory->generateReview());

        $controller = new ControllerReviewController($this->reviewService, $this->userService);

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
        $this->reviewService->expects($this->once())
            ->method('postReview')
            ->willThrowException($error);

        $controller = new ControllerReviewController($this->reviewService, $this->userService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'name' => 'test',
        ]));

        $response = $controller->post($request, $this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
    }

    /** @test */
    public function should_return_ok_when_update_method_succeeded()
    {
        $this->reviewService->expects($this->once())
            ->method('updateReactions');

        $controller = new ControllerReviewController($this->reviewService, $this->userService);

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'name' => 'test',
        ]));

        $response = $controller->update($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('ok', $responseData['status']);
    }

    /** @test */
    public function should_return_error_when_update_method_failed()
    {
        $error = new \Exception('test');
        $this->reviewService->expects($this->once())
            ->method('updateReactions')
            ->willThrowException($error);

        $controller = new ControllerReviewController($this->reviewService, $this->userService);

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'name' => 'test',
        ]));

        $response = $controller->update($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
    }

    /** @test */
    public function should_return_ok_when_delete_method_succeeded()
    {
        $this->reviewService->expects($this->once())
            ->method('deleteReview');

        $controller = new ControllerReviewController($this->reviewService, $this->userService);

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'id' => 1,
        ]));

        $response = $controller->delete($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('ok', $responseData['status']);
    }

    /** @test */
    public function should_return_error_when_delete_method_failed()
    {
        $error = new \Exception('test');
        $this->reviewService->expects($this->once())
            ->method('deleteReview')
            ->willThrowException($error);

        $controller = new ControllerReviewController($this->reviewService, $this->userService);

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'id' => 1,
        ]));

        $response = $controller->delete($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
    }

    /** @test */
    public function should_return_ok_when_get_method_succeeded()
    {
        $expected = [
            $this->fakesFactory->generateReview(),
            $this->fakesFactory->generateReview(),
        ];

        $this->reviewService->expects($this->once())
            ->method('getReviews')
            ->willReturn($expected);

        $controller = new ControllerReviewController($this->reviewService, $this->userService);

        $response = $controller->getReviews('test', 1, 1);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertIsArray($responseData);
    }

    /** @test */
    public function should_return_error_when_get_method_failed()
    {
        $error = new \Exception('test');
        $this->reviewService->expects($this->once())
            ->method('getReviews')
            ->willThrowException($error);

        $controller = new ControllerReviewController($this->reviewService, $this->userService);

        $response = $controller->getReviews('test', 1, 1);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertIsArray($responseData);
    }
}