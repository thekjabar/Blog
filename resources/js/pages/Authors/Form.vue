<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
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
  email: string;
  bio: string | null;
  is_active: boolean;
}

interface Props {
  author?: Author;
}

const props = defineProps<Props>();

const form = useForm({
  name: props.author?.name || '',
  email: props.author?.email || '',
  bio: props.author?.bio || '',
  is_active: props.author?.is_active !== undefined ? props.author.is_active : true,
});

const isEditing = computed(() => !!props.author);
const pageTitle = computed(() => isEditing.value ? 'Edit Author' : 'Create New Author');
const submitLabel = computed(() => isEditing.value ? 'Update Author' : 'Create Author');

const breadcrumbs: BreadcrumbItem[] = [

  {
    title: 'Authors',
    href: route('authors.index'),
  },
  {
    title: isEditing.value ? 'Edit Author' : 'Create Author',
    href: isEditing.value && props.author ? route('authors.edit', props.author.id) : route('authors.create'),
  },
];

function submit() {
  if (isEditing.value && props.author) {
    form.put(route('authors.update', props.author.id));
  } else {
    form.post(route('authors.store'));
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
          <Link :href="route('authors.index')" class="flex items-center gap-2">
            <ArrowLeftIcon class="w-4 h-4" />
            Back to List
          </Link>
        </Button>
      </div>

      <Card class="flex-1 bg-white dark:bg-[#1a1b1d] border-gray-200 dark:border-gray-800">
        <CardHeader>
          <CardTitle>Author Details</CardTitle>
          <CardDescription class="text-gray-500 dark:text-gray-400">
            Fill in the details for the author.
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
              <Label for="name" class="text-gray-700 dark:text-gray-300">Name</Label>
              <Input
                id="name"
                v-model="form.name"
                :class="{ 'border-red-500': form.errors.name }"
                placeholder="Enter author's name"
              />
              <p v-if="form.errors.name" class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.name }}
              </p>
            </div>

            <div class="space-y-2">
              <Label for="email" class="text-gray-700 dark:text-gray-300">Email</Label>
              <Input
                id="email"
                type="email"
                v-model="form.email"
                :class="{ 'border-red-500': form.errors.email }"
                placeholder="Enter author's email"
              />
              <p v-if="form.errors.email" class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.email }}
              </p>
            </div>

            <div class="space-y-2">
              <Label for="bio" class="text-gray-700 dark:text-gray-300">Bio</Label>
              <Textarea
                id="bio"
                v-model="form.bio"
                rows="6"
                :class="{ 'border-red-500': form.errors.bio }"
                placeholder="Enter author's biography"
              />
              <p v-if="form.errors.bio" class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.bio }}
              </p>
            </div>

            <div class="flex items-center space-x-2">
              <Switch
                id="is_active"
                v-model="form.is_active"
              />
              <Label for="is_active" class="text-gray-700 dark:text-gray-300">
                {{ form.is_active ? 'Active' : 'Inactive' }}
              </Label>
              <p v-if="form.errors.is_active" class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.is_active }}
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
