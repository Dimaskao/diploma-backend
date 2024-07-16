<?php

namespace Tests\Feature\ServicesTests;

use App\Enums\SearchType;
use App\Services\SearchService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\Feature\TestsHelpers\ChatHelper;
use Tests\TestCase;

class SearchServiceTest extends TestCase
{
    use RefreshDatabase, ChatHelper;

    protected SearchService $searchService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->seed(DatabaseSeeder::class);
        $this->searchService = new SearchService();
    }

    public function testSearchUsers()
    {
        $this->getRegularTestUser();
        $request = new Request(['query' => 'John', 'searchType' => SearchType::USERS]);

        $response = $this->searchService->search($request);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertCount(2, $responseData['results']);
        Log::debug('$responseData[results]: ' . var_export($responseData['results'], 1));
        $this->assertEquals("John Doe", $responseData['results']['users'][0]['name']);
    }

    public function testSearchCompanies()
    {
        $this->getCompanyTestUser();
        $request = new Request(['query' => 'Test Company', 'searchType' => SearchType::COMPANIES]);

        $response = $this->searchService->search($request);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals("Test Company", $responseData['results']['companies'][0]['name']);
    }
}
