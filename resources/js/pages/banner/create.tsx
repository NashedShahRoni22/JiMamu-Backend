import React, { useEffect, useMemo, useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

type Props = {
  // Submit URL can be provided from the server-side Inertia response.
  // Fallback to a sensible default path if not provided.
  submitUrl?: string;
  // Optional page title
  title?: string;
};

type FormDataShape = {
  image: File | null;
};

export default function CreateBanner({ submitUrl = route('banner.store'), title = 'Create Banner' }: Props) {
  const { data, setData, post, processing, errors, progress, reset, clearErrors } = useForm<FormDataShape>({
    image: null,
  });

  const [previewUrl, setPreviewUrl] = useState<string | null>(null);

  // Clean up object URL when component unmounts or when a new file is selected
  useEffect(() => {
    return () => {
      if (previewUrl) URL.revokeObjectURL(previewUrl);
    };
  }, [previewUrl]);

  const onFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0] ?? null;
    setData('image', file);
    clearErrors('image');

    if (previewUrl) URL.revokeObjectURL(previewUrl);
    setPreviewUrl(file ? URL.createObjectURL(file) : null);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(submitUrl, {
      preserveScroll: true,
      forceFormData: true, // ensure multipart/form-data
      onSuccess: () => {
        // Optionally clear the form on success
        reset('image');
        if (previewUrl) {
          URL.revokeObjectURL(previewUrl);
          setPreviewUrl(null);
        }
      },
    });
  };

  const canSubmit = useMemo(() => !!data.image && !processing, [data.image, processing]);

  return (
    <AppLayout>
      <div className="p-6">
        <Head title={title} />
        <h1 className="mb-6 text-2xl font-semibold">{title}</h1>

        <form onSubmit={handleSubmit} encType="multipart/form-data" className="space-y-6 max-w-2xl">
          <div>
            <label htmlFor="image" className="mb-2 block text-sm font-medium text-gray-700">
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
            {errors.image && <p className="mt-2 text-sm text-red-600">{errors.image}</p>}
          </div>

          {previewUrl && (
            <div className="rounded-md border border-gray-200 p-3 max-w-2xl">
              <p className="mb-2 text-sm text-gray-600">Preview</p>
              <img
                src={previewUrl}
                alt="Selected banner preview"
                className="max-h-64 w-full rounded-md object-contain"
              />
            </div>
          )}

          {progress && (
            <div className="w-full max-w-2xl">
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

          <div className="flex items-center gap-3">
            <button
              type="submit"
              disabled={!canSubmit}
              className={`inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-white ${
                canSubmit ? 'bg-gray-900 hover:bg-black/80' : 'bg-gray-400 cursor-not-allowed'
              }`}
            >
              {processing ? 'Submitting...' : 'Submit'}
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
