<?php

namespace TestsHelpers\Image;

use Illuminate\Foundation\Testing\RefreshDatabase;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Image\Helpers\ImageUploadSetUpHelper;
use TestsHelpers\Profile\Users\UsersHelper;

trait ImageUploadServiceTestsHelper
{
    use RefreshDatabase, AuthHelper, UsersHelper, ImageUploadSetUpHelper;

    public function testValidImageUpload(): void
    {
        $url = $this->upload();
        $this->assertNotEmpty($url);
    }

    public function testInvalidImageUpload(): void
    {
        $url = $this->upload(1);
        $this->assertNull($url);
    }
}
