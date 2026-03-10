import { Livre } from './livre';

export interface Emprunt {
  id: number;
  dateEmprunt: string;
  dateRetourPrevue: string;
  dateRetourEffective?: string;
  livre?: Livre;
  enRetard?: boolean;
  enCours?: boolean;
}
