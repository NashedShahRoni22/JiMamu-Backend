import React, { useEffect, useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

type PlatformCharge = {
  id: number;
  base_fare: string;
  platform_charge: string;
  type: number;
  created_at: string;
  updated_at: string;
};

type Props = {
  platformCharges: PlatformCharge[];
};

type SharedProps = {
  flash?: {
    success?: string;
  };
};

const TYPE_LABELS: Record<number, string> = {
  1: 'National',
  2: 'International',
};

export default function PlatformChargeIndex({ platformCharges }: Props) {
  const { props } = usePage<SharedProps>();
  const successMessage = props.flash?.success ?? null;

  const [showNotification, setShowNotification] = useState(!!successMessage);

  useEffect(() => {
    if (successMessage) {
      setShowNotification(true);
      const timer = setTimeout(() => setShowNotification(false), 4000);
      return () => clearTimeout(timer);
    }
  }, [successMessage]);

  return (
    <AppLayout>
      <Head title="Platform Charges" />

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
          <h1 className="text-2xl font-semibold">Platform Charges</h1>
          <Link
            href={route('platform-charge.create')}
            className="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black/80"
          >
            + Add Platform Charge
          </Link>
        </div>

        {/* Table */}
        {platformCharges.length === 0 ? (
          <div className="rounded-md border border-gray-200 bg-white p-10 text-center text-sm text-gray-500">
            No platform charges found.
          </div>
        ) : (
          <div className="overflow-hidden rounded-md border border-gray-200 bg-white">
            <table className="min-w-full divide-y divide-gray-200 text-sm">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-4 py-3 text-left font-medium text-gray-600">#</th>
                  <th className="px-4 py-3 text-left font-medium text-gray-600">Base Fare</th>
                  <th className="px-4 py-3 text-left font-medium text-gray-600">Platform Charge</th>
                  <th className="px-4 py-3 text-left font-medium text-gray-600">Type</th>
                  <th className="px-4 py-3 text-left font-medium text-gray-600">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {platformCharges.map((item, index) => (
                  <tr key={item.id} className="hover:bg-gray-50">
                    <td className="px-4 py-3 text-gray-500">{index + 1}</td>
                    <td className="px-4 py-3 text-gray-900">{Number(item.base_fare).toFixed(2)}</td>
                    <td className="px-4 py-3 text-gray-900">{Number(item.platform_charge).toFixed(2)}</td>
                    <td className="px-4 py-3">
                      <span
                        className={`inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium ${
                          item.type === 1
                            ? 'bg-blue-100 text-blue-700'
                            : 'bg-purple-100 text-purple-700'
                        }`}
                      >
                        {TYPE_LABELS[item.type] ?? 'Unknown'}
                      </span>
                    </td>
                    <td className="px-4 py-3">
                      <Link
                        href={route('platform-charge.edit', item.id)}
                        className="px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                      >
                        Edit
                      </Link>
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