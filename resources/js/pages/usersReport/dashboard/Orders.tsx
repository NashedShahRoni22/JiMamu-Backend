import React, { useMemo, useState } from "react";
import AppLayout from "@/layouts/app-layout";
import RiderDashboardLayout from "@/layouts/RiderDashboardLayout";
import { Head } from "@inertiajs/react";

// ================= Types =================
type Rider = {
    name: string;
    email: string;
    avatar?: string;
};

type OrderAttempt = {
    id: number;
    status: number;
    order_tracking_number: string;
    fare: number;
    parcel_estimate_price: number;
    payment_status: number;
    created_at: string;
};

type Order = {
    id: number;
    order_unique_id: string;
    status: number;
    order_type: number;
    weight: number;
    created_at: string;
    rider: Rider;
    order_attempt?: OrderAttempt;
};

interface OrdersProps {
    orders: Order[];
}

// ================= Helpers =================
const ORDER_STATUS_NAME: Record<number, string> = {
    1: "Pending",
    2: "Confirmed",
    3: "Picked",
    4: "Shipping",
    5: "Delivered",
    6: "Cancelled",
};

const PAYMENT_STATUS_NAME: Record<number, string> = {
    0: "Unpaid",
    1: "Paid",
};

// ================= Component =================
export default function Orders({ orders }: OrdersProps) {
    const [filters, setFilters] = useState({
        order_unique_id: "",
        tracking: "",
        email: "",
        status: "",
        type: "",
        payment_status: "",
    });

    const filteredOrders = useMemo(() => {
        return orders.filter((order) => {
            const attempt = order.order_attempt;

            return (
                (!filters.order_unique_id ||
                    String(order.order_unique_id)
                        .toLowerCase()
                        .includes(filters.order_unique_id.toLowerCase()))&&
                (!filters.tracking ||
                    attempt?.order_tracking_number
                        ?.toLowerCase()
                        .includes(filters.tracking.toLowerCase())) &&
                (!filters.status ||
                    String(order.status) === filters.status) &&
                (!filters.type ||
                    String(order.order_type) === filters.type) &&
                (!filters.payment_status ||
                    String(attempt?.payment_status) === filters.payment_status)
            );
        });
    }, [orders, filters]);

    return (
        <AppLayout>
            <RiderDashboardLayout>
                <Head title="Orders" />

                <h2 className="text-2xl font-bold mb-4">Delivery Reports</h2>

                {/* ================= Filters ================= */}
                <div className="grid grid-cols-2 md:grid-cols-6 gap-2 mb-4">
                    <input
                        className="border p-2 rounded"
                        placeholder="Order #"
                        value={filters.order_unique_id}
                        onChange={(e) =>
                            setFilters({ ...filters, order_unique_id: e.target.value })
                        }
                    />

                    {/*<input*/}
                    {/*    className="border p-2 rounded"*/}
                    {/*    placeholder="Tracking #"*/}
                    {/*    value={filters.tracking}*/}
                    {/*    onChange={(e) =>*/}
                    {/*        setFilters({ ...filters, tracking: e.target.value })*/}
                    {/*    }*/}
                    {/*/>*/}

                    <select
                        className="border p-2 rounded"
                        value={filters.status}
                        onChange={(e) =>
                            setFilters({ ...filters, status: e.target.value })
                        }
                    >
                        <option value="">All Status</option>
                        {Object.entries(ORDER_STATUS_NAME).map(([k, v]) => (
                            <option key={k} value={k}>
                                {v}
                            </option>
                        ))}
                    </select>

                    <select
                        className="border p-2 rounded"
                        value={filters.payment_status}
                        onChange={(e) =>
                            setFilters({
                                ...filters,
                                payment_status: e.target.value,
                            })
                        }
                    >
                        <option value="">All Payments</option>
                        <option value="1">Paid</option>
                        <option value="0">Unpaid</option>
                    </select>

                    <button
                        onClick={() =>
                            setFilters({
                                order_unique_id: "",
                                tracking: "",
                                email: "",
                                status: "",
                                type: "",
                                payment_status: "",
                            })
                        }
                        className="border p-2 rounded bg-gray-100 hover:bg-gray-200"
                    >
                        Reset
                    </button>
                </div>

                {/* ================= Table ================= */}
                <div className="overflow-x-auto bg-white rounded shadow">
                    <table className="w-full text-sm">
                        <thead className="bg-gray-100">
                            <tr>
                                <th className="p-2 border">Name</th>
                                <th className="p-2 border">Email</th>
                                <th className="p-2 border">Order #</th>
                                {/*<th className="p-2 border">Tracking #</th>*/}
                                <th className="p-2 border">Fare</th>
                                <th className="p-2 border">Weight</th>
                                <th className="p-2 border">Status</th>
                                <th className="p-2 border">Payment</th>
                                <th className="p-2 border">Date</th>
                                <th className="p-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filteredOrders.length ? (
                                filteredOrders.map((order) => (
                                    <tr
                                        key={order.id}
                                        className="hover:bg-gray-50"
                                    >
                                        <td className="border p-2">
                                            {order.rider.name}
                                        </td>
                                        <td className="border p-2">
                                            {order.rider.email}
                                        </td>
                                        <td className="border p-2">
                                            #{order.order_unique_id}
                                        </td>
                                        {/*<td className="border p-2">*/}
                                        {/*    {order.order_attempt*/}
                                        {/*        ?.order_tracking_number || "-"}*/}
                                        {/*</td>*/}
                                        <td className="border p-2">
                                            ${ order?.order_attempt?.fare}
                                        </td>
                                        <td className="border p-2">
                                            {order.weight} kg
                                        </td>
                                        <td className="border p-2">
                                            {ORDER_STATUS_NAME[order.status]}
                                        </td>
                                        <td className="border p-2">
                                            {PAYMENT_STATUS_NAME[
                                                order.order_attempt
                                                    ?.payment_status || 0
                                            ]}
                                        </td>
                                        <td className="border p-2">
                                            {new Date(
                                                order.created_at
                                            ).toLocaleDateString()}
                                        </td>
                                        <td className="border p-2 text-center">
                                            <a
                                                href={`/orders/show/${order.id}`}
                                                className="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700"
                                            >
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td
                                        colSpan={8}
                                        className="text-center p-4 text-gray-500"
                                    >
                                        No orders found
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </RiderDashboardLayout>
        </AppLayout>
    );
}
