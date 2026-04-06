<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRider;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RiderAccountReviewController extends Controller
{
    public function accountCreateRequest()
    {
        $riders = User::role('rider')
            ->with('userRiders')
            ->get()
            ->map(function ($rider) {
                // If each rider has only ONE userRider
                $userRider = $rider->userRiders->first();

                $rider->review_status_text = $userRider
                    ? UserRider::$REVIEW_STATUS[$userRider->review_status] ?? null
                    : null;

                return $rider;
            });

        return Inertia::render('riders/index', [
            'riders' => $riders
        ]);
    }
    public function accountReviewDetails($id)
    {
        $rider = User::with('userRiders')->findOrFail($id);

        // If each rider has only one review record
        $userRider = $rider->userRiders->first();

        $rider->review_status_text = $userRider
            ? UserRider::$REVIEW_STATUS[$userRider->review_status] ?? null
            : null;

        return Inertia::render('riders/account-review-details', [
            'rider' => $rider
        ]);
    }
    // rider account review approved or rejected
    public function accountApprove($userId, $statusType)
    {
     $rider = User::with('userRiders')->findOrFail($userId);

        foreach ($rider->userRiders as $userRider) {
            $userRider->review_status = $statusType; // approved or rejected
            $userRider->save();
        }

        return Inertia::render('riders/account-review-details', [
            'rider' => $rider,
            // Pass message as normal prop
            'toastMessage' => $statusType == 2
                ? 'Rider approved successfully!'
                : 'Rider rejected!',
        ]);
    }




}
