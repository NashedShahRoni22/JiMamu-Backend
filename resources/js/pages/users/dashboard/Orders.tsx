import React from "react";
import AppLayout from "@/layouts/app-layout";
import RiderDashboardLayout from "@/layouts/RiderDashboardLayout";
import { Head } from "@inertiajs/react";

type Order = {
    id: number;
    status: string;
    [key: string]: any; // optional: allows extra fields from Laravel
};

interface OrdersProps {
    orders: Order[];
}

export default function Orders({ orders }: OrdersProps) {
    return (
      <AppLayout>
          <RiderDashboardLayout>
              <Head title="Orders" />
              <h2 className="text-2xl font-bold mb-4">Active Orders</h2>
              <p>Test wallet</p>
              {/*{orders.length ? (*/}
              {/*    <ul className="space-y-3">*/}
              {/*        {orders.map((order) => (*/}
              {/*            <li*/}
              {/*                key={order.id}*/}
              {/*                className="bg-white p-4 rounded shadow flex justify-between"*/}
              {/*            >*/}
              {/*                <span>Order #{order.id}</span>*/}
              {/*                <span className="text-sm text-gray-600">{order.status}</span>*/}
              {/*            </li>*/}
              {/*        ))}*/}
              {/*    </ul>*/}
              {/*) : (*/}
              {/*    <p>No active orders.</p>*/}
              {/*)}*/}
          </RiderDashboardLayout>
      </AppLayout>
    );
}
