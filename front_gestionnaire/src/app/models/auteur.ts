export interface Auteur {
  id: number;
  nom: string;
  prenom: string;
  dateNaissance?: string;
  dateDeces?: string;
  nationalite?: string;
  description?: string;
  photo?: string;
  livres?: any[];
}
