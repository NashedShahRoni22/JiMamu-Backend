import React, { useMemo, useState } from "react";
import AppLayout from "@/layouts/app-layout";
import RiderDashboardLayout from "@/layouts/RiderDashboardLayout";
import { Head } from "@inertiajs/react";

// ================= Types =================
type Customer = {
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
    customer: Customer;
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

const ORDER_STATUS_BADGE: Record<number, string> = {
    1: "bg-yellow-100 text-yellow-800",
    2: "bg-blue-100 text-blue-800",
    3: "bg-purple-100 text-purple-800",
    4: "bg-indigo-100 text-indigo-800",
    5: "bg-green-100 text-green-800",
    6: "bg-red-100 text-red-800",
};

const PAYMENT_STATUS_NAME: Record<number, string> = {
    1: "Paid",
    2: "Unpaid",
    3: "Cancelled",
};

const PAYMENT_STATUS_BADGE: Record<number, string> = {
    1: "bg-green-100 text-green-800",
    2: "bg-red-100 text-red-800",
    3: "bg-gray-100 text-gray-800",
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
                        .includes(filters.order_unique_id.toLowerCase())) &&
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

                <h2 className="text-2xl font-bold mb-4">Order Reports</h2>

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
                        <option value="2">Unpaid</option>
                        <option value="3">Cancelled</option>
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
                                            {order.customer.name}
                                        </td>
                                        <td className="border p-2">
                                            {order.customer.email}
                                        </td>
                                        <td className="border p-2">
                                            {order.order_unique_id}
                                        </td>
                                        {/*<td className="border p-2">*/}
                                        {/*    {order.order_attempt*/}
                                        {/*        ?.order_tracking_number || "-"}*/}
                                        {/*</td>*/}
                                        <td className="border p-2">
                                            {order?.order_attempt?.fare ? `$${order.order_attempt.fare}` : "-"}
                                        </td>
                                        <td className="border p-2">
                                            {order.weight} kg
                                        </td>
                                        <td className="border p-2">
                                            <span className={`px-2 py-1 rounded-full text-xs font-semibold ${ORDER_STATUS_BADGE[order.status] ?? "bg-gray-100 text-gray-800"}`}>
                                                {ORDER_STATUS_NAME[order.status] ?? "Unknown"}
                                            </span>
                                        </td>
                                        <td className="border p-2">
                                            <span className={`px-2 py-1 rounded-full text-xs font-semibold ${PAYMENT_STATUS_BADGE[order.order_attempt?.payment_status ?? 2] ?? "bg-gray-100 text-gray-800"}`}>
                                                {PAYMENT_STATUS_NAME[order.order_attempt?.payment_status ?? 2] ?? "Unknown"}
                                            </span>
                                        </td>
                                        <td className="border p-2">
                                            {new Date(
                                                order.created_at
                                            ).toLocaleDateString()}
                                        </td>
                                        <td className="border p-2 text-center">
                                               <a href={`/orders/show/${order.id}`}
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
                                        colSpan={9}
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