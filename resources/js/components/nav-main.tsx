import { useState } from 'react';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { ChevronDown, ChevronRight } from 'lucide-react';

export function NavMain({ items = [] }: { items: NavItem[] }) {
    const page = usePage();
    const [openMenus, setOpenMenus] = useState<string[]>([]);

    const toggleMenu = (title: string) => {
        setOpenMenus(prev =>
            prev.includes(title) ? prev.filter(t => t !== title) : [...prev, title]
        );
    };

    const isActive = (href?: string, children?: NavItem[]) => {
        if (!href && children) {
            // If a parent has a child with active route
            return children.some(child => page.url.startsWith(child.href || ''));
        }
        return href ? page.url.startsWith(href) : false;
    };

    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupLabel>Platform</SidebarGroupLabel>
            <SidebarMenu>
                {items.map((item) => {
                    const active = isActive(item.href, item.children);
                    const isOpen = openMenus.includes(item.title);

                    return (
                        <SidebarMenuItem key={item.title}>
                            {item.children ? (
                                <>
                                    <SidebarMenuButton
                                        onClick={() => toggleMenu(item.title)}
                                        isActive={active}
                                    >
                                        {item.icon && <item.icon />}
                                        <span>{item.title}</span>
                                        {isOpen ? (
                                            <ChevronDown className="ml-auto h-4 w-4" />
                                        ) : (
                                            <ChevronRight className="ml-auto h-4 w-4" />
                                        )}
                                    </SidebarMenuButton>
                                    {isOpen && (
                                        <div className="ml-6 mt-1 space-y-1">
                                            {item.children.map((child) => (
                                                <Link
                                                    key={child.title}
                                                    href={child.href || '#'}
                                                    className={`block rounded px-2 py-1 text-sm ${
                                                        isActive(child.href) ? 'bg-blue-500 text-white' : 'hover:bg-gray-200'
                                                    }`}
                                                >
                                                    {child.title}
                                                </Link>
                                            ))}
                                        </div>
                                    )}
                                </>
                            ) : (
                                <SidebarMenuButton
                                    asChild
                                    isActive={active}
                                >
                                    <Link href={item.href || '#'}>
                                        {item.icon && <item.icon />}
                                        <span>{item.title}</span>
                                    </Link>
                                </SidebarMenuButton>
                            )}
                        </SidebarMenuItem>
                    );
                })}
            </SidebarMenu>
        </SidebarGroup>
    );
}
