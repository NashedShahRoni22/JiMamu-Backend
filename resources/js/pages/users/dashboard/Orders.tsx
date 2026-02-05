import React from "react";
import AppLayout from "@/layouts/app-layout";
import RiderDashboardLayout from "@/layouts/RiderDashboardLayout";
import { Head } from "@inertiajs/react";

// Type for a single order attempt
type OrderAttempt = {
    id: number;
    status: number;
    order_tracking_number: string;
    fare: number;
    parcel_estimate_price: number;
    payment_status: number;
    created_at: string;
};

// Type for a single order
type Order = {
    id: number;
    status: number;
    weight: number;
    fare: number;
    order_attempt?: OrderAttempt; // single object
};

// Props interface
interface OrdersProps {
    orders: Order[];
}

export default function Orders({ orders }: OrdersProps) {
    return (
        <AppLayout>
            <RiderDashboardLayout>
                <Head title="Orders" />
                <h2 className="text-2xl font-bold mb-4">Active Orders</h2>
                <p>Test wallet</p>

                {orders.length ? (
                    <ul className="space-y-3">
                        {orders.map((order) => (
                            <li key={order.id} className="bg-white p-4 rounded shadow">
                                {/* Order Info */}
                                <div className="flex justify-between mb-2">
                                    <span>Order #{order.id}</span>
                                    <span>Status: {order.status}</span>
                                    <span>Fare: {order.fare} ৳</span>
                                    <span>Weight: {order.weight} kg</span>
                                </div>

                                {/* Latest Order Attempt */}
                                {order.order_attempt ? (
                                    <table className="w-full text-sm border mt-2">
                                        <thead className="bg-gray-100">
                                        <tr>
                                            <th className="border p-1">Attempt ID</th>
                                            <th className="border p-1">Tracking #</th>
                                            <th className="border p-1">Status</th>
                                            <th className="border p-1">Fare</th>
                                            <th className="border p-1">Created At</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td className="border p-1">{order.order_attempt.id}</td>
                                            <td className="border p-1">
                                                {order.order_attempt.order_tracking_number}
                                            </td>
                                            <td className="border p-1">{order.order_attempt.status}</td>
                                            <td className="border p-1">{order.order_attempt.fare} ৳</td>
                                            <td className="border p-1">
                                                {new Date(order.order_attempt.created_at).toLocaleString()}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                ) : (
                                    <p className="text-gray-500 text-sm mt-2">No attempts yet</p>
                                )}
                            </li>
                        ))}
                    </ul>
                ) : (
                    <p>No active orders.</p>
                )}
            </RiderDashboardLayout>
        </AppLayout>
    );
}
