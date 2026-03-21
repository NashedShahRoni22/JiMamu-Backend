import React, { useMemo } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

type Package = {
    id: number;
    name: string;
    sort_description: string;
    status: number;
};

type Props = {
    package: Package;
    title?: string;
};

type FormDataShape = {
    name: string;
    sort_description: string;
    status: string;
};

export default function EditPackage({ package: pkg, title = 'Edit Package' }: Props) {
    const { data, setData, put, processing, errors, clearErrors } = useForm<FormDataShape>({
        name: pkg.name,
        sort_description: pkg.sort_description,
        status: String(pkg.status),
    });

    const onInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
        const { name, value } = e.target;
        setData(name as keyof FormDataShape, value);
        clearErrors(name as keyof FormDataShape);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('package.update', pkg.id), {
            preserveScroll: true,
        });
    };

    const canSubmit = useMemo(
        () => !!data.name && !!data.sort_description && !!data.status && !processing,
        [data.name, data.sort_description, data.status, processing],
    );

    return (
        <AppLayout>
            <div className="p-6">
                <Head title={title} />
                <h1 className="mb-6 text-2xl font-semibold">{title}</h1>

                <form onSubmit={handleSubmit} className="space-y-6 max-w-2xl">
                    {/* Name */}
                    <div>
                        <label htmlFor="name" className="mb-2 block text-sm font-medium text-gray-700">
                            Name
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value={data.name}
                            onChange={onInputChange}
                            placeholder="Enter package name"
                            className="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
                        />
                        {errors.name && <p className="mt-2 text-sm text-red-600">{errors.name}</p>}
                    </div>

                    {/* Sort Description */}
                    <div>
                        <label htmlFor="sort_description" className="mb-2 block text-sm font-medium text-gray-700">
                            Sort Description
                        </label>
                        <textarea
                            id="sort_description"
                            name="sort_description"
                            rows={4}
                            value={data.sort_description}
                            onChange={onInputChange}
                            placeholder="Enter a short description"
                            className="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900 resize-none"
                        />
                        {errors.sort_description && (
                            <p className="mt-2 text-sm text-red-600">{errors.sort_description}</p>
                        )}
                    </div>

                    {/* Status */}
                    <div>
                        <label htmlFor="status" className="mb-2 block text-sm font-medium text-gray-700">
                            Status
                        </label>
                        <select
                            id="status"
                            name="status"
                            value={data.status}
                            onChange={onInputChange}
                            className="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
                        >
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        {errors.status && <p className="mt-2 text-sm text-red-600">{errors.status}</p>}
                    </div>

                    {/* Actions */}
                    <div className="flex items-center gap-3">
                        <button
                            type="submit"
                            disabled={!canSubmit}
                            className={`inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-white ${
                                canSubmit ? 'bg-gray-900 hover:bg-black/80' : 'bg-gray-400 cursor-not-allowed'
                            }`}
                        >
                            {processing ? 'Updating...' : 'Update'}
                        </button>

                        <button
                            type="button"
                            onClick={() => window.history.back()}
                            className="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}