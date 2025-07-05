import axios, { type AxiosInstance , type AxiosResponse, AxiosError } from 'axios';
import { type Book, type Review } from '../types'; // Importa las interfaces de tipos definidas

const API_BASE_URL: string = 'http://127.0.0.1:8000/api';

const apiClient: AxiosInstance = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
  timeout: 10000,
});

apiClient.interceptors.request.use(
  (config) => {
    return config;
  },
  (error: AxiosError) => {
    console.error('Error en la petición (interceptado):', error);
    return Promise.reject(error);
  }
);

apiClient.interceptors.response.use(
  (response: AxiosResponse) => {
    return response; 
  },
  (error: AxiosError) => {
    console.error('Error en la respuesta de la API (interceptado):', error.response?.status, error.response?.data);

    if (error.response?.status === 401) {
      console.warn('Sesión expirada o no autorizada. Por favor, inicia sesión de nuevo.');
    }
    if (error.response?.status === 422) {
      console.warn('Error de validación:', error.response.data);
    }
    if (error.response?.status === 500) {
      console.error('Error interno del servidor. Por favor, inténtalo más tarde.');
    }
    return Promise.reject(error);
  }
);


export const getBooks = async (): Promise<Book[]> => {
  try {
    const response: AxiosResponse<Book[]> = await apiClient.get('/books');
    return response.data; 
  } catch (error) {
    throw error;
  }
};

export const addReview = async (reviewData: Review): Promise<any> => {
  try {
    const response: AxiosResponse<any> = await apiClient.post('/reviews', reviewData);
    return response.data;
  } catch (error) {
    throw error;
  }
};