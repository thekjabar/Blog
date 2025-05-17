<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowLeftIcon, PencilIcon, UserIcon, ClipboardListIcon } from 'lucide-vue-next';

interface Post {
  id: number;
  title: string;
  content: string;
  is_published: boolean;
  created_at: string;
}

interface Author {
  id: number;
  name: string;
  email: string;
  bio: string | null;
  is_active: boolean;
  created_at: string;
  updated_at: string;
  posts: Post[];
}

interface Props {
  author: Author;
  totalPosts: number;
  publishedPosts: number;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [

  {
    title: 'Authors',
    href: route('authors.index'),
  },
  {
    title: props.author.name,
    href: route('authors.show', props.author.id),
  },
];

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};
</script>

<template>
  <Head :title="author.name" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col h-full p-6 bg-white dark:bg-black text-gray-900 dark:text-white">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Author Profile</h1>
        <div class="flex space-x-2">
          <Button
            variant="outline"
            class="border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
          >
            <Link :href="route('authors.index')" class="flex items-center gap-2">
              <ArrowLeftIcon class="w-4 h-4" />
              Back to List
            </Link>
          </Button>
          <Button
            class="bg-green-600 hover:bg-green-700 text-white dark:bg-white dark:text-black dark:hover:bg-gray-200"
          >
            <Link :href="route('authors.edit', author.id)" class="flex items-center gap-2">
              <PencilIcon class="w-4 h-4" />
              Edit Author
            </Link>
          </Button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <Card class="md:col-span-1 bg-white dark:bg-[#1a1b1d] border-gray-200 dark:border-gray-800">
          <CardHeader class="pb-4 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center">
              <div class="h-16 w-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                <UserIcon class="h-8 w-8 text-gray-500 dark:text-gray-400" />
              </div>
              <div class="ml-4">
                <CardTitle class="text-xl">{{ author.name }}</CardTitle>
                <CardDescription class="text-gray-500 dark:text-gray-400">
                  {{ author.email }}
                </CardDescription>
              </div>
            </div>
          </CardHeader>
          <CardContent class="pt-6">
            <div class="mb-6">
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Status</h3>
              <Badge
                v-if="author.is_active"
                class="bg-green-100 text-green-800 dark:bg-green-800 dark:text-white"
              >
                Active
              </Badge>
              <Badge
                v-else
                class="bg-yellow-100 text-yellow-800 dark:bg-transparent dark:text-yellow-500 dark:border dark:border-yellow-500"
              >
                Inactive
              </Badge>
            </div>

            <div class="mb-6">
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Statistics</h3>
              <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-gray-100 dark:bg-[#212224] rounded-md">
                  <p class="text-xs text-gray-500 dark:text-gray-400">Total Posts</p>
                  <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ totalPosts }}</p>
                </div>
                <div class="p-3 bg-gray-100 dark:bg-[#212224] rounded-md">
                  <p class="text-xs text-gray-500 dark:text-gray-400">Published</p>
                  <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ publishedPosts }}</p>
                </div>
              </div>
            </div>

            <div class="mb-6">
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Joined</h3>
              <p class="text-gray-700 dark:text-gray-300">{{ formatDate(author.created_at) }}</p>
            </div>
          </CardContent>
        </Card>

        <div class="md:col-span-2 space-y-6">
          <Card class="bg-white dark:bg-[#1a1b1d] border-gray-200 dark:border-gray-800">
            <CardHeader>
              <CardTitle>Biography</CardTitle>
            </CardHeader>
            <CardContent>
              <p v-if="author.bio" class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ author.bio }}</p>
              <p v-else class="text-gray-500 dark:text-gray-400 italic">No biography provided</p>
            </CardContent>
          </Card>

          <Card class="bg-white dark:bg-[#1a1b1d] border-gray-200 dark:border-gray-800">
            <CardHeader class="flex flex-row items-center justify-between">
              <div>
                <CardTitle>Recent Posts</CardTitle>
                <CardDescription>Latest 5 posts by this author</CardDescription>
              </div>
              <Button
                variant="outline"
                class="border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
              >
                <Link
                  :href="route('posts.index', { author: author.id })"
                  class="flex items-center gap-2"
                >
                  <ClipboardListIcon class="w-4 h-4" />
                  View All Posts
                </Link>
              </Button>
            </CardHeader>
            <CardContent>
              <div v-if="author.posts && author.posts.length > 0" class="divide-y divide-gray-200 dark:divide-gray-800">
                <div
                  v-for="post in author.posts"
                  :key="post.id"
                  class="py-4 first:pt-0 last:pb-0"
                >
                  <div class="flex justify-between items-start">
                    <div>
                      <Link
                        :href="route('posts.show', post.id)"
                        class="text-lg font-medium text-gray-900 dark:text-white hover:text-green-600 dark:hover:text-green-400"
                      >
                        {{ post.title }}
                      </Link>
                      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ formatDate(post.created_at) }}
                      </p>
                    </div>
                    <Badge
                      v-if="post.is_published"
                      class="bg-green-100 text-green-800 dark:bg-green-800 dark:text-white"
                    >
                      Published
                    </Badge>
                    <Badge
                      v-else
                      class="bg-yellow-100 text-yellow-800 dark:bg-transparent dark:text-yellow-500 dark:border dark:border-yellow-500"
                    >
                      Unpublished
                    </Badge>
                  </div>
                </div>
              </div>
              <div v-else class="py-6 text-center text-gray-500 dark:text-gray-400">
                <p>No posts found for this author</p>
                <Link
                  :href="route('posts.create')"
                  class="mt-2 inline-block text-green-600 dark:text-green-400 hover:underline"
                >
                  Create a post for this author
                </Link>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
