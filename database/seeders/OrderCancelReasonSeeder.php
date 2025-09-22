<?php

namespace Database\Seeders;

use App\Models\OrderCancelReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderCancelReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            'Vehicle Breakdown',
            'Accident / Health Emergency',
            'Traffic Jam / Road Block',
            'Wrong Pickup Location',
            'Customer Not Responding',
            'Excessive Waiting Time',
            'Package Issue',
            'Weather Conditions',
            'Safety Concerns',
            'Order Assigned by Mistake',
        ];

        foreach ($reasons as $index => $name) {
            OrderCancelReason::updateOrCreate(
                ['name' => $name],
                ['is_active' => true]
            );
        }
    }
}
