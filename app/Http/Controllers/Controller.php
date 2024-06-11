<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Display a listing of the skills.
     */
    public function index()
    {
    }

    /**
     * Store a newly created skill in storage.
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
     * Update the specified skill in storage.
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified skill from storage.
     */
    public function destroy($id)
    {
    }
}
