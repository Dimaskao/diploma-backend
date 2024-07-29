<?php

namespace Tests\Feature\ServicesTests;

use App\Services\ValidationService;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ValidationServiceTest extends TestCase
{
    protected ValidationService $validationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validationService = new ValidationService();
    }

    public function testValidationPasses()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ];

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ];

        $validatedData = $this->validationService->validate($data, $rules);

        $this->assertEquals($data, $validatedData);
    }

    public function testValidationFails()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => '',
            'email' => 'invalid-email',
        ];

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ];

        $this->validationService->validate($data, $rules);
    }
}
