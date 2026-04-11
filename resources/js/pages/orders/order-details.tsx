import AppLayout from "@/layouts/app-layout";
import { Head, usePage, Link } from "@inertiajs/react";
import React from "react";

type Order = {
    id: number;
    order_unique_id: string;
    status: string | number;
    payment_status: string | number;
    weight: number;
    weight_type: string;
    pickup_address: string;
    drop_address: string;
    package: {
        name: string;
    };
    customer: {
        name: string;
        email: string;
        phone_number: string;
        profile_image?: string;
    };
    rider: {
        name: string;
        email: string;
        phone_number: string;
        profile_image?: string;
    };
    receiver_information?: {
        receiver_name: string;
        receiver_phone: string;
        remarks: string;
    };
    sender_information?: {
        sender_name: string;
        sender_phone: string;
        remarks: string;
    };
};

interface Props {
    order: Order;
    [key: string]: any;
}

const ORDER_STATUS_MAP: Record<number, string> = {
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

const PAYMENT_STATUS_MAP: Record<number, string> = {
    1: "Paid",
    2: "Unpaid",
    3: "Cancelled",
};

const PAYMENT_STATUS_BADGE: Record<number, string> = {
    1: "bg-green-100 text-green-800",
    2: "bg-red-100 text-red-800",
    3: "bg-gray-100 text-gray-800",
};

function Avatar({ name, image, size = "md" }: { name: string; image?: string; size?: "sm" | "md" | "lg" }) {
    const sizeClass = size === "lg" ? "w-16 h-16 text-xl" : size === "sm" ? "w-8 h-8 text-xs" : "w-12 h-12 text-sm";
    const initials = name?.split(" ").map((n) => n[0]).join("").toUpperCase().slice(0, 2) ?? "?";

    if (image) {
        return (
            <img
                src={image}
                alt={name}
                className={`${sizeClass} rounded-full object-cover border border-gray-200`}
            />
        );
    }
    return (
        <div className={`${sizeClass} rounded-full bg-blue-100 text-blue-800 flex items-center justify-center font-semibold`}>
            {initials}
        </div>
    );
}

function InfoRow({ label, value }: { label: string; value?: string | null }) {
    return (
        <div className="flex flex-col gap-0.5 py-2 border-b border-gray-100 last:border-0">
            <span className="text-xs text-gray-500">{label}</span>
            <span className="text-sm text-gray-900 font-medium">{value || "—"}</span>
        </div>
    );
}

export default function Show() {
    const { order } = usePage<Props>().props;

    const statusNum = Number(order.status);
    const paymentNum = Number(order.payment_status);
    const statusLabel = ORDER_STATUS_MAP[statusNum] ?? String(order.status);
    const paymentLabel = PAYMENT_STATUS_MAP[paymentNum] ?? String(order.payment_status);
    const statusBadge = ORDER_STATUS_BADGE[statusNum] ?? "bg-gray-100 text-gray-800";
    const paymentBadge = PAYMENT_STATUS_BADGE[paymentNum] ?? "bg-gray-100 text-gray-800";

    return (
        <AppLayout>
            <Head title={`Order #${order.order_unique_id}`} />

            <div className="p-4 md:p-6 max-w-5xl mx-auto space-y-6">

                {/* Header */}
                <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <Link href="/orders" className="text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1 mb-1">
                            &#8592; Back to Orders
                        </Link>
                        <h1 className="text-xl font-bold text-gray-900">
                            Order <span className="text-blue-600">#{order.order_unique_id}</span>
                        </h1>
                    </div>
                    <div className="flex items-center gap-2">
                        <span className={`px-3 py-1 rounded-full text-xs font-semibold ${statusBadge}`}>
                            {statusLabel}
                        </span>
                        <span className={`px-3 py-1 rounded-full text-xs font-semibold ${paymentBadge}`}>
                            {paymentLabel}
                        </span>
                    </div>
                </div>

                {/* Order Summary */}
                <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                    {[
                        { label: "Order ID", value: `#${order.order_unique_id}` },
                        { label: "Package", value: order.package?.name },
                        { label: "Weight", value: `${order.weight} ${order.weight_type == "1" ? "kg" : "lbs"}` },
                        { label: "Order Status", value: statusLabel },
                    ].map((item) => (
                        <div
                            key={item.label}
                            className="bg-gray-50 rounded-lg p-3 border border-gray-100"
                        >
                            <p className="text-xs text-gray-500 mb-1">{item.label}</p>
                            <p className="text-sm font-semibold text-gray-900">{item.value ?? "—"}</p>
                        </div>
                    ))}
                </div>

                {/* Addresses */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="bg-white border border-gray-200 rounded-xl p-4">
                        <div className="flex items-center gap-2 mb-3">
                            <div className="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                <svg className="w-3 h-3 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
                                </svg>
                            </div>
                            <h2 className="text-sm font-semibold text-gray-700">Pickup Address</h2>
                        </div>
                        <p className="text-sm text-gray-800">{order.pickup_address || "—"}</p>
                    </div>

                    <div className="bg-white border border-gray-200 rounded-xl p-4">
                        <div className="flex items-center gap-2 mb-3">
                            <div className="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                                <svg className="w-3 h-3 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
                                </svg>
                            </div>
                            <h2 className="text-sm font-semibold text-gray-700">Drop Address</h2>
                        </div>
                        <p className="text-sm text-gray-800">{order.drop_address || "—"}</p>
                    </div>
                </div>

                {/* Customer & Rider */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {/* Customer */}
                    <div className="bg-white border border-gray-200 rounded-xl p-4">
                        <h2 className="text-sm font-semibold text-gray-700 mb-3">Customer</h2>
                        {order.customer ? (
                            <div className="flex items-start gap-3">
                                <Avatar name={order.customer.name} image={order.customer.profile_image} size="md" />
                                <div className="flex-1 min-w-0">
                                    <InfoRow label="Name" value={order.customer.name} />
                                    <InfoRow label="Email" value={order.customer.email} />
                                    <InfoRow label="Phone" value={order.customer.phone_number} />
                                </div>
                            </div>
                        ) : (
                            <p className="text-sm text-gray-400">No customer info</p>
                        )}
                    </div>

                    {/* Rider */}
                    <div className="bg-white border border-gray-200 rounded-xl p-4">
                        <h2 className="text-sm font-semibold text-gray-700 mb-3">Rider</h2>
                        {order.rider ? (
                            <div className="flex items-start gap-3">
                                <Avatar name={order.rider.name} image={order.rider.profile_image} size="md" />
                                <div className="flex-1 min-w-0">
                                    <InfoRow label="Name" value={order.rider.name} />
                                    <InfoRow label="Email" value={order.rider.email} />
                                    <InfoRow label="Phone" value={order.rider.phone_number} />
                                </div>
                            </div>
                        ) : (
                            <p className="text-sm text-gray-400">No rider info</p>
                        )}
                    </div>
                </div>

                {/* Sender & Receiver */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {/* Sender */}
                    <div className="bg-white border border-gray-200 rounded-xl p-4">
                        <h2 className="text-sm font-semibold text-gray-700 mb-3">Sender Information</h2>
                        {order.sender_information ? (
                            <>
                                <InfoRow label="Name" value={order.sender_information.sender_name} />
                                <InfoRow label="Phone" value={order.sender_information.sender_phone} />
                                <InfoRow label="Remarks" value={order.sender_information.remarks} />
                            </>
                        ) : (
                            <p className="text-sm text-gray-400">No sender info</p>
                        )}
                    </div>

                    {/* Receiver */}
                    <div className="bg-white border border-gray-200 rounded-xl p-4">
                        <h2 className="text-sm font-semibold text-gray-700 mb-3">Receiver Information</h2>
                        {order.receiver_information ? (
                            <>
                                <InfoRow label="Name" value={order.receiver_information.receiver_name} />
                                <InfoRow label="Phone" value={order.receiver_information.receiver_phone} />
                                <InfoRow label="Remarks" value={order.receiver_information.remarks} />
                            </>
                        ) : (
                            <p className="text-sm text-gray-400">No receiver info</p>
                        )}
                    </div>
                </div>

            </div>
        </AppLayout>
    );
}