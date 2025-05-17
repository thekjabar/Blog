import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;


export interface Post {
    id: number;
    title: string;
    content: string;
    user_id: number;
    is_published: boolean;
    created_at: string;
    updated_at: string;
    user?: User;
  }

  export interface PaginatedData<T> {
    data: T[];
    links: Array<{
      url: string | null;
      label: string;
      active: boolean;
    }>;
    meta: {
      current_page: number;
      from: number | null;
      last_page: number;
      links: Array<{
        url: string | null;
        label: string;
        active: boolean;
      }>;
      path: string;
      per_page: number;
      to: number | null;
      total: number;
    };
    from: number | null;
    to: number | null;
    total: number;
  }
