import React, { ReactNode } from "react";
import { Link, usePage } from "@inertiajs/react";
import type { PageProps as InertiaPageProps } from "@inertiajs/core"; // ✅ use core, not react

interface RiderDashboardPageProps extends InertiaPageProps {
    auth: {
        user: {
            id: number;
            name: string;
        };
    };
    walletBalance: number;
    riderId: number;
}

interface RiderDashboardLayoutProps {
    children: ReactNode;
}

export default function RiderDashboardLayout({ children }: RiderDashboardLayoutProps) {
    const { auth, walletBalance, riderId } = usePage<RiderDashboardPageProps>().props;

    const sidebarItems = [
        { name: "Rider Delivery Orders", route: "users.report.dashboard" },
        { name: "Requested Orders", route: "users.report.requested.order" },
        { name: "Wallet", route: "users.report.wallet" },
        // add more items later (Completed Orders, Wallet, etc.)
    ];

    return (
        <div className="flex min-h-screen bg-gray-100">
            {/* Sidebar */}
            <aside className="w-64 bg-white shadow-md p-4">
                <h2 className="text-xl font-bold mb-4">Rider Panel</h2>
                {/*{sidebarItems.map((item) => (*/}
                {/*    <Link*/}
                {/*        key={item.route}*/}
                {/*        href={route(item.route, riderId)} // ✅ passing rider ID dynamically*/}
                {/*        className={`block px-4 py-2 rounded mb-1 ${*/}
                {/*            route().current(item.route)*/}
                {/*                ? "bg-blue-500 text-white"*/}
                {/*                : "text-gray-700 hover:bg-gray-200"*/}
                {/*        }`}*/}
                {/*    >*/}
                {/*        {item.name}*/}
                {/*    </Link>*/}
                {/*))}*/}
                {sidebarItems.map((item) => (
                    <Link
                        key={item.route}
                        href={riderId ? route(item.route, riderId) : "#"}
                        className={`block px-4 py-2 rounded mb-1 ${
                            route().current(item.route)
                                ? "bg-blue-500 text-white"
                                : "text-gray-700 hover:bg-gray-200"
                        } ${!riderId ? "opacity-50 pointer-events-none" : ""}`}
                    >
                        {item.name}
                    </Link>
                ))}
            </aside>

            {/* Main Section */}
            <div className="flex-1 flex flex-col">
                <header className="bg-white border-b p-4 flex justify-between items-center">
                    <h1 className="text-lg font-semibold">
                        Welcome, {auth?.user?.name ?? "Rider"}
                    </h1>
                    <div className="text-blue-600 font-bold">
                        Wallet: ${walletBalance}
                    </div>
                </header>

                <main className="p-6 flex-1">{children}</main>
            </div>
        </div>
    );
}
