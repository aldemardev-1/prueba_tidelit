<script setup lang="ts">
import { ref } from 'vue';
import { addReview } from '../services/api'; 
import type { Book, Review } from '../types';

interface AddReviewFormProps {
  books: Book[];
}
const props = defineProps<AddReviewFormProps>(); 

const emit = defineEmits(['review-added']);

const selectedBookId = ref<number | ''>(''); 
const rating = ref<number | ''>('');     
const comment = ref<string>('');             
const submitMessage = ref<string | null>(null); 
const isSubmitting = ref<boolean>(false);     
const errors = ref<Record<string, string>>({}); 


const handleSubmit = async () => {
  submitMessage.value = null;
  errors.value = {};
  isSubmitting.value = true; 
  const newErrors: Record<string, string> = {};
  console.log('selectBookId: ', selectedBookId.value, rating.value, comment.value)
  if (!selectedBookId.value) {
    newErrors.book_id = 'Por favor, selecciona un libro.';
  }
  if (rating.value === '' || rating.value < 1 || rating.value > 5) {
    newErrors.rating = 'El rating debe ser entre 1 y 5.';
  }
  if (comment.value.trim() === '') {
    newErrors.comment = 'El comentario no puede estar vacío.';
  }
  if (comment.value.trim().length < 10) {
    newErrors.comment = 'El comentario no puede estar vacío y debe tener al menos 10 caracteres.';
  }

  if (Object.keys(newErrors).length > 0) {
    errors.value = newErrors;
    isSubmitting.value = false;
    return; 
  }

  try {
    const reviewData: Review = {
      book_id: Number(selectedBookId.value),
      rating: Number(rating.value),        
      comment: comment.value.trim(),       
    };


    const response = await addReview(reviewData);

    if (response) {
      submitMessage.value = '¡Reseña añadida con éxito!'; 
      selectedBookId.value = '';
      rating.value = '';
      comment.value = '';
      emit('review-added');
    }
  } catch (error: any) {

    console.error('Error al enviar la reseña:', error);

    if (!error.response || !error.response.data) {
      submitMessage.value = 'Ocurrió un error inesperado al añadir la reseña.';
      return;
    } 
    if (error.response.data.errors) {
        const backendErrors: Record<string, string> = {};
        error.response.data.errors.forEach((msg: string) => {
          if (msg.toLowerCase().includes('rating')) backendErrors.rating = msg;
          else if (msg.toLowerCase().includes('book')) backendErrors.book_id = msg;
          else if (msg.toLowerCase().includes('comment')) backendErrors.comment = msg;
          else submitMessage.value = `Error: ${msg}`; 
        });
        errors.value = backendErrors;
    }
    if (error.response.data.message) {
        submitMessage.value = `Error: ${error.response.data.message}`;
    } 
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 mt-8">
    <h2 class="text-2xl font-bold text-indigo-700 mb-6 text-center">Añadir Nueva Reseña</h2>
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label htmlFor="book-select" class="block text-gray-700 text-sm font-bold mb-2">
          Seleccionar Libro:
        </label>
        <select
          id="book-select"
          class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          :class="{ 'border-red-500': errors.book_id }"
          v-model="selectedBookId"
          :disabled="isSubmitting"
        >
          <option value="">-- Selecciona un libro --</option>
          <option v-for="book in props.books" :key="book.id" :value="book.id">
            {{ book.title }}
          </option>
        </select>
        <p v-if="errors.book_id" class="text-red-500 text-xs italic mt-1">{{ errors.book_id }}</p>
      </div>

      <div>
        <label htmlFor="rating" class="block text-gray-700 text-sm font-bold mb-2">
          Rating (1-5):
        </label>
        <input
          type="number"
          id="rating"
          class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          :class="{ 'border-red-500': errors.rating }"
          min="1"
          max="5"
          v-model="rating"
          :disabled="isSubmitting"
        />
        <p v-if="errors.rating" class="text-red-500 text-xs italic mt-1">{{ errors.rating }}</p>
      </div>
      <div>
        <label htmlFor="comment" class="block text-gray-700 text-sm font-bold mb-2">
          Comentario:
        </label>
        <textarea
          id="comment"
          class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-24 resize-none"
          :class="{ 'border-red-500': errors.comment }"
          placeholder="Escribe tu reseña aquí..."
          v-model="comment"
          :disabled="isSubmitting"
        ></textarea>
        <p v-if="errors.comment" class="text-red-500 text-xs italic mt-1">{{ errors.comment }}</p>
      </div>
      <button
        type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-xl focus:outline-none focus:shadow-outline transition-all duration-200 ease-in-out w-full disabled:opacity-50 disabled:cursor-not-allowed"
        :disabled="isSubmitting"
      >
        {{ isSubmitting ? 'Enviando...' : 'Añadir Reseña' }}
      </button>
      <p v-if="submitMessage" class="text-center mt-4" :class="{ 'text-red-600': submitMessage.includes('Error'), 'text-green-600': !submitMessage.includes('Error') }">
        {{ submitMessage }}
      </p>
    </form>
  </div>
</template>