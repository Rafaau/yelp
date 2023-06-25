<?php

namespace App\Tests\Unit\Controller;

use App\Controller\BusinessController as ControllerBusinessController;
use App\Interface\BusinessServiceInterface;
use App\Tests\unit\entity\FakesFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class BusinessController extends TestCase
{
    private $userInterface;
    private $businessService;
    private $fakesFactory;

    protected function setUp(): void
    {
        $this->userInterface = $this->createMock(UserInterface::class);
        $this->businessService = $this->createMock(BusinessServiceInterface::class);
        $this->fakesFactory = new FakesFactory();
    }

    protected function tearDown(): void
    {
        $this->userInterface = null;
        $this->businessService = null;
    }

    /** @test */
    public function should_return_ok_when_create_method_succeeded()
    {
        $this->businessService->expects($this->once())
            ->method('createBusiness')
            ->willReturn($this->fakesFactory->generateBusiness());

        $controller = new ControllerBusinessController($this->businessService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'name' => 'test',
        ]));

        $response = $controller->create($request, $this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('ok', $responseData['status']);
    }

    /** @test */
    public function should_return_error_when_create_method_failed()
    {
        $error = new \Exception('test');
        $this->businessService->expects($this->once())
            ->method('createBusiness')
            ->willThrowException($error);

        $controller = new ControllerBusinessController($this->businessService);

        $this->userInterface->method('getUserIdentifier')->willReturn('1');

        $request = new Request();
        $request->initialize([], [], [], [], [], [], json_encode([
            'name' => 'test',
        ]));

        $response = $controller->create($request, $this->userInterface);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals($error->getMessage(), $responseData['message']);
    }

    /** @test */
    public function should_return_ok_when_get_method_succeeded()
    {
        $expected = array(
            'name' => 'test',
        );

        $this->businessService->expects($this->once())
            ->method('getBusiness')
            ->willReturn($expected);

        $controller = new ControllerBusinessController($this->businessService);

        $response = $controller->get('test', 'test');

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('ok', $responseData['status']);
        $this->assertEquals($expected, $responseData['business']);
    }

    /** @test */
    public function should_return_error_when_get_method_failed()
    {
        $error = new \Exception('test');
        $this->businessService->expects($this->once())
            ->method('getBusiness')
            ->willThrowException($error);

        $controller = new ControllerBusinessController($this->businessService);

        $response = $controller->get('test', 'test');

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals($error->getMessage(), $responseData['message']);
    }

    /** @test */
    public function should_return_ok_when_get_all_method_succeeded()
    {
        $expected = array(
            'name' => 'test',
        );

        $this->businessService->expects($this->once())
            ->method('getBusinesses')
            ->willReturn($expected);

        $controller = new ControllerBusinessController($this->businessService);

        $request = new Request();
        $request->initialize([
            'cflt' => 'test',
            'find_loc' => 'test',
        ], [], [], [], [], [], []);

        $response = $controller->getAll($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('ok', $responseData['status']);
        $this->assertEquals($expected, $responseData['businesses']);
    }

    /** @test */
    public function should_return_error_when_get_all_method_failed()
    {
        $error = new \Exception('test');
        $this->businessService->expects($this->once())
            ->method('getBusinesses')
            ->willThrowException($error);

        $controller = new ControllerBusinessController($this->businessService);

        $request = new Request();
        $request->initialize([
            'cflt' => 'test',
            'find_loc' => 'test',
        ], [], [], [], [], [], []);

        $response = $controller->getAll($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseData['status']);
        $this->assertEquals($error->getMessage(), $responseData['message']);
    }
}