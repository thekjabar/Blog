<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import ConfirmationDialog from '@/components/ConfirmationDialog.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { ArrowLeftIcon, PencilIcon, TrashIcon, UserIcon } from 'lucide-vue-next';

interface Author {
  id: number;
  name: string;
  email: string;
}

interface Post {
  id: number;
  title: string;
  content: string;
  author_id: number;
  is_published: boolean;
  created_at: string;
  updated_at: string;
  author: Author;
}

interface Props {
  post: Post;
}

const props = defineProps<Props>();
const showDeleteDialog = ref(false);

const breadcrumbs: BreadcrumbItem[] = [

  {
    title: 'Posts',
    href: route('posts.index'),
  },
  {
    title: props.post.title,
    href: route('posts.show', props.post.id),
  },
];

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

function deletePost() {
  router.delete(route('posts.destroy', props.post.id));
}
</script>

<template>
  <Head :title="post.title" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col h-full bg-white dark:bg-black text-gray-900 dark:text-white">
      <div class="flex justify-between items-center p-6 pb-4 border-b border-gray-200 dark:border-gray-800">
        <h1 class="text-3xl font-bold">{{ post.title }}</h1>
        <div class="flex gap-2">
          <Button
            variant="outline"
            class="border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
          >
            <Link :href="route('posts.index')" class="flex items-center gap-2">
              <ArrowLeftIcon class="w-4 h-4" />
              Back to List
            </Link>
          </Button>
          <Button
            class="bg-green-600 hover:bg-green-700 text-white dark:bg-white dark:text-black dark:hover:bg-gray-200"
          >
            <Link :href="route('posts.edit', post.id)" class="flex items-center gap-2">
              <PencilIcon class="w-4 h-4" />
              Edit
            </Link>
          </Button>
        </div>
      </div>

      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <div class="flex justify-between items-center">
          <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
              <UserIcon class="h-6 w-6 text-gray-500 dark:text-gray-400" />
            </div>
            <div class="ml-4">
              <div class="flex items-center gap-2">
                <Link
                  :href="route('authors.show', post.author.id)"
                  class="font-medium text-gray-900 dark:text-white hover:text-green-600 dark:hover:text-green-400"
                >
                  {{ post.author.name }}
                </Link>
              </div>
              <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ formatDate(post.created_at) }}
                <span v-if="post.created_at !== post.updated_at">
                  (Updated: {{ formatDate(post.updated_at) }})
                </span>
              </div>
            </div>
          </div>
          <Badge
            v-if="post.is_published"
            class="ml-auto bg-green-100 text-green-800 dark:bg-green-800 dark:text-white border-0"
          >
            Published
          </Badge>
          <Badge
            v-else
            class="ml-auto bg-yellow-100 text-yellow-800 dark:bg-transparent dark:text-yellow-500 dark:border dark:border-yellow-500"
          >
            Unpublished
          </Badge>
        </div>
      </div>

      <div class="flex-1 p-6">
        <div class="prose max-w-none dark:prose-invert prose-lg">
          <div class="whitespace-pre-wrap">{{ post.content }}</div>
        </div>
      </div>

      <div class="p-6 border-t border-gray-200 dark:border-gray-800 flex justify-between items-center">
        <Button
          variant="destructive"
          class="bg-red-600 hover:bg-red-700 text-white dark:bg-red-900 dark:hover:bg-red-800 flex items-center gap-2"
          @click="showDeleteDialog = true"
        >
          <TrashIcon class="w-4 h-4" />
          Delete
        </Button>
        <Button
          class="bg-green-600 hover:bg-green-700 text-white dark:bg-white dark:text-black dark:hover:bg-gray-200 flex items-center gap-2"
        >
          <Link :href="route('posts.edit', post.id)" class="flex">
            <PencilIcon class="w-4 h-4 mr-2" />
            Edit
          </Link>
        </Button>
      </div>
    </div>
  </AppLayout>

  <ConfirmationDialog
    v-model:open="showDeleteDialog"
    title="Are you sure you want to delete this post?"
    description="This action cannot be undone. This will permanently delete your post and remove it from the server."
    confirm-text="Delete"
    confirm-variant="destructive"
    @confirm="deletePost"
  />
</template>
