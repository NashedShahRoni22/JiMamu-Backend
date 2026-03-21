import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
];

type Stats = {
    total_orders: number;
    national_orders: number;
    international_orders: number;
    pending_orders: number;
    completed_orders: number;
    active_riders: number;
    total_customers: number;
    total_revenue: number;
};

type Order = {
    id: number;
    order_unique_id: string;
    customer: string;
    rider: string;
    package: string;
    type: 'National' | 'International';
    status: string;
    payment_status: string;
    weight: string;
    amount: number;
    created_at: string;
};

type Props = {
    stats?: Stats;
    recentOrders?: Order[];
};

const defaultStats: Stats = {
    total_orders: 0,
    national_orders: 0,
    international_orders: 0,
    pending_orders: 0,
    completed_orders: 0,
    active_riders: 0,
    total_customers: 0,
    total_revenue: 0,
};

const STATUS_CONFIG: Record<string, { label: string; classes: string }> = {
    pending:   { label: 'Pending',   classes: 'bg-amber-100 text-amber-700' },
    confirmed: { label: 'Confirmed', classes: 'bg-blue-100 text-blue-700' },
    picked:    { label: 'Picked',    classes: 'bg-indigo-100 text-indigo-700' },
    shipping:  { label: 'Shipping',  classes: 'bg-purple-100 text-purple-700' },
    delivered: { label: 'Delivered', classes: 'bg-green-100 text-green-700' },
    cancelled: { label: 'Cancelled', classes: 'bg-red-100 text-red-700' },
};

// ── Icon components ────────────────────────────────────────────────────────────
function IconBox({ className = '' }: { className?: string }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round">
            <path d="M21 8l-9-5-9 5v8l9 5 9-5V8z" /><path d="M3 8l9 5 9-5" /><line x1="12" y1="13" x2="12" y2="21" />
        </svg>
    );
}
function IconTruck({ className = '' }: { className?: string }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round">
            <rect x="1" y="3" width="15" height="13" /><polygon points="16 8 20 8 23 11 23 16 16 16 16 8" /><circle cx="5.5" cy="18.5" r="2.5" /><circle cx="18.5" cy="18.5" r="2.5" />
        </svg>
    );
}
function IconGlobe({ className = '' }: { className?: string }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round">
            <circle cx="12" cy="12" r="10" /><line x1="2" y1="12" x2="22" y2="12" /><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
        </svg>
    );
}
function IconUsers({ className = '' }: { className?: string }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
    );
}
function IconRider({ className = '' }: { className?: string }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round">
            <circle cx="12" cy="5" r="2" /><path d="M12 7v6l3 3" /><path d="M5 14a7 7 0 0 1 14 0" /><circle cx="5" cy="17" r="2" /><circle cx="19" cy="17" r="2" /><path d="M7 17h10" />
        </svg>
    );
}
function IconWallet({ className = '' }: { className?: string }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round">
            <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4" /><path d="M4 6v12a2 2 0 0 0 2 2h14v-4" /><path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z" />
        </svg>
    );
}
function IconClock({ className = '' }: { className?: string }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round">
            <circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" />
        </svg>
    );
}
function IconCheck({ className = '' }: { className?: string }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round">
            <polyline points="20 6 9 17 4 12" />
        </svg>
    );
}

// ── Stat Card ─────────────────────────────────────────────────────────────────
function StatCard({
    label,
    value,
    icon,
    accent,
    sub,
}: {
    label: string;
    value: string | number;
    icon: React.ReactNode;
    accent: string;
    sub?: string;
}) {
    return (
        <div className="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
            <div className={`absolute -right-4 -top-4 h-20 w-20 rounded-full opacity-10 ${accent}`} />
            <div className="flex items-start justify-between">
                <div>
                    <p className="text-xs font-medium uppercase tracking-widest text-gray-400">{label}</p>
                    <p className="mt-1 text-3xl font-bold text-gray-900">{value}</p>
                    {sub && <p className="mt-1 text-xs text-gray-400">{sub}</p>}
                </div>
                <div className={`flex h-11 w-11 shrink-0 items-center justify-center rounded-lg ${accent} bg-opacity-10`}>
                    {icon}
                </div>
            </div>
        </div>
    );
}

// ── Order row ─────────────────────────────────────────────────────────────────
function OrderRow({ order, index }: { order: Order; index: number }) {
    const cfg = STATUS_CONFIG[order.status] ?? { label: order.status, classes: 'bg-gray-100 text-gray-600' };
    const isPaid = order.payment_status === 'paid';
    return (
        <tr className="border-b border-gray-50 transition-colors hover:bg-gray-50/60" style={{ animationDelay: `${index * 60}ms` }}>
            <td className="px-4 py-3">
                <p className="font-mono text-xs text-gray-400">#{order.id}</p>
                <p className="mt-0.5 font-mono text-xs font-medium text-gray-600">{order.order_unique_id}</p>
            </td>
            <td className="px-4 py-3">
                <p className="text-sm font-medium text-gray-800">{order.customer}</p>
                <p className="mt-0.5 text-xs text-gray-400">{order.package}</p>
            </td>
            <td className="px-4 py-3 text-xs text-gray-500">{order.rider}</td>
            <td className="px-4 py-3 text-sm">
                <span className={`inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ${order.type === 'National' ? 'bg-blue-50 text-blue-600' : 'bg-violet-50 text-violet-600'}`}>
                    {order.type === 'International' ? <IconGlobe className="h-3 w-3" /> : <IconBox className="h-3 w-3" />}
                    {order.type}
                </span>
            </td>
            <td className="px-4 py-3">
                <span className={`inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium ${cfg.classes}`}>{cfg.label}</span>
            </td>
            <td className="px-4 py-3">
                <span className={`inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium ${isPaid ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-600'}`}>
                    {isPaid ? 'Paid' : 'Unpaid'}
                </span>
            </td>
            <td className="px-4 py-3 text-xs text-gray-500">{order.weight}</td>
            <td className="px-4 py-3 text-sm font-semibold text-gray-800">
                {order.amount > 0 ? `৳${Number(order.amount).toFixed(2)}` : '—'}
            </td>
            <td className="px-4 py-3 text-xs text-gray-400">{order.created_at}</td>
        </tr>
    );
}

// ── Main ──────────────────────────────────────────────────────────────────────
export default function Dashboard({ stats = defaultStats, recentOrders = [] }: Props) {
    const completionRate = stats.total_orders > 0
        ? Math.round((stats.completed_orders / stats.total_orders) * 100)
        : 0;

    const nationalPct = stats.total_orders > 0
        ? Math.round((stats.national_orders / stats.total_orders) * 100)
        : 0;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />

            <div className="flex flex-col gap-6 p-5">

                {/* ── Header ── */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Dashboard</h1>
                        <p className="mt-0.5 text-sm text-gray-400">Parcel delivery operations overview</p>
                    </div>
                    <div className="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-500 shadow-sm">
                        <IconClock className="h-4 w-4 text-gray-400" />
                        <span>Live</span>
                        <span className="relative flex h-2 w-2">
                            <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75" />
                            <span className="relative inline-flex h-2 w-2 rounded-full bg-green-500" />
                        </span>
                    </div>
                </div>

                {/* ── Stat Grid ── */}
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <StatCard
                        label="Total Orders"
                        value={stats.total_orders}
                        icon={<IconBox className="h-5 w-5 text-gray-700" />}
                        accent="bg-gray-900"
                        sub="All time"
                    />
                    <StatCard
                        label="Pending Orders"
                        value={stats.pending_orders}
                        icon={<IconClock className="h-5 w-5 text-amber-600" />}
                        accent="bg-amber-500"
                        sub="Awaiting payment / pickup"
                    />
                    <StatCard
                        label="Completed Orders"
                        value={stats.completed_orders}
                        icon={<IconCheck className="h-5 w-5 text-green-600" />}
                        accent="bg-green-500"
                        sub={`${completionRate}% completion rate`}
                    />
                    <StatCard
                        label="Total Revenue"
                        value={`৳${Number(stats.total_revenue).toLocaleString()}`}
                        icon={<IconWallet className="h-5 w-5 text-blue-600" />}
                        accent="bg-blue-500"
                        sub="From completed orders"
                    />
                </div>

                {/* ── Second Row ── */}
                <div className="grid gap-4 sm:grid-cols-3">
                    <StatCard
                        label="National Orders"
                        value={stats.national_orders}
                        icon={<IconTruck className="h-5 w-5 text-indigo-600" />}
                        accent="bg-indigo-500"
                        sub={`${nationalPct}% of all orders`}
                    />
                    <StatCard
                        label="International Orders"
                        value={stats.international_orders}
                        icon={<IconGlobe className="h-5 w-5 text-violet-600" />}
                        accent="bg-violet-500"
                        sub={`${100 - nationalPct}% of all orders`}
                    />
                    <StatCard
                        label="Active Riders"
                        value={stats.active_riders}
                        icon={<IconRider className="h-5 w-5 text-rose-600" />}
                        accent="bg-rose-500"
                        sub={`${stats.total_customers} total customers`}
                    />
                </div>

                {/* ── Order Flow Visual ── */}
                <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p className="mb-4 text-xs font-semibold uppercase tracking-widest text-gray-400">Order Journey</p>
                    <div className="flex items-center gap-0 overflow-x-auto pb-1">
                        {[
                            { label: 'Order Placed',    icon: <IconBox className="h-5 w-5" />,    color: 'text-gray-600 bg-gray-100',    dot: 'bg-gray-400' },
                            { label: 'Stripe Payment',  icon: <IconWallet className="h-5 w-5" />, color: 'text-blue-600 bg-blue-50',     dot: 'bg-blue-400' },
                            { label: 'Confirmed',       icon: <IconCheck className="h-5 w-5" />,  color: 'text-indigo-600 bg-indigo-50', dot: 'bg-indigo-400' },
                            { label: 'Picked Up',       icon: <IconRider className="h-5 w-5" />,  color: 'text-amber-600 bg-amber-50',   dot: 'bg-amber-400' },
                            { label: 'Shipping',        icon: <IconTruck className="h-5 w-5" />,  color: 'text-purple-600 bg-purple-50', dot: 'bg-purple-400' },
                            { label: 'Delivered',       icon: <IconCheck className="h-5 w-5" />,  color: 'text-green-600 bg-green-50',   dot: 'bg-green-400' },
                        ].map((step, i, arr) => (
                            <div key={i} className="flex shrink-0 items-center">
                                <div className="flex flex-col items-center gap-2">
                                    <div className={`flex h-10 w-10 items-center justify-center rounded-full ${step.color}`}>
                                        {step.icon}
                                    </div>
                                    <span className="text-center text-xs font-medium text-gray-500 max-w-[72px] leading-tight">{step.label}</span>
                                </div>
                                {i < arr.length - 1 && (
                                    <div className="mx-2 mb-5 h-px w-10 bg-gray-200 sm:w-16" />
                                )}
                            </div>
                        ))}
                    </div>
                </div>

                {/* ── Recent Orders Table ── */}
                <div className="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div className="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                        <p className="text-sm font-semibold text-gray-800">Recent Orders</p>
                        <span className="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500">
                            {recentOrders.length} latest
                        </span>
                    </div>

                    {recentOrders.length === 0 ? (
                        <div className="flex flex-col items-center gap-3 py-16 text-gray-400">
                            <IconBox className="h-10 w-10 opacity-30" />
                            <p className="text-sm">No orders yet</p>
                        </div>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="min-w-full text-sm">
                                <thead>
                                    <tr className="border-b border-gray-100 bg-gray-50/60">
                                        <th className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Order</th>
                                        <th className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Customer / Package</th>
                                        <th className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Rider</th>
                                        <th className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Type</th>
                                        <th className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Status</th>
                                        <th className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Payment</th>
                                        <th className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Weight</th>
                                        <th className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Bid</th>
                                        <th className="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {recentOrders.map((order, i) => (
                                        <OrderRow key={order.id} order={order} index={i} />
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {/* ── Quick Info Cards ── */}
                <div className="grid gap-4 sm:grid-cols-2">
                    {/* Rider Wallet Info */}
                    <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div className="mb-3 flex items-center gap-2">
                            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50">
                                <IconWallet className="h-4 w-4 text-rose-500" />
                            </div>
                            <p className="text-sm font-semibold text-gray-800">Rider Wallet System</p>
                        </div>
                        <p className="text-xs leading-relaxed text-gray-500">
                            Riders earn automatically upon order completion. Wallet balance is updated instantly once the delivery is confirmed by the customer.
                        </p>
                        <div className="mt-4 flex items-center gap-3">
                            <div className="flex-1 rounded-lg bg-gray-50 px-3 py-2 text-center">
                                <p className="text-lg font-bold text-gray-900">{stats.active_riders}</p>
                                <p className="text-xs text-gray-400">Active Riders</p>
                            </div>
                            <div className="flex-1 rounded-lg bg-green-50 px-3 py-2 text-center">
                                <p className="text-lg font-bold text-green-700">{stats.completed_orders}</p>
                                <p className="text-xs text-gray-400">Paid Out</p>
                            </div>
                        </div>
                    </div>

                    {/* Customer & User Info */}
                    <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div className="mb-3 flex items-center gap-2">
                            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50">
                                <IconUsers className="h-4 w-4 text-blue-500" />
                            </div>
                            <p className="text-sm font-semibold text-gray-800">User Roles</p>
                        </div>
                        <p className="text-xs leading-relaxed text-gray-500">
                            Every user starts as a customer. Once they complete their profile, they can apply to become a rider and accept delivery jobs.
                        </p>
                        <div className="mt-4 space-y-2">
                            <div className="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                                <div className="flex items-center gap-2">
                                    <span className="h-2 w-2 rounded-full bg-blue-400" />
                                    <span className="text-xs text-gray-600">Customers</span>
                                </div>
                                <span className="text-sm font-semibold text-gray-800">{stats.total_customers}</span>
                            </div>
                            <div className="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                                <div className="flex items-center gap-2">
                                    <span className="h-2 w-2 rounded-full bg-rose-400" />
                                    <span className="text-xs text-gray-600">Riders</span>
                                </div>
                                <span className="text-sm font-semibold text-gray-800">{stats.active_riders}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </AppLayout>
    );
}