import React, { useEffect, useState } from "react";
import { Head, useForm } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";

type Props = {
    banner: {
        id: number;
        image_name: string;
        status: "active" | "inactive";
    };
};

type FormDataShape = {
    image: File | null;
    status: "active" | "inactive";
};

export default function Edit({ banner }: Props) {
    const { data, setData, post, processing, errors, progress } = useForm<FormDataShape>({
        image: null,
        status: banner.status,
    });

    const [previewUrl, setPreviewUrl] = useState<string | null>(
        banner.image_name ? `/storage/${banner.image_name}` : null
    );

    // Cleanup object URL on unmount
    useEffect(() => {
        return () => {
            if (previewUrl?.startsWith("blob:")) URL.revokeObjectURL(previewUrl);
        };
    }, [previewUrl]);

    const onFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData("image", file);

        if (previewUrl?.startsWith("blob:")) URL.revokeObjectURL(previewUrl);
        setPreviewUrl(file ? URL.createObjectURL(file) : previewUrl);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        post(route("banner.update", banner.id), {
            preserveScroll: true,
            forceFormData: true, // important for file upload
        });
    };

    return (
        <AppLayout>
            <div className="p-6">
                <Head title="Edit Banner" />
                <h1 className="mb-6 text-2xl font-semibold">Edit Banner</h1>

                <form
                    onSubmit={handleSubmit}
                    encType="multipart/form-data"
                    className="space-y-6 max-w-xl"
                >
                    {/* Image Upload */}
                    <div>
                        <label
                            htmlFor="image"
                            className="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Banner Image
                        </label>
                        <input
                            id="image"
                            name="image"
                            type="file"
                            accept="image/*"
                            onChange={onFileChange}
                            className="block w-full cursor-pointer rounded-md border border-gray-300 bg-white p-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-black/80"
                        />
                        {errors.image && (
                            <p className="mt-2 text-sm text-red-600">
                                {errors.image}
                            </p>
                        )}
                    </div>

                    {/* Image Preview */}
                    {previewUrl && (
                        <div className="rounded-md border border-gray-200 p-3 max-w-xl">
                            <p className="mb-2 text-sm text-gray-600">Preview</p>
                            <img
                                src={previewUrl}
                                alt="Banner preview"
                                className="max-h-64 w-full rounded-md object-contain"
                            />
                        </div>
                    )}

                    {/* Status Dropdown */}
                    <div>
                        <label
                            htmlFor="status"
                            className="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Status
                        </label>
                        <select
                            id="status"
                            name="status"
                            value={data.status}
                            onChange={(e) =>
                                setData("status", e.target.value as "active" | "inactive")
                            }
                            className="border p-2 rounded w-full"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        {errors.status && (
                            <p className="mt-2 text-sm text-red-600">
                                {errors.status}
                            </p>
                        )}
                    </div>

                    {/* Upload Progress */}
                    {progress && (
                        <div className="w-full max-w-xl">
                            <div className="mb-2 flex items-center justify-between text-sm text-gray-600">
                                <span>Uploading...</span>
                                <span>{progress.percentage}%</span>
                            </div>
                            <div className="h-2 w-full overflow-hidden rounded bg-gray-200">
                                <div
                                    className="h-2 bg-gray-900 transition-all"
                                    style={{ width: `${progress.percentage}%` }}
                                />
                            </div>
                        </div>
                    )}

                    {/* Buttons */}
                    <div className="flex items-center gap-3">
                        <button
                            type="submit"
                            disabled={processing}
                            className={`inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-white ${
                                !processing
                                    ? "bg-gray-900 hover:bg-black/80"
                                    : "bg-gray-400 cursor-not-allowed"
                            }`}
                        >
                            {processing ? "Updating..." : "Update"}
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
