<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Display a listing of the data.
     */
    public function index()
    {
    }

    /**
     * Store a newly created data in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified skill.
     */
    public function show(string $id)
    {
    }

    /**
     * Update the specified data in storage.
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified data from storage.
     */
    public function destroy($id)
    {
    }
}
