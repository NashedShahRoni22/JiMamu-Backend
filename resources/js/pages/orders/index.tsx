import AppLayout from "@/layouts/app-layout";
import { Head, usePage, Link } from "@inertiajs/react";
import React, { useState } from "react";
import { DataTable } from "@/components/ui/datatable";
import { ColumnDef } from "@tanstack/react-table";

export default function Index() {
    const { orders } = usePage().props;

    type Order = {
        id: number;
        order_unique_id: string;
        status: string;
        customer_name: string;
        customer_email: string;
        customer_image: string;
        payment_status: string;
        weight: number;
        action: string;
    };

    const columns: ColumnDef<Order>[] = [
        { accessorKey: "id", header: "ID" },
        { accessorKey: "order_unique_id", header: "Order ID" },
        { accessorKey: "status", header: "Status" },
        { accessorKey: "customer_name", header: "Customer Name" },
        { accessorKey: "customer_email", header: "Customer Email" },
        {
            accessorKey: "customer_image",
            header: "Image",
            cell: ({ row }) =>
                row.original.customer_image ? (
                    <img
                        src={row.original.customer_image}
                        alt={row.original.customer_name}
                        className="w-10 h-10 rounded-full object-cover"
                    />
                ) : (
                    <span>No Image</span>
                )
        },
        { accessorKey: "payment_status", header: "Payment Status" },
        { accessorKey: "weight", header: "Weight" },
        {
            accessorKey: "action",
            header: "Action",
            cell: ({ row }) => (
                <Link
                    href={`/orders/show/${row.original.id}`}
                    className="text-blue-500 hover:underline"
                >
                    View
                </Link>
            ),
        },
    ];

    // Filter state
    const [orderId, setOrderId] = useState("");
    const [customerEmail, setCustomerEmail] = useState("");
    const [status, setStatus] = useState("");

    // Filtering logic
    const filteredOrders = (orders as Order[]).filter((order) => {
        return (
            (orderId ? order.order_unique_id.includes(orderId) : true) &&
            (customerEmail
                ? order.customer_email
                    .toLowerCase()
                    .includes(customerEmail.toLowerCase())
                : true) &&
            (status ? order.status.toLowerCase() === status.toLowerCase() : true)
        );
    });

    return (
        <AppLayout>
            <div className="p-4">
                <Head title="Orders" />
                <h1 className="text-2xl font-bold mb-4">Orders</h1>

                {/* Filter Inputs */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <input
                        type="text"
                        placeholder="Order ID"
                        value={orderId}
                        onChange={(e) => setOrderId(e.target.value)}
                        className="border p-2 rounded w-full"
                    />
                    <input
                        type="text"
                        placeholder="Customer Email"
                        value={customerEmail}
                        onChange={(e) => setCustomerEmail(e.target.value)}
                        className="border p-2 rounded w-full"
                    />
                    <select
                        value={status}
                        onChange={(e) => setStatus(e.target.value)}
                        className="border p-2 rounded w-full"
                    >
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="picked">Picked</option>
                        <option value="shipping">Shipping</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                    <button
                        onClick={() => {
                            setOrderId("");
                            setCustomerEmail("");
                            setStatus("");
                        }}
                        className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                    >
                        Clear Filters
                    </button>

                </div>
                <DataTable columns={columns} data={filteredOrders} />
            </div>
        </AppLayout>
    );
}
