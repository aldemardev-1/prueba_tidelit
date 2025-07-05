<script lang="ts" setup>
import type { Book } from '../types';

interface BookCardProps {
  book: Book;
}
const props = defineProps<BookCardProps>(); 
</script>

<template>

  <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 border border-gray-100">
    <h3 class="text-2xl font-bold text-indigo-700 mb-2">{{ props.book.title }}</h3>
    <p class="text-gray-600 text-sm mb-1">
      <span class="font-semibold">Autor:</span> {{ props.book.author }}
    </p>

    <p class="text-gray-600 text-sm mb-3">
      <span class="font-semibold">Año:</span> {{ props.book.published_year }}
    </p>

    <div class="flex items-center justify-between pt-3 border-t border-gray-200 mt-3">
      <span class="text-lg font-semibold text-gray-800">
        Rating Promedio:
      </span>
      <span class="text-xl font-bold text-yellow-500 flex items-center">
        {{ props.book.average_rating !== null ? props.book.average_rating.toFixed(2) : 'Sin reseñas' }}
        <span v-if="props.book.average_rating !== null" class="ml-1">⭐</span>
      </span>
    </div>
  </div>
</template>