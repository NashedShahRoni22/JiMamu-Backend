import AppLayout from "@/layouts/app-layout";
import { Head, usePage, Link } from "@inertiajs/react";
import React, { useState } from "react";
import { DataTable } from "@/components/ui/datatable";
import { ColumnDef } from "@tanstack/react-table";

export default function Index() {
    const { orders } = usePage().props;

    type Order = {
        id: number;
        customer_image: string;
        customer_name: string;
        customer_email: string;
        order_unique_id: string;
        status: string;
        payment_status: string;
        weight: number;
        created_at: string;
        action: string;
    };

    const orderStatusBadge = (status: string) => {
        const map: Record<string, string> = {
            pending:   "bg-yellow-100 text-yellow-800",
            confirmed: "bg-blue-100 text-blue-800",
            picked:    "bg-purple-100 text-purple-800",
            shipping:  "bg-indigo-100 text-indigo-800",
            delivered: "bg-green-100 text-green-800",
            cancelled: "bg-red-100 text-red-800",
        };
        const classes = map[status.toLowerCase()] ?? "bg-gray-100 text-gray-800";
        return (
            <span className={`px-2 py-1 rounded-full text-xs font-semibold capitalize ${classes}`}>
                {status}
            </span>
        );
    };

    const paymentStatusBadge = (status: string) => {
        const map: Record<string, string> = {
            paid:      "bg-green-100 text-green-800",
            unpaid:    "bg-red-100 text-red-800",
            cancelled: "bg-gray-100 text-gray-800",
        };
        const classes = map[status.toLowerCase()] ?? "bg-gray-100 text-gray-800";
        return (
            <span className={`px-2 py-1 rounded-full text-xs font-semibold capitalize ${classes}`}>
                {status}
            </span>
        );
    };

    const columns: ColumnDef<Order>[] = [
        { accessorKey: "id", header: "ID" },
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
                ),
        },
        { accessorKey: "customer_name", header: "Customer Name" },
        { accessorKey: "customer_email", header: "Customer Email" },
        { accessorKey: "order_unique_id", header: "Order ID" },
        {
            accessorKey: "status",
            header: "Order Status",
            cell: ({ row }) => orderStatusBadge(row.original.status),
        },
        {
            accessorKey: "payment_status",
            header: "Payment Status",
            cell: ({ row }) => paymentStatusBadge(row.original.payment_status),
        },
        { accessorKey: "weight", header: "Weight" },
        { accessorKey: "created_at", header: "Order Date" },
        {
            accessorKey: "action",
            header: "Action",
            cell: ({ row }) => (
                <Link
                    href={`/orders/show/${row.original.id}`}
                    className="px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                    View
                </Link>
            ),
        },
    ];

    const [orderId, setOrderId] = useState("");
    const [customerEmail, setCustomerEmail] = useState("");
    const [status, setStatus] = useState("");

    const filteredOrders = (orders as Order[]).filter((order) => {
        return (
            (orderId ? order.order_unique_id.includes(orderId) : true) &&
            (customerEmail
                ? order.customer_email.toLowerCase().includes(customerEmail.toLowerCase())
                : true) &&
            (status ? order.status.toLowerCase() === status.toLowerCase() : true)
        );
    });

    return (
        <AppLayout>
            <div className="p-4">
                <Head title="Orders" />
                <h1 className="text-2xl font-bold mb-4">Orders</h1>

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