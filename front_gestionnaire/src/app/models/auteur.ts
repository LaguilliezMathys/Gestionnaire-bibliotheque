export interface Auteur {
  id: number;
  nom: string;
  prenom: string;
  biographie?: string;
  photo?: string;
  livres?: any[];
}
