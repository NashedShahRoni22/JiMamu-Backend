import React, { useEffect, useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

type Package = {
    id: number;
    name: string;
    sort_description: string;
    status: number;
    created_at: string;
    updated_at: string;
};

type Props = {
    packages: Package[];
};

type SharedProps = {
    flash?: {
        success?: string;
    };
};

export default function PackageIndex({ packages }: Props) {
    const { props } = usePage<SharedProps>();
    const successMessage = props.flash?.success ?? null;

    const [showNotification, setShowNotification] = useState(!!successMessage);
    const [deletingId, setDeletingId] = useState<number | null>(null);
    const [confirmId, setConfirmId] = useState<number | null>(null);

    useEffect(() => {
        if (successMessage) {
            setShowNotification(true);
            const timer = setTimeout(() => setShowNotification(false), 4000);
            return () => clearTimeout(timer);
        }
    }, [successMessage]);

    const handleDelete = (id: number) => {
        setDeletingId(id);
        router.delete(route('package.destroy', id), {
            preserveScroll: true,
            onFinish: () => {
                setDeletingId(null);
                setConfirmId(null);
            },
        });
    };

    return (
        <AppLayout>
            <Head title="Packages" />

            <div className="p-6">
                {/* Success Notification */}
                {showNotification && successMessage && (
                    <div className="mb-6 flex items-center justify-between rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        <div className="flex items-center gap-2">
                            <svg
                                className="h-4 w-4 shrink-0 text-green-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                strokeWidth={2}
                            >
                                <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{successMessage}</span>
                        </div>
                        <button
                            onClick={() => setShowNotification(false)}
                            className="ml-4 text-green-600 hover:text-green-800"
                            aria-label="Dismiss"
                        >
                            <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                )}

                {/* Header */}
                <div className="mb-6 flex items-center justify-between">
                    <h1 className="text-2xl font-semibold">Packages</h1>
                    <Link
                        href={route('package.create')}
                        className="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black/80"
                    >
                        + Add Package
                    </Link>
                </div>

                {/* Table */}
                {packages.length === 0 ? (
                    <div className="rounded-md border border-gray-200 bg-white p-10 text-center text-sm text-gray-500">
                        No packages found.
                    </div>
                ) : (
                    <div className="overflow-hidden rounded-md border border-gray-200 bg-white">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-4 py-3 text-left font-medium text-gray-600">#</th>
                                    <th className="px-4 py-3 text-left font-medium text-gray-600">Name</th>
                                    <th className="px-4 py-3 text-left font-medium text-gray-600">Sort Desc</th>
                                    <th className="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                                    <th className="px-4 py-3 text-left font-medium text-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {packages.map((item, index) => (
                                    <tr key={item.id} className="hover:bg-gray-50">
                                        <td className="px-4 py-3 text-gray-500">{index + 1}</td>
                                        <td className="px-4 py-3 text-gray-900">{item.name}</td>
                                        <td className="px-4 py-3 text-gray-900">{item.sort_description}</td>
                                        <td className="px-4 py-3">
                                            <span
                                                className={`inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium ${
                                                    item.status === 1
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-gray-100 text-gray-600'
                                                }`}
                                            >
                                                {item.status === 1 ? 'Active' : 'Inactive'}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="flex items-center gap-3">
                                                <Link
                                                    href={route('package.edit', item.id)}
                                                    className="text-gray-600 underline-offset-2 hover:text-gray-900 hover:underline"
                                                >
                                                    Edit
                                                </Link>

                                                {confirmId === item.id ? (
                                                    <span className="flex items-center gap-2">
                                                        <span className="text-gray-500">Sure?</span>
                                                        <button
                                                            onClick={() => handleDelete(item.id)}
                                                            disabled={deletingId === item.id}
                                                            className="text-red-600 underline-offset-2 hover:underline disabled:opacity-50"
                                                        >
                                                            {deletingId === item.id ? 'Deleting...' : 'Yes'}
                                                        </button>
                                                        <button
                                                            onClick={() => setConfirmId(null)}
                                                            className="text-gray-500 underline-offset-2 hover:underline"
                                                        >
                                                            No
                                                        </button>
                                                    </span>
                                                ) : (
                                                    <button
                                                        onClick={() => setConfirmId(item.id)}
                                                        className="text-red-500 underline-offset-2 hover:text-red-700 hover:underline"
                                                    >
                                                        Delete
                                                    </button>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}