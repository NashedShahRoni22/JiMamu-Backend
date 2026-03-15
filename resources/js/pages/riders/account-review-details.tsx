import AppLayout from "@/layouts/app-layout";
import { Head, usePage, Link } from "@inertiajs/react";
import React, {useEffect} from "react";
import toast from "react-hot-toast";

type RiderDocument = {
    id: number;
    user_id: number;
    document_type: string;
    document_number: string;
    document: string[]; // Multiple images
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

export default function Show() {
    const { rider, toastMessage } = usePage<Props>().props;


    const reviewStatusMap: Record<number, string> = {
        1: "Pending",
        2: "Approved",
        3: "Rejected"
    };


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
                        <div>
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
                        rider.user_riders.map((doc) => (
                            <div key={doc.id} className="border p-4 rounded-lg shadow-sm mb-6">
                                <p><strong>Document Type:</strong> {doc.document_type}</p>
                                <p><strong>Document Number:</strong> {doc.document_number}</p>
                                <p><strong>Review Status:</strong> {reviewStatusMap[doc.review_status]}</p>
                                {doc.remarks && <p><strong>Remarks:</strong> {doc.remarks}</p>}

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
                        ))
                    ) : (
                        <p>No documents submitted</p>
                    )}
                </div>

                {/* Actions */}
                <div className="mt-6 flex gap-4">
                    <Link href={route('riders.rider.account.approve', {user_id: rider.id, status_type: 2})}  className="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Approve
                    </Link>
                    <Link href={route('riders.rider.account.approve', {user_id: rider.id, status_type: 3})} className="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Reject
                    </Link>
                </div>
            </div>
        </AppLayout>
    );
}
