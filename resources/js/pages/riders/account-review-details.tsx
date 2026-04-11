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
    1: { badge: "bg-yellow-100 text-yellow-800", dot: "bg-yellow-500" },
    2: { badge: "bg-green-100 text-green-800",   dot: "bg-green-600" },
    3: { badge: "bg-red-100 text-red-800",        dot: "bg-red-600"   },
};

function StatusBadge({ status }: { status: number }) {
    const s = statusStyle[status] ?? statusStyle[1];
    return (
        <span className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold ${s.badge}`}>
            <span className={`w-1.5 h-1.5 rounded-full ${s.dot}`} />
            {reviewStatusMap[status] ?? "Pending"}
        </span>
    );
}

function Avatar({ name, image }: { name: string; image?: string }) {
    const initials = name?.split(" ").map((n) => n[0]).join("").toUpperCase().slice(0, 2) ?? "?";
    if (image) {
        return (
            <img
                src={image}
                alt={name}
                className="w-20 h-20 rounded-full object-cover border-2 border-white shadow"
            />
        );
    }
    return (
        <div className="w-20 h-20 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-2xl font-bold border-2 border-white shadow">
            {initials}
        </div>
    );
}

function InfoRow({ label, value }: { label: string; value?: string | null }) {
    return (
        <div className="flex flex-col gap-0.5 py-2 border-b border-gray-100 last:border-0">
            <span className="text-xs text-gray-500">{label}</span>
            <span className="text-sm font-medium text-gray-900">{value || "—"}</span>
        </div>
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

            <div className="p-4 md:p-6 max-w-5xl mx-auto space-y-6">

                {/* Header */}
                <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <Link
                            href={route('riders.account.review.request')}
                            className="text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1 mb-1"
                        >
                            &#8592; Back to Riders
                        </Link>
                        <h1 className="text-xl font-bold text-gray-900">Rider Review</h1>
                    </div>
                    <StatusBadge status={rider.status ?? 1} />
                </div>

                {/* Rider Profile Card */}
                <div className="bg-white border border-gray-200 rounded-xl p-5">
                    <h2 className="text-sm font-semibold text-gray-700 mb-4">Rider Information</h2>
                    <div className="flex flex-col md:flex-row gap-5">
                        <div className="flex-shrink-0">
                            <Avatar name={rider.name} image={rider.profile_image} />
                        </div>
                        <div className="flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8">
                            <InfoRow label="Full Name" value={rider.name} />
                            <InfoRow label="Email" value={rider.email} />
                            <InfoRow label="Phone" value={rider.phone_number} />
                            <InfoRow label="Gender" value={rider.gender} />
                            <InfoRow label="Date of Birth" value={rider.dob} />
                            <InfoRow
                                label="Member Since"
                                value={new Date(rider.created_at).toLocaleDateString("en-GB", {
                                    day: "2-digit",
                                    month: "short",
                                    year: "numeric",
                                })}
                            />
                        </div>
                    </div>
                </div>

                {/* Documents */}
                <div>
                    <h2 className="text-sm font-semibold text-gray-700 mb-3">Submitted Documents</h2>

                    {rider.user_riders.length > 0 ? (
                        <div className="space-y-4">
                            {rider.user_riders.map((doc) => {
                                const docStatus = doc.review_status ?? 1;
                                return (
                                    <div
                                        key={doc.id}
                                        className="bg-white border border-gray-200 rounded-xl p-5"
                                    >
                                        {/* Doc Meta */}
                                        <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                                            <div>
                                                <p className="text-sm font-semibold text-gray-900">
                                                    {doc.document_type}
                                                </p>
                                                <p className="text-xs text-gray-500 mt-0.5">
                                                    #{doc.document_number}
                                                </p>
                                            </div>
                                            <StatusBadge status={docStatus} />
                                        </div>

                                        {doc.remarks && (
                                            <div className="bg-yellow-50 border border-yellow-100 rounded-lg px-3 py-2 mb-4">
                                                <p className="text-xs text-yellow-800">
                                                    <span className="font-semibold">Remarks: </span>
                                                    {doc.remarks}
                                                </p>
                                            </div>
                                        )}

                                        {/* Document Images */}
                                        {doc.document.length > 0 && (
                                            <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                {doc.document.map((fileUrl, index) => (
                                                    <a
                                                        key={index}
                                                        href={fileUrl}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="block group"
                                                    >
                                                        <img
                                                            src={fileUrl}
                                                            alt={`${doc.document_type} ${index + 1}`}
                                                            className="w-full h-48 object-cover rounded-lg border border-gray-200 group-hover:opacity-90 transition"
                                                        />
                                                    </a>
                                                ))}
                                            </div>
                                        )}

                                        <p className="text-xs text-gray-400 mt-3">
                                            Submitted: {new Date(doc.created_at).toLocaleDateString("en-GB", {
                                                day: "2-digit",
                                                month: "short",
                                                year: "numeric",
                                            })}
                                        </p>
                                    </div>
                                );
                            })}
                        </div>
                    ) : (
                        <div className="bg-white border border-gray-200 rounded-xl p-8 text-center">
                            <p className="text-sm text-gray-400">No documents submitted yet</p>
                        </div>
                    )}
                </div>

                {/* Action Panel */}
                <div className="bg-white border border-gray-200 rounded-xl p-5">
                    <p className="text-xs text-gray-500 mb-3">Take action on this rider's account</p>
                    <div className="flex gap-3 flex-wrap">
                        <Link
                            href={route('riders.rider.account.approve', { user_id: rider.id, status_type: 2 })}
                            className="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors"
                        >
                            <svg className="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none">
                                <path d="M3 8.5L6.5 12L13 5" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" />
                            </svg>
                            Approve Rider
                        </Link>
                        <Link
                            href={route('riders.rider.account.approve', { user_id: rider.id, status_type: 3 })}
                            className="inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-red-50 text-red-600 border border-red-200 text-sm font-medium rounded-lg transition-colors"
                        >
                            <svg className="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none">
                                <path d="M4 4L12 12M12 4L4 12" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" />
                            </svg>
                            Reject Rider
                        </Link>
                    </div>
                </div>

            </div>
        </AppLayout>
    );
}