<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import ConfirmationDialog from '@/components/ConfirmationDialog.vue';
import { MoreHorizontal, PlusIcon, PencilIcon, TrashIcon, Eye  } from 'lucide-vue-next';

interface Post {
  id: number;
  title: string;
  content: string;
  user_id: number;
  is_published: boolean;
  created_at: string;
  updated_at: string;
  user: {
    id: number;
    name: string;
    email: string;
  };
}

interface Props {
  posts?: {
    data: Post[];
    links?: Array<{
      url: string | null;
      label: string;
      active: boolean;
    }>;
    from?: number | null;
    to?: number | null;
    total?: number;
    current_page?: number;
    last_page?: number;
  };
  filters?: {
    status?: string | null;
  };
}

const props = defineProps<Props>();

const status = ref(props.filters?.status || '');
const showDeleteDialog = ref(false);
const postToDelete = ref<number | null>(null);
const showDropdown = ref<number | null>(null);

const breadcrumbs: BreadcrumbItem[] = [

  {
    title: 'Posts',
    href: route('posts.index'),
  },
];

watch(status, (value) => {
  router.get(route('posts.index'), { status: value }, {
    preserveState: true,
    replace: true,
  });
});

const confirmDelete = (id: number, e: Event) => {
  e.preventDefault();
  e.stopPropagation();
  postToDelete.value = id;
  showDeleteDialog.value = true;
};

const deletePost = () => {
  if (postToDelete.value) {
    router.delete(route('posts.destroy', postToDelete.value), {
      onSuccess: () => {
        showDeleteDialog.value = false;
        postToDelete.value = null;
      }
    });
  }
};

const toggleDropdown = (id: number, e: Event) => {
  e.preventDefault();
  e.stopPropagation();
  showDropdown.value = showDropdown.value === id ? null : id;
};

const getPageNumber = (label: string): string => {
  return label.replace(/&laquo;/g, '«')
              .replace(/&raquo;/g, '»')
              .replace(/<\/?span[^>]*>/g, '');
};

const closeAllDropdowns = () => {
  showDropdown.value = null;
};
</script>

<template>
  <Head title="Blog Posts" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div
      class="flex h-full flex-1 flex-col p-6 bg-white dark:bg-black text-gray-900 dark:text-white"
      @click="closeAllDropdowns"
    >
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Blog Posts</h1>
        <Button variant="default" class="bg-green-600 hover:bg-green-700 text-white dark:bg-white dark:text-black dark:hover:bg-gray-200">
          <Link :href="route('posts.create')" class="flex items-center gap-2">
            <PlusIcon class="w-4 h-4" />
            Add New Post
          </Link>
        </Button>
      </div>

      <div class="mb-6">
        <div class="text-sm font-medium mb-2">Filter by Status</div>
        <div class="flex gap-2">
          <Button
            variant="outline"
            size="sm"
            @click="status = ''"
            class="relative border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
            :class="{
              'bg-green-100 border-green-300 text-green-800 dark:bg-black dark:border-white dark:text-white': status === ''
            }"
          >
            All
            <span
              v-if="status === ''"
              class="absolute -top-1 -right-1 w-2 h-2 bg-green-600 dark:bg-white rounded-full"
            ></span>
          </Button>
          <Button
            variant="outline"
            size="sm"
            @click="status = 'published'"
            class="relative border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
            :class="{
              'bg-green-100 border-green-300 text-green-800 dark:bg-black dark:border-white dark:text-white': status === 'published'
            }"
          >
            Published
            <span
              v-if="status === 'published'"
              class="absolute -top-1 -right-1 w-2 h-2 bg-green-600 dark:bg-white rounded-full"
            ></span>
          </Button>
          <Button
            variant="outline"
            size="sm"
            @click="status = 'unpublished'"
            class="relative border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
            :class="{
              'bg-green-100 border-green-300 text-green-800 dark:bg-black dark:border-white dark:text-white': status === 'unpublished'
            }"
          >
            Unpublished
            <span
              v-if="status === 'unpublished'"
              class="absolute -top-1 -right-1 w-2 h-2 bg-green-600 dark:bg-white rounded-full"
            ></span>
          </Button>
        </div>
      </div>

      <div class="flex-1 border rounded-md overflow-hidden border-gray-200 dark:border-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
          <thead class="bg-gray-50 dark:bg-[#111827]">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Title
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Author
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Publish Status
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>

          <tbody class="bg-white dark:bg-black divide-y divide-gray-200 dark:divide-gray-800">
            <tr
              v-for="post in props.posts?.data || []"
              :key="post.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-900 cursor-pointer transition-colors"
              @click="router.visit(route('posts.show', post.id))"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="font-medium text-gray-900 dark:text-white">{{ post.title }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-gray-700 dark:text-gray-300">{{ post.author.name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  v-if="post.is_published"
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-white"
                >
                  Published
                </span>
                <span
                  v-else
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-transparent dark:text-yellow-500 dark:border dark:border-yellow-500"
                >
                  Unpublished
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative">
                <button
                  class="text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none"
                  @click.stop="toggleDropdown(post.id, $event)"
                >
                  <MoreHorizontal class="h-5 w-5" />
                </button>

                <div
                  v-if="showDropdown === post.id"
                  class="absolute right-1 mt-2 w-32  rounded-md shadow-lg bg-white dark:bg-[#202225] border border-gray-200 dark:border-gray-700 z-10"
                  @click.stop
                >
                  <div class="py-1">

                    <Link
                      :href="route('posts.show', post.id)"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                      @click.stop
                    >
                      <span class="flex items-center">
                        <Eye class="mr-2 h-4 w-4" />
                        View
                      </span>
                    </Link>
                    <Link
                      :href="route('posts.edit', post.id)"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                      @click.stop
                    >
                      <span class="flex items-center">
                        <PencilIcon class="mr-2 h-4 w-4" />
                        Edit
                      </span>
                    </Link>
                    <button
                      class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:text-red-400 dark:hover:bg-gray-700"
                      @click.stop="confirmDelete(post.id, $event)"
                    >
                      <span class="flex items-center">
                        <TrashIcon class="mr-2 h-4 w-4" />
                        Delete
                      </span>
                    </button>
                  </div>
                </div>
              </td>
            </tr>
            <tr v-if="!props.posts?.data?.length">
              <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                No posts found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="props.posts?.links?.length" class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-500 dark:text-gray-400">
          Showing {{ props.posts?.from || 0 }} to {{ props.posts?.to || 0 }} of {{ props.posts?.total || 0 }} posts
        </div>
        <div class="flex space-x-1">
          <button
            v-for="(link, i) in props.posts?.links || []"
            :key="i"
            class="relative rounded-sm inline-flex items-center px-4 py-2 text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
            :class="{
              ' text-green-600  dark:text-black': link.active,
              'cursor-not-allowed opacity-50': !link.url
            }"
            :disabled="!link.url"
            @click="link.url ? router.get(link.url) : null"
          >
            {{ getPageNumber(link.label) }}
            <span
              v-if="link.active"
              class="absolute -top-1 -right-1 w-2 h-2 bg-green-600  rounded-full"
            ></span>
          </button>
        </div>
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
