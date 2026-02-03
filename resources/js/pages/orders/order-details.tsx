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
    pickup_address: string,
    drop_address: string,
    package: {
        name: string
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

export default function Show() {
    const { order } = usePage<Props>().props;
    console.log(order)

    // Convert numeric status to readable text if needed
    const statusMap: Record<number, string> = {
        1: "Pending",
        2: "Processing",
        3: "Delivered"
    };
    const paymentStatusMap: Record<number, string> = {
        1: "Unpaid",
        2: "Paid"
    };

    return (
        <AppLayout>
            <Head title={`Order #${order.order_unique_id}`} />

            <div className="w-full p-4 bg-white shadow rounded">
                <h1 className="text-2xl font-bold mb-4">Order Details</h1>

                <div className="mb-6">
                    <Link href="/orders" className="text-blue-500 hover:underline">
                        &larr; Back to Orders
                    </Link>
                </div>

                {/* Main Details Card */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 border p-4 rounded-lg shadow-sm">
                    {/* Left Side */}
                    <div>
                        <p><strong>Order ID:</strong> {order.order_unique_id}</p>
                        <p><strong>Status:</strong> {statusMap[Number(order.status)] || order.status}</p>
                        <p><strong>Payment Status:</strong> {paymentStatusMap[Number(order.payment_status)] || order.payment_status}</p>
                        <p><strong>Weight:</strong> {order.weight} {order.weight_type == 1? "kg" : 'lbs'}</p>
                    </div>

                    {/* Right Side */}
                    <div>
                        <p><strong>Package Type:</strong> {order.package.name}</p>
                    </div>
                </div>
                {/* Pickup and Drop Information */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    {/* Pickup */}
                    <div className="border p-4 rounded-lg shadow-sm">
                        <h2 className="text-lg font-semibold mb-2">Pickup Address</h2>
                        {order.pickup_address ? (
                            <>
                                <p>{order.pickup_address}</p>
                            </>
                        ) : (
                            <p>N/A</p>
                        )}
                    </div>

                    {/* Drop Info */}
                    <div className="border p-4 rounded-lg shadow-sm">
                        <h2 className="text-lg font-semibold mb-2">Drop Address</h2>
                        {order.drop_address ? (
                            <>
                                <p>{order.drop_address}</p>
                            </>
                        ) : (
                            <p>N/A</p>
                        )}
                    </div>
                </div>
                {/* Customer and Rider Information */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    {/* Sender Info */}
                    <div className="border p-4 rounded-lg shadow-sm">
                        <h2 className="text-lg font-semibold mb-2">Customer Information</h2>
                        {order.customer ? (
                            <>
                                <p><strong>Name:</strong> {order.customer.name}</p>
                                <p><strong>Phone:</strong> {order.customer.email}</p>
                                <p><strong>Remarks:</strong> {order.customer.phone_number}</p>
                                {order.customer.profile_image ? (
                                    <img
                                        src={order.customer.profile_image}
                                        alt={order.customer.name}
                                        className="w-32 h-32 rounded-full object-cover mt-2 border"
                                    />
                                ) : (
                                    <p>No customer image</p>
                                )}
                            </>
                        ) : (
                            <p>No sender info</p>
                        )}
                    </div>

                    {/* Receiver Info */}
                    <div className="border p-4 rounded-lg shadow-sm">
                        <h2 className="text-lg font-semibold mb-2">Rider Information</h2>
                        {order.rider ? (
                            <>
                                <p><strong>Name:</strong> {order.rider.name}</p>
                                <p><strong>Phone:</strong> {order.rider.email}</p>
                                <p><strong>Remarks:</strong> {order.rider.phone_number}</p>
                                {order.rider.profile_image ? (
                                    <img
                                        src={order.rider.profile_image}
                                        alt={order.rider.name}
                                        className="w-32 h-32 rounded-full object-cover mt-2 border"
                                    />
                                ) : (
                                    <p>No customer image</p>
                                )}
                            </>
                        ) : (
                            <p>No receiver info</p>
                        )}
                    </div>
                </div>

                {/* Sender & Receiver Information */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    {/* Sender Info */}
                    <div className="border p-4 rounded-lg shadow-sm">
                        <h2 className="text-lg font-semibold mb-2">Sender Information</h2>
                        {order.sender_information ? (
                            <>
                                <p><strong>Name:</strong> {order.sender_information.sender_name}</p>
                                <p><strong>Phone:</strong> {order.sender_information.sender_phone}</p>
                                <p><strong>Remarks:</strong> {order.sender_information.remarks}</p>
                            </>
                        ) : (
                            <p>No sender info</p>
                        )}
                    </div>

                    {/* Receiver Info */}
                    <div className="border p-4 rounded-lg shadow-sm">
                        <h2 className="text-lg font-semibold mb-2">Receiver Information</h2>
                        {order.receiver_information ? (
                            <>
                                <p><strong>Name:</strong> {order.receiver_information.receiver_name}</p>
                                <p><strong>Phone:</strong> {order.receiver_information.receiver_phone}</p>
                                <p><strong>Remarks:</strong> {order.receiver_information.remarks}</p>
                            </>
                        ) : (
                            <p>No receiver info</p>
                        )}
                    </div>
                </div>

                {/* Actions */}
                {/*<div className="mt-6">*/}
                {/*    <h2 className="text-xl font-semibold mb-2">Actions</h2>*/}
                {/*    <button className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">*/}
                {/*        Update Status*/}
                {/*    </button>*/}
                {/*</div>*/}
            </div>
        </AppLayout>
    );
}
