<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\User;
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
        $jobOffer = new JobOffer([
            'title' => $request->get('title'),
            'company_id' => $request->get('company_id'),
            'position' => $request->get('position'),
            'desc' => $request->get('desc'),
            'requirements' => $request->get('requirements'),
            'requirement_experience' => $request->get('requirement_experience')
        ]);

        // $jobOffer = new JobOffer();
        // $jobOffer->title = $request->get('title');
        // $jobOffer->company_id = $request->get('company_id');
        // $jobOffer->position = $request->get('position');
        // $jobOffer->desc = $request->get('desc');
        // $jobOffer->requirements = $request->get('requirements');
        // $jobOffer->requirement_experience = $request->get('requirement_experience');

        //var_dump($jobOffer);
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

    public function respondOffer(string $jobOfferId, string $userId) {
        $jobOffer = JobOffer::find($jobOfferId);
        $jobOffer->users()->syncWithoutDetaching($userId);
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
