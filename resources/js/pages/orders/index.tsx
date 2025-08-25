// resources/js/Pages/Orders/Index.tsx
import AppLayout from "@/layouts/app-layout";
import { Head, usePage, Link } from "@inertiajs/react";
import React from "react";
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
            cell: ({ row }) => (
                row.original.customer_image ? (
                    <img
                        src={row.original.customer_image}
                        alt={row.original.customer_name}
                        className="w-10 h-10 rounded-full object-cover"
                    />
                ) : (
                    <span>No Image</span>
                )
            )
        },
        { accessorKey: "payment_status", header: "Payment Status" },
        { accessorKey: "weight", header: "Weight" },
        {
            accessorKey: "action",
            header: "Action",
            cell: ({ row }) => (
                <Link
                    href={`/orders/show/${row.original.id}`} // your URL
                    className="text-blue-500 hover:underline"
                >
                    View
                </Link>
            ),
        },
    ];

    return (
        <AppLayout>
            <Head title="Orders" />
            <h1 className="text-2xl font-bold mb-4">Orders</h1>
            <DataTable columns={columns} data={orders as any} />
        </AppLayout>
    );
}
