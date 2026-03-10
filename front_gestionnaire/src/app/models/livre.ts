import { Categorie } from './categorie';
import { Auteur } from './auteur';

export interface Livre {
  id: number;
  titre: string;
  isbn: string;
  resume?: string;
  langue: string;
  dateSortie: string;
  couverture?: string;
  disponible: boolean;
  categorie?: Categorie;
  auteurs?: Auteur[];
}

export interface LivreListResponse {
  livres: Livre[];
  total: number;
  page: number;
  limit: number;
}