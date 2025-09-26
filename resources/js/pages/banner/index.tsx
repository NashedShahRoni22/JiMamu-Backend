import AppLayout from "@/layouts/app-layout";
import { Head, usePage, Link } from "@inertiajs/react";
import React, { useState } from "react";
import { DataTable } from "@/components/ui/datatable";
import { ColumnDef } from "@tanstack/react-table";

export default function Index() {
    const { banners } = usePage().props;

    type Banner = {
        id: number;
        image_name: string;
        status: "active" | "inactive";
    };

    const columns: ColumnDef<Banner>[] = [
        { accessorKey: "id", header: "ID" },
        {
            accessorKey: "image_name",
            header: "Image",
            cell: ({ row }) =>
                row.original.image_name ? (
                    <img
                        src={`/storage/${row.original.image_name}`}
                        alt="Banner"
                        className="w-20 h-12 object-cover rounded"
                    />
                ) : (
                    <span>No Image</span>
                )
        },
        {
            accessorKey: "status",
            header: "Status",
            cell: ({ row }) => (
                <span
                    className={`px-2 py-1 rounded text-xs font-semibold ${
                        row.original.status === "active"
                            ? "bg-green-100 text-green-700"
                            : "bg-red-100 text-red-700"
                    }`}
                >
                    {row.original.status}
                </span>
            ),
        },
        {
            accessorKey: "action",
            header: "Action",
            cell: ({ row }) => (
                <Link
                    href={route("banner.edit", { id: row.original.id })}
                    className="text-blue-500 hover:underline"
                >
                    Update
                </Link>
            ),
        },
    ];

    // Filters
    const [status, setStatus] = useState("");

    const filteredBanners = (banners as Banner[]).filter((banner) => {
        return status
            ? banner.status.toLowerCase() === status.toLowerCase()
            : true;
    });

    return (
        <AppLayout>
            <div className="p-4">
                <Head title="Banners" />

                {/* Header + Add Button */}
                <div className="flex justify-between items-center mb-4">
                    <h1 className="text-2xl font-bold">Banners</h1>
                    <Link
                        href={route("banner.create")}
                        className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                    >
                        Add Banner
                    </Link>
                </div>

                {/* Filter Inputs */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <select
                        value={status}
                        onChange={(e) => setStatus(e.target.value)}
                        className="border p-2 rounded w-full"
                    >
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    <button
                        onClick={() => {
                            setStatus("");
                        }}
                        className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                    >
                        Clear Filters
                    </button>
                </div>

                <DataTable columns={columns} data={filteredBanners} />
            </div>
        </AppLayout>
    );
}
