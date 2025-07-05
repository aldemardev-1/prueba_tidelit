<script setup lang="ts">
import { ref, onMounted, provide } from 'vue';
import { getBooks } from './services/api'; 
import BookList from './components/BookList.vue'; 
import AddReviewForm from './components/AddReviewForm.vue'; 
import type { Book } from './types'; 


const books = ref<Book[]>([]);           
const isLoading = ref<boolean>(true);    
const error = ref<string | null>(null);  

const fetchBooks = async () => {
  isLoading.value = true;  
  error.value = null;      
  try {
    const fetchedBooks = await getBooks();
    books.value = fetchedBooks;        
  } catch (err: any) {

    console.error('Error fetching books:', err);
    error.value = 'No se pudieron cargar los libros. Por favor, verifica que el backend de Symfony esté funcionando.';
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchBooks();
});

</script>

<template>

  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-4 sm:p-6 lg:p-8 font-sans">
    <header class="bg-white p-6 rounded-xl shadow-md mb-8 text-center border-b-4 border-indigo-600">
      <h1 class="text-4xl font-extrabold text-indigo-800 tracking-tight">
        <span class="text-yellow-500">📚</span> Biblioteca de Reseñas
      </h1>
      <p class="text-gray-600 mt-2 text-lg">Explora y comparte tus opiniones sobre libros.</p>
    </header>

    <main class="max-w-6xl mx-auto bg-white p-6 md:p-8 rounded-xl shadow-2xl border border-gray-200">
      <section class="mb-10">
        <h2 class="text-3xl font-bold text-indigo-700 mb-6 text-center">Nuestra Colección de Libros</h2>
        <BookList :books="books" :isLoading="isLoading" :error="error" />
      </section>

      <section>
        <AddReviewForm :books="books" @review-added="fetchBooks" />
      </section>
    </main>

    <footer class="text-center text-gray-500 text-sm mt-10">
      <p>&copy; {{ new Date().getFullYear() }} Biblioteca de Reseñas. Todos los derechos reservados.</p>
    </footer>
  </div>
</template>