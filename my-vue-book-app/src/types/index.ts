export interface Book {
  id: number;
  title: string;
  author: string;
  published_year: number;
  average_rating: number | null;
}

export interface Review {
  book_id: number;
  rating: number;
  comment: string;
}