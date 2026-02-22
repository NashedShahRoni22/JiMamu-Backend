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
type BankInformation = {
    name: string;
    account_number: string;
    institution_number: string;
    transit_number: string;
    bank_document: string;
};
type Wallet = {
    balance: string;
    walletHistory: WalletHistory[];
};

interface WalletProps {
    wallet: Wallet;
    bankInformation: BankInformation | null;
}



// ================= Component =================
export default function Wallet({ wallet, bankInformation }: WalletProps) {
    const [filters, setFilters] = useState({
        status: "",
        from_date: "",
        to_date: "",
    });

    const history = wallet?.walletHistory ?? [];
    const [showModal, setShowModal] = useState(false); // show modal

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

    // bank information data destructure



    return (
        <AppLayout>
            <RiderDashboardLayout>
                <Head title="Wallet" />

                <div className="flex justify-between items-center mb-4">
                    <h2 className="text-2xl font-bold">Wallet History</h2>

                    <button
                        onClick={() => setShowModal(true)}
                        className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Bank Info
                    </button>
                </div>

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
                {/*Bank information modal*/}
                {showModal && (
                    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div className="bg-white rounded-lg shadow-lg w-full max-w-md p-6">

                            {/* Header */}
                            <div className="flex justify-between items-center mb-4">
                                <h3 className="text-lg font-semibold">Bank Information</h3>
                                <button
                                    onClick={() => setShowModal(false)}
                                    className="text-gray-500 hover:text-gray-700"
                                >
                                    ✕
                                </button>
                            </div>

                            {/* Content */}
                            {bankInformation ? (
                                <div className="space-y-2 text-sm">
                                    <p><strong>Account Name:</strong> {bankInformation.name}</p>
                                    <p><strong>Account Number:</strong> {bankInformation.account_number}</p>
                                    <p><strong>Institution Number:</strong> {bankInformation.institution_number}</p>
                                    <p><strong>Transit Number:</strong> {bankInformation.transit_number}</p>

                                    {/* Bank Document */}
                                    <div>
                                        <strong>Document:</strong>
                                        <img
                                            src={bankInformation.bank_document}
                                            alt="Bank Document"
                                            className="mt-2 rounded border w-full max-h-64 object-contain"
                                        />
                                    </div>
                                </div>
                            ) : (
                                <p className="text-gray-500">No bank information found</p>
                            )}

                            {/* Footer */}
                            <div className="mt-4 text-right">
                                <button
                                    onClick={() => setShowModal(false)}
                                    className="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                                >
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                )}
            </RiderDashboardLayout>
        </AppLayout>
    );
}
