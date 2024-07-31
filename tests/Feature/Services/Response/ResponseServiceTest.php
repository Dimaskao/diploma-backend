<?php

namespace Tests\Feature\ServicesTests\Response;

use App\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ResponseServiceTest extends TestCase
{
    protected ResponseService $responseService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->responseService = new ResponseService();
    }

    public function testResponse()
    {
        $data = ['key' => 'value'];
        $message = 'Test message';
        $statusCode = 200;

        $response = $this->responseService->response($data, $message, $statusCode);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals(['data' => $data, 'message' => $message], $response->getData(true));
    }

    public function testSuccess()
    {
        $data = ['key' => 'value'];
        $message = 'Success';

        $response = $this->responseService->success($data, $message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['data' => $data, 'message' => $message], $response->getData(true));
    }

    public function testCreated()
    {
        $data = ['key' => 'value'];
        $message = 'Created';

        $response = $this->responseService->created($data, $message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['data' => $data, 'message' => $message], $response->getData(true));
    }

    public function testBadRequest()
    {
        $message = 'Bad Request';

        $response = $this->responseService->badRequest($message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['data' => null, 'message' => $message], $response->getData(true));
    }

    public function testUnauthorized()
    {
        $message = 'Unauthorized';

        $response = $this->responseService->unauthorized($message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(['data' => null, 'message' => $message], $response->getData(true));
    }

    public function testForbidden()
    {
        $message = 'Forbidden';

        $response = $this->responseService->forbidden($message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(['data' => null, 'message' => $message], $response->getData(true));
    }

    public function testNotFound()
    {
        $message = 'Not Found';

        $response = $this->responseService->notFound($message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['data' => null, 'message' => $message], $response->getData(true));
    }

    public function testInternalServerError()
    {
        $message = 'Internal Server Error';

        $response = $this->responseService->internalServerError($message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['data' => null, 'message' => $message], $response->getData(true));
    }
}
