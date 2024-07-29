<?php

namespace TestsHelpers\Profile\Users\Specific\CompanyHelpers\Handlers\Update;

use App\Models\Company;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use TestsEnums\Status;
use TestsHelpers\Auth\SpecificUser\EmailAuthHelper;

trait UpdateHelper
{
    private function expectedCompanyUpdateResult($status, $response): void
    {
        match ($status) {
            Status::SUCCESS => $this->expectedCompanySuccessUpdateResult($response),
            Status::FAILED => $this->expectedCompanyFailedUpdateResult($response)
        };
    }

    private function expectedCompanySuccessUpdateResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Success', $response->getData(true)['message']);

        $company = Company::first();

        $profile = UserProfile::where('company_id', $company->id)->first();

        $this->assertEquals('Company X update test', $company->name);
        $this->assertEquals('+14189481515', $company->contact_phone);
        $this->assertEquals('https://contact-url.test.com', $company->contact_url);

        // TODO setup models relations $company->userProfile->user error here
//        $this->assertEquals('https://avatar-url.test.com', $company->userProfile->user->avatar_url);

        $this->assertEquals('https://avatar-url.test.com', User::where('user_profile_id', $profile->id)->first()->avatar_url);
    }

    private function expectedCompanyFailedUpdateResult($response): void
    {
        Log::debug('******** response: ' . var_export($response, 1)) ;
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Attempt to read property "userProfile" on array', $response->getData(true)['message']);
    }

    private function getCompanyUpdateRequestData(): array
    {
        return [
            'update_type' => [
                'personal_information' => [
                    'name' => 'Company X update test',
                    'contact_email' => EmailAuthHelper::generateRandomEmail(),
                    "contact_phone" => "+14189481515",
                    "password" => "new password",
                    "contact_url" => "https://contact-url.test.com",
                    "avatar_url" => "https://avatar-url.test.com",
                ]
            ]
        ];
    }
}
