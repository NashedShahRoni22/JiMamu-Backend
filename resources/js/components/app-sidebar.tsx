import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },

    {
        title: 'Orders',
        href: '/orders',
        icon: LayoutGrid,
    },
    // {
    //     title: 'Cancel Orders',
    //     href: '/cancel/orders',
    //     icon: LayoutGrid,
    // },
    {
        title: 'Banners',
        href: '/banner',
        icon: LayoutGrid,
    },
    {
        title: 'Customer',
        href: '/rider/pending',
        icon: LayoutGrid,
        // children: [
        //     { title: 'Pending Orders', href: '/rider/pending' },
        //     { title: 'Completed Orders', href: '/rider/completed' },
        //     { title: 'Cancelled Orders', href: '/rider/cancelled' },
        // ],
    },
    {
        title: 'Rider',
        href: '/riders/rider/account/review',
        icon: LayoutGrid,

        // children: [
        //
        //     { title: 'Pending Orders', href: '/rider/pending' },
        //     { title: 'Completed Orders', href: '/rider/completed' },
        //     { title: 'Cancelled Orders', href: '/rider/cancelled' },
        // ],
    },

];

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Repository',
    //     href: 'https://github.com/laravel/react-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits',
    //     icon: BookOpen,
    // },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
