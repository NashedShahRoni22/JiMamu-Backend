import AppLayout from "@/layouts/app-layout";
import { Head, usePage, Link } from "@inertiajs/react";
import React, { useEffect } from "react";
import toast from "react-hot-toast";

type RiderDocument = {
    id: number;
    user_id: number;
    document_type: string;
    document_number: string;
    document: string[];
    review_status: number;
    remarks?: string | null;
    created_at: string;
    updated_at: string;
};

type Rider = {
    id: number;
    name: string;
    email: string;
    phone_number: string;
    profile_image?: string;
    dob?: string;
    gender?: string;
    status: number;
    created_at: string;
    updated_at: string;
    user_riders: RiderDocument[];
};

interface Props {
    rider: Rider;
    [key: string]: any;
}

const reviewStatusMap: Record<number, string> = {
    1: "Pending",
    2: "Approved",
    3: "Rejected",
};

const statusStyle: Record<number, { badge: string; dot: string }> = {
    1: { badge: "bg-amber-50 text-amber-800 border-amber-300", dot: "bg-amber-500" },
    2: { badge: "bg-green-50 text-green-800 border-green-300", dot: "bg-green-600" },
    3: { badge: "bg-red-50 text-red-800 border-red-300",       dot: "bg-red-600"   },
};

function StatusBadge({ status }: { status: number }) {
    const s = statusStyle[status] ?? statusStyle[1];
    return (
        <span className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border ${s.badge}`}>
            <span className={`w-1.5 h-1.5 rounded-full ${s.dot}`} />
            {reviewStatusMap[status] ?? "Pending"}
        </span>
    );
}

export default function Show() {
    const { rider, toastMessage } = usePage<Props>().props;

    useEffect(() => {
        if (toastMessage) {
            toast.success(toastMessage);
        }
    }, [toastMessage]);

    return (
        <AppLayout>
            <Head title={`Rider Review - ${rider.name}`} />

            <div className="w-full p-4 bg-white shadow rounded">

                {/* Back button */}
                <div className="mb-6">
                    <Link href={route('riders.account.review.request')} className="text-blue-500 hover:underline">
                        &larr; Back to Riders
                    </Link>
                </div>

                {/* Rider Info */}
                <div className="border p-4 rounded-lg shadow-sm mb-6">
                    <h1 className="text-2xl font-bold mb-4">Rider Information</h1>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="space-y-1">
                            <p><strong>Name:</strong> {rider.name}</p>
                            <p><strong>Email:</strong> {rider.email}</p>
                            <p><strong>Phone:</strong> {rider.phone_number}</p>
                            <p><strong>Gender:</strong> {rider.gender || "N/A"}</p>
                            <p><strong>Date of Birth:</strong> {rider.dob || "N/A"}</p>
                        </div>
                        <div className="flex items-center">
                            {rider.profile_image ? (
                                <img
                                    src={rider.profile_image}
                                    alt={rider.name}
                                    className="w-32 h-32 rounded-full object-cover border"
                                />
                            ) : (
                                <p>No profile image</p>
                            )}
                        </div>
                    </div>
                </div>

                {/* Document Info */}
                <div>
                    <h2 className="text-xl font-semibold mb-4">Submitted Documents</h2>
                    {rider.user_riders.length > 0 ? (
                        rider.user_riders.map((doc) => {
                            const docStatus = doc.review_status ?? 1;
                            return (
                                <div key={doc.id} className="border p-4 rounded-lg shadow-sm mb-6">
                                    <div className="space-y-1 mb-3">
                                        <p><strong>Document Type:</strong> {doc.document_type}</p>
                                        <p><strong>Document Number:</strong> {doc.document_number}</p>
                                        <div className="flex items-center gap-2">
                                            <strong className="text-sm">Review Status:</strong>
                                            <StatusBadge status={docStatus} />
                                        </div>
                                        {doc.remarks && (
                                            <p><strong>Remarks:</strong> {doc.remarks}</p>
                                        )}
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                        {doc.document.map((fileUrl, index) => (
                                            <img
                                                key={index}
                                                src={fileUrl}
                                                alt={`${doc.document_type} ${index + 1}`}
                                                className="w-full h-48 object-cover rounded border"
                                            />
                                        ))}
                                    </div>
                                </div>
                            );
                        })
                    ) : (
                        <p>No documents submitted</p>
                    )}
                </div>

                {/* Status + Actions Panel */}
                <div className="mt-6 border rounded-xl overflow-hidden">

                    {/* Status Row */}
                    {/* <div className="px-5 py-4 flex items-center justify-between flex-wrap gap-2 bg-white">
                        <div>
                            <p className="text-xs text-gray-500 mb-1.5">Current account status</p>
                            <StatusBadge status={rider.status ?? 1} />
                        </div>
                        <span className="text-xs text-gray-400">
                            Last updated: {new Date(rider.updated_at).toLocaleString()}
                        </span>
                    </div> */}

                    <hr className="border-t border-gray-100" />

                    {/* Action Buttons */}
                    <div className="px-5 py-4 bg-white">
                        <p className="text-xs text-gray-500 mb-3">Take action on this rider's account</p>
                        <div className="flex gap-2.5 flex-wrap">
                            <Link
                                href={route('riders.rider.account.approve', { user_id: rider.id, status_type: 2 })}
                                className="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700 hover:bg-green-800 text-green-50 text-sm font-medium rounded-lg transition-colors"
                            >
                                <svg className="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none">
                                    <path d="M3 8.5L6.5 12L13 5" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" />
                                </svg>
                                Approve Rider
                            </Link>
                            <Link
                                href={route('riders.rider.account.approve', { user_id: rider.id, status_type: 3 })}
                                className="inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-red-50 text-red-700 border border-red-300 text-sm font-medium rounded-lg transition-colors"
                            >
                                <svg className="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none">
                                    <path d="M4 4L12 12M12 4L4 12" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" />
                                </svg>
                                Reject
                            </Link>
                        </div>
                    </div>
                </div>

            </div>
        </AppLayout>
    );
}