import React, { useMemo } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

type PlatformCharge = {
  id: number;
  base_fare: string;
  platform_charge: string;
  type: number;
};

type Props = {
  platformCharge: PlatformCharge;
  title?: string;
};

type FormDataShape = {
  base_fare: string;
  platform_charge: string;
  type: string;
};

export default function EditPlatformCharge({ platformCharge, title = 'Edit Platform Charge' }: Props) {
  const { data, setData, put, processing, errors, clearErrors } = useForm<FormDataShape>({
    base_fare: platformCharge.base_fare,
    platform_charge: platformCharge.platform_charge,
    type: String(platformCharge.type),
  });

  const onInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setData(name as keyof FormDataShape, value);
    clearErrors(name as keyof FormDataShape);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    put(route('platform-charge.update', platformCharge.id), {
      preserveScroll: true,
    });
  };

  const canSubmit = useMemo(
    () => !!data.base_fare && !!data.platform_charge && !!data.type && !processing,
    [data.base_fare, data.platform_charge, data.type, processing],
  );

  return (
    <AppLayout>
      <div className="p-6">
        <Head title={title} />
        <h1 className="mb-6 text-2xl font-semibold">{title}</h1>

        <form onSubmit={handleSubmit} className="space-y-6 max-w-2xl">
          {/* Base Fare */}
          <div>
            <label htmlFor="base_fare" className="mb-2 block text-sm font-medium text-gray-700">
              Base Fare
            </label>
            <input
              id="base_fare"
              name="base_fare"
              type="number"
              step="0.01"
              min="0"
              value={data.base_fare}
              onChange={onInputChange}
              placeholder="0.00"
              className="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
            />
            {errors.base_fare && <p className="mt-2 text-sm text-red-600">{errors.base_fare}</p>}
          </div>

          {/* Platform Charge */}
          <div>
            <label htmlFor="platform_charge" className="mb-2 block text-sm font-medium text-gray-700">
              Platform Charge
            </label>
            <input
              id="platform_charge"
              name="platform_charge"
              type="number"
              step="0.01"
              min="0"
              value={data.platform_charge}
              onChange={onInputChange}
              placeholder="0.00"
              className="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
            />
            {errors.platform_charge && (
              <p className="mt-2 text-sm text-red-600">{errors.platform_charge}</p>
            )}
          </div>

          {/* Type */}
          <div>
            <label htmlFor="type" className="mb-2 block text-sm font-medium text-gray-700">
              Type
            </label>
            <select
              id="type"
              name="type"
              value={data.type}
              onChange={onInputChange}
              className="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900"
            >
              <option value="" disabled>
                Select type
              </option>
              <option value="1">National</option>
              <option value="2">International</option>
            </select>
            {errors.type && <p className="mt-2 text-sm text-red-600">{errors.type}</p>}
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