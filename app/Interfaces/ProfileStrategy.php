<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ProfileStrategy
{
    public function show($id): JsonResponse;

    public function update(Request $request, $id): JsonResponse;

    public function deleteProfile($id): JsonResponse;
}
