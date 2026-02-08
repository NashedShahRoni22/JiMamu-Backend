import React, { useMemo, useState } from "react";
import AppLayout from "@/layouts/app-layout";
import RiderDashboardLayout from "@/layouts/RiderDashboardLayout";
import { Head } from "@inertiajs/react";

// ================= Types =================
type WalletHistory = {
    amount: string;
    purpose_of_transaction: string;
    transaction_type: "credit" | "debit";
    status: string; // pending / approved / cancelled
    created_at: string; // "01-06-2025 06:23 AM"
};

type Wallet = {
    balance: string;
    walletHistory: WalletHistory[];
};

interface WalletProps {
    wallet: Wallet;
}

// ================= Component =================
export default function Wallet({ wallet }: WalletProps) {
    const [filters, setFilters] = useState({
        status: "",
        from_date: "",
        to_date: "",
    });

    const history = wallet?.walletHistory ?? [];

    const filteredHistory = useMemo(() => {
        return history.filter((item) => {
            const itemDate = new Date(item.created_at);

            const fromOk = !filters.from_date
                ? true
                : itemDate >= new Date(filters.from_date);

            const toOk = !filters.to_date
                ? true
                : itemDate <= new Date(filters.to_date);

            const statusOk = !filters.status
                ? true
                : item.status.toLowerCase() === filters.status.toLowerCase();

            return fromOk && toOk && statusOk;
        });
    }, [history, filters]);

    return (
        <AppLayout>
            <RiderDashboardLayout>
                <Head title="Wallet" />

                <h2 className="text-2xl font-bold mb-4">Wallet History</h2>

                {/* ================= Filters (Same Flow) ================= */}
                <div className="grid grid-cols-2 md:grid-cols-6 gap-2 mb-4">
                    <input
                        type="date"
                        className="border p-2 rounded"
                        value={filters.from_date}
                        onChange={(e) =>
                            setFilters({ ...filters, from_date: e.target.value })
                        }
                    />

                    <input
                        type="date"
                        className="border p-2 rounded"
                        value={filters.to_date}
                        onChange={(e) =>
                            setFilters({ ...filters, to_date: e.target.value })
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
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                    <button
                        onClick={() =>
                            setFilters({
                                status: "",
                                from_date: "",
                                to_date: "",
                            })
                        }
                        className="border p-2 rounded bg-gray-100 hover:bg-gray-200"
                    >
                        Reset
                    </button>
                </div>

                 {/*================= Table (Same Style as Orders) =================*/}
                <div className="overflow-x-auto bg-white rounded shadow">
                    <table className="w-full text-sm">
                        <thead className="bg-gray-100">
                        <tr>
                            <th className="p-2 border">Date</th>
                            <th className="p-2 border">Amount</th>
                            <th className="p-2 border">Type</th>
                            <th className="p-2 border">Purpose</th>
                            <th className="p-2 border">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        {filteredHistory.length ? (
                            filteredHistory.map((item, idx) => (
                                <tr key={idx} className="hover:bg-gray-50">
                                    <td className="border p-2">
                                        {item.created_at}
                                    </td>
                                    <td className="border p-2 font-medium">
                                        ${item.amount}
                                    </td>
                                    <td className="border p-2 capitalize">
                                        {item.transaction_type}
                                    </td>
                                    <td className="border p-2">
                                        {item.purpose_of_transaction}
                                    </td>
                                    <td className="border p-2">
                                            <span
                                                className={`px-2 py-1 rounded text-xs font-medium
                                                ${
                                                    item.status === "approved"
                                                        ? "bg-green-100 text-green-700"
                                                        : item.status === "pending"
                                                            ? "bg-yellow-100 text-yellow-700"
                                                            : "bg-red-100 text-red-700"
                                                }`}
                                            >
                                                {item.status}
                                            </span>
                                    </td>
                                </tr>
                            ))
                        ) : (
                            <tr>
                                <td
                                    colSpan={5}
                                    className="text-center p-4 text-gray-500"
                                >
                                    No wallet history found
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
