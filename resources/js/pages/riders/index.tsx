import AppLayout from "@/layouts/app-layout";
import { Head, usePage, Link } from "@inertiajs/react";
import React, { useState } from "react";
import { DataTable } from "@/components/ui/datatable";
import { ColumnDef } from "@tanstack/react-table";

export default function Index() {
    const { riders } = usePage().props;

    type riderRequest = {
        id: number;
        name: string;
        email: string;
        phone_number: string;
        profile_image: string;
        review_status_text: string;
    };

    const columns: ColumnDef<riderRequest>[] = [
        { accessorKey: "id", header: "ID" },
        { accessorKey: "name", header: "Name" },
        { accessorKey: "email", header: "Email" },
        { accessorKey: "phone_number", header: "Phone Number" },
        { accessorKey: "review_status_text", header: "Review Status" },
        {
            accessorKey: "profile_image",
            header: "Image",
            cell: ({ row }) =>
                row.original.profile_image ? (
                    <img
                        src={row.original.profile_image}
                        alt={row.original.name}
                        className="w-10 h-10 rounded-full object-cover"
                    />
                ) : (
                    <span>No Image</span>
                ),
        },
        {
            accessorKey: "action",
            header: "Action",
            cell: ({ row }) => (
                <>
                    <Link
                        href={`/riders/rider/account/review/details/${row.original.id}`}
                        className="text-blue-500 hover:underline"
                    >
                        Views |
                    </Link>
                    <Link
                        href={`/users/dashboard/${row.original.id}`}
                        className="text-blue-500 hover:underline"
                    >
                        Report
                    </Link>
                </>


            ),
        },
    ];

    // Filter state
    const [phoneNumber, setPhoneNumber] = useState("");
    const [customerEmail, setCustomerEmail] = useState("");
    const [status, setStatus] = useState("");

    // Filtering logic
    const filteredRiders = (riders as riderRequest[]).filter((rider) => {
        return (
            (phoneNumber ? rider.phone_number.toString().includes(phoneNumber) : true) &&
            (customerEmail ? rider.email.toLowerCase().includes(customerEmail.toLowerCase()) : true) &&
            (status ? rider.review_status_text.toLowerCase() === status.toLowerCase() : true)
        );
    });

    return (
        <AppLayout>
            <div className="p-4">
                <Head title="Rider Account Review" />
                <h1 className="text-2xl font-bold mb-4">Rider Account Review</h1>

                {/* Filter Inputs */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <input
                        type="text"
                        placeholder="Rider Phone Number"
                        value={phoneNumber}
                        onChange={(e) => setPhoneNumber(e.target.value)}
                        className="border p-2 rounded w-full"
                    />
                    <input
                        type="text"
                        placeholder="Email"
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
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>

                    <button
                        onClick={() => {
                            setPhoneNumber("");
                            setCustomerEmail("");
                            setStatus("");
                        }}
                        className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                    >
                        Clear Filters
                    </button>
                </div>

                <DataTable columns={columns} data={filteredRiders} />
            </div>
        </AppLayout>
    );
}
