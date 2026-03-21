<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingRate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlatformChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $platformCharges = PricingRate::latest()->get();
 
        return Inertia::render('platformCharge/index', [
            'platformCharges' => $platformCharges,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('platformCharge/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'base_fare'        => ['required', 'numeric', 'min:0'],
            'platform_charge'  => ['required', 'numeric', 'min:0'],
            'type'             => ['required', 'in:1,2'],
        ]);
 
        PricingRate::create($validated);
 
        return redirect()
            ->route('platform-charge.index')
            ->with('success', 'Platform charge created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $platformCharge = PricingRate::findOrFail($id);
 
        return Inertia::render('platformCharge/edit', [
            'platformCharge' => $platformCharge,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $platformCharge = PricingRate::findOrFail($id);
 
        return Inertia::render('platformCharge/edit', [
            'platformCharge' => $platformCharge,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $platformCharge = PricingRate::findOrFail($id);
 
        $validated = $request->validate([
            'base_fare'        => ['required', 'numeric', 'min:0'],
            'platform_charge'  => ['required', 'numeric', 'min:0'],
            'type'             => ['required', 'in:1,2'],
        ]);
 
        $platformCharge->update($validated);
 
        return redirect()
            ->route('platform-charge.index')
            ->with('success', 'Platform charge updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
