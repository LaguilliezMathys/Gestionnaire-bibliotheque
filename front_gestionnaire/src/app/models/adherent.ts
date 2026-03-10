export interface Adherent {
  id: number;
  email: string;
  nom: string;
  prenom: string;
  telephone?: string;
  adresse?: string;
  dateInscription: string;
  actif: boolean;
}
