<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" :class="className" v-bind="$attrs">
        <path
            fill="currentColor"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M8 4a4 4 0 00-4 4v24a4 4 0 004 4h24a4 4 0 004-4V8a4 4 0 00-4-4H8zm0 2h24a2 2 0 012 2v24a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2zm4 6a1 1 0 011-1h14a1 1 0 110 2H13a1 1 0 01-1-1zm0 6a1 1 0 011-1h14a1 1 0 110 2H13a1 1 0 01-1-1zm0 6a1 1 0 011-1h14a1 1 0 110 2H13a1 1 0 01-1-1zm0 6a1 1 0 011-1h8a1 1 0 110 2h-8a1 1 0 01-1-1z"
        />
    </svg>
</template>
