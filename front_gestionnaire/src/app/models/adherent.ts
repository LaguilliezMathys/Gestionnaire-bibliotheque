export interface Adherent {
  id: number;
  email: string;
  nom: string;
  prenom: string;
  numTel?: string;
  adressePostale?: string;
  dateAdhesion: string;
  actif: boolean;
}
