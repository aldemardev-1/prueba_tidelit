<script setup lang="ts">
import BookCard from './BookCard.vue';
import type { Book } from '../types'; 

interface BookListProps {
  books: Book[];          
  isLoading: boolean;    
  error: string | null; 
}
const props = defineProps<BookListProps>(); 
</script>

<template>
  <div v-if="props.isLoading" class="text-center py-8 text-gray-600 text-lg">
    Cargando libros...
    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500 mx-auto mt-4"></div>
  </div>

  <div v-else-if="props.error" class="text-center py-8 text-red-600 text-lg font-semibold">
    Error: {{ props.error }}
  </div>

  <div v-else-if="props.books.length === 0" class="text-center py-8 text-gray-500 text-lg">
    No se encontraron libros. ¡Sé el primero en añadir una reseña!
  </div>
  <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <BookCard v-for="book in props.books" :key="book.id" :book="book" />
  </div>
</template>