<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue
} from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle
} from '@/components/ui/card';
import { ArrowLeftIcon } from 'lucide-vue-next';

interface Author {
  id: number;
  name: string;
}

interface Post {
  id: number;
  title: string;
  content: string;
  author_id: number;
  is_published: boolean;
}

interface Props {
  post?: Post;
  authors: Author[];
}

const props = defineProps<Props>();

const form = useForm({
  title: props.post?.title || '',
  content: props.post?.content || '',
  author_id: props.post?.author_id || '',
  is_published: props.post?.is_published || false,
});

const isEditing = computed(() => !!props.post);
const pageTitle = computed(() => isEditing.value ? 'Edit Blog Post' : 'Create New Blog Post');
const submitLabel = computed(() => isEditing.value ? 'Update Post' : 'Create Post');

const breadcrumbs: BreadcrumbItem[] = [

  {
    title: 'Posts',
    href: route('posts.index'),
  },
  {
    title: isEditing.value ? 'Edit Post' : 'Create Post',
    href: isEditing.value && props.post ? route('posts.edit', props.post.id) : route('posts.create'),
  },
];

function submit() {
  if (isEditing.value && props.post) {
    form.put(route('posts.update', props.post.id));
  } else {
    form.post(route('posts.store'));
  }
}
</script>

<template>
  <Head :title="pageTitle" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col p-6 bg-white dark:bg-black text-gray-900 dark:text-white">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold">{{ pageTitle }}</h2>
        <Button
          variant="outline"
          class="border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
        >
          <Link :href="route('posts.index')" class="flex items-center gap-2">
            <ArrowLeftIcon class="w-4 h-4" />
            Back to List
          </Link>
        </Button>
      </div>

      <Card class="flex-1 bg-white dark:bg-[#111827] border-gray-200 dark:border-gray-800">
        <CardHeader>
          <CardTitle>Post Details</CardTitle>
          <CardDescription class="text-gray-500 dark:text-gray-400">
            Fill in the details for your blog post.
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
              <Label for="title" class="text-gray-700 dark:text-gray-300">Title</Label>
              <Input
                id="title"
                v-model="form.title"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                :class="{ 'border-red-500': form.errors.title }"
                placeholder="Enter post title"
              />
              <p v-if="form.errors.title" class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.title }}
              </p>
            </div>

            <div class="space-y-2">
              <Label for="author_id" class="text-gray-700 dark:text-gray-300">Author</Label>
              <Select v-model="form.author_id">
                <SelectTrigger
                  class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                  :class="{ 'border-red-500': form.errors.author_id }"
                >
                  <SelectValue placeholder="Select an author" />
                </SelectTrigger>
                <SelectContent class="bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                  <SelectItem
                    v-for="author in authors"
                    :key="author.id"
                    :value="author.id"
                    class="text-gray-900 dark:text-gray-100"
                  >
                    {{ author.name }}
                  </SelectItem>
                </SelectContent>
              </Select>
              <p v-if="form.errors.author_id" class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.author_id }}
              </p>
            </div>

            <div class="space-y-2">
              <Label for="content" class="text-gray-700 dark:text-gray-300">Content</Label>
              <Textarea
                id="content"
                v-model="form.content"
                rows="10"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                :class="{ 'border-red-500': form.errors.content }"
                placeholder="Write your post content here..."
              />
              <p v-if="form.errors.content" class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.content }}
              </p>
            </div>

            <div class="flex items-center space-x-2">
              <Switch
                id="is_published"
                v-model="form.is_published"
              />
              <Label for="is_published" class="text-gray-700 dark:text-gray-300">
                {{ form.is_published ? 'Published' : 'Unpublished' }}
              </Label>
              <p v-if="form.errors.is_published" class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.is_published }}
              </p>
            </div>
          </form>
        </CardContent>
        <CardFooter class="flex justify-end border-t border-gray-200 dark:border-gray-800 pt-6">
          <Button
            type="submit"
            class="bg-green-600 hover:bg-green-700 text-white dark:bg-white dark:text-black dark:hover:bg-gray-200"
            @click="submit"
            :disabled="form.processing"
          >
            {{ submitLabel }}
          </Button>
        </CardFooter>
      </Card>
      
    </div>
  </AppLayout>
</template>
