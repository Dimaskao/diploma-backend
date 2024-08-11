<?php

namespace TestsHelpers\Auth\Logout;

use App\Models\User;
use Illuminate\Http\Request;
use TestsEnums\Status;

trait AuthLogoutHelper
{

    private function logoutUserSuccess($email): void
    {
        $user = User::where('email', $email)->first();

        $this->actingAs($user, 'api');

        $request = Request::create('/logout', 'POST');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->registry->logout($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    private function logoutFailed(): void
    {
        $request = Request::create('/logout', 'POST');
        $response = $this->registry->logout($request);
        $statusCode = $this->responseService->internalServerError()->getStatusCode();
        $this->assertEquals($statusCode, $response->getStatusCode());
    }
}
