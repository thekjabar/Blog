<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  open: boolean;
  title: string;
  description?: string;
  cancelText?: string;
  confirmText?: string;
  confirmVariant?: 'default' | 'destructive' | 'outline';
}

const props = defineProps<Props>();
const emit = defineEmits(['update:open', 'confirm', 'cancel']);

const isOpen = computed({
  get: () => props.open,
  set: (value) => emit('update:open', value),
});

const handleCancel = () => {
  isOpen.value = false;
  emit('cancel');
};

const handleConfirm = () => {
  emit('confirm');
};

// Compute button classes based on variant for both light and dark mode
const confirmButtonClass = computed(() => {
  if (props.confirmVariant === 'destructive') {
    return 'bg-red-600 hover:bg-red-700 text-white dark:bg-red-900 dark:hover:bg-red-800';
  } else if (props.confirmVariant === 'outline') {
    return 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 dark:bg-transparent dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800';
  } else {
    return 'bg-blue-600 hover:bg-blue-700 text-white dark:bg-white dark:text-black dark:hover:bg-gray-200';
  }
});
</script>

<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 dark:bg-black/70 backdrop-blur-sm"
    @click="handleCancel"
  >
    <div
      class="w-full max-w-md p-6 mx-4 bg-white dark:bg-[#202225] border border-gray-200 dark:border-gray-700 rounded-md shadow-xl animate-in fade-in-0 zoom-in-95 duration-200"
      @click.stop
    >
      <!-- Header -->
      <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ title }}</h2>
        <p v-if="description" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
          {{ description }}
        </p>
      </div>

      <!-- Footer -->
      <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
        <button
          class="mb-2 sm:mb-0 px-4 py-2 text-sm font-medium border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
          @click="handleCancel"
        >
          {{ cancelText || 'Cancel' }}
        </button>
        <button
          :class="[confirmButtonClass, 'px-4 py-2 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 dark:focus:ring-gray-700']"
          @click="handleConfirm"
        >
          {{ confirmText || 'Confirm' }}
        </button>
      </div>
    </div>
  </div>
</template>
