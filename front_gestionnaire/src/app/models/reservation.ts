import { Livre } from './livre';

export interface Reservation {
  id: number;
  dateReservation: string;
  livre?: Livre;
  expiree?: boolean;
}
