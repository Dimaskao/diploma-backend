<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\Http\Request;

class JobOffersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $jobOffers = JobOffer::all();
        return $jobOffers;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $jobOffer = new JobOffer($request->all());
        $jobOffer->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $jobOffer = JobOffer::find($id);
        return $jobOffer;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function job_offers_by_company(string $id) {
        $jobOffers = JobOffer::where('company_id', $id)->orderBy('created_at')->get();
        return $jobOffers;
    }
}
