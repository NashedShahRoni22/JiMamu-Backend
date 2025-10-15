import React from "react";
import RiderDashboardLayout from "@/layouts/RiderDashboardLayout";
import { Head } from "@inertiajs/react";

type Wallet = {
    id: number;
    user_id: number;
    balance: number;
    updated_at?: string;
};

interface WalletProps {
    wallet: Wallet;
}

export default function Wallet({ wallet }: WalletProps) {
    return (
        <RiderDashboardLayout>
            <Head title="Wallet" />
            <h2 className="text-2xl font-bold mb-4">Wallet Details</h2>
            <p>Current balance: ${wallet.balance.toFixed(2)}</p>
        </RiderDashboardLayout>
    );
}
