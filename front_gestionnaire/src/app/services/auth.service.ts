import { Injectable, inject, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { Observable } from 'rxjs';
import { Emprunt } from '../models/emprunt';
import { Reservation } from '../models/reservation';
import { Adherent } from '../models/adherent';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private http = inject(HttpClient);
  private router = inject(Router);
  private apiUrl = 'https://127.0.0.1:8000/api';

  isLoggedIn = signal(false);
  userName = signal('');
  userEmail = signal('');

  constructor() {
    const token = localStorage.getItem('jwt_token');
    if (token) {
      this.isLoggedIn.set(true);
      this.loadUserInfo();
    }
  }

  login(email: string, password: string) {
    return this.http.post<{ token: string }>(`${this.apiUrl}/login_check`, { email, password });
  }

  handleLoginSuccess(token: string) {
    localStorage.setItem('jwt_token', token);
    this.isLoggedIn.set(true);
    this.loadUserInfo();
  }

  logout() {
    localStorage.removeItem('jwt_token');
    this.isLoggedIn.set(false);
    this.userName.set('');
    this.userEmail.set('');
    this.router.navigate(['/']);
  }

  getToken(): string | null {
    return localStorage.getItem('jwt_token');
  }

  // Profil
  getProfil(): Observable<Adherent> {
    return this.http.get<Adherent>(`${this.apiUrl}/user/me`);
  }

  updateProfil(data: { numTel: string; adressePostale: string }): Observable<Adherent> {
    return this.http.put<Adherent>(`${this.apiUrl}/user/profil`, data);
  }

  // Emprunts
  getEmprunts(): Observable<Emprunt[]> {
    return this.http.get<Emprunt[]>(`${this.apiUrl}/user/emprunts`);
  }

  // Réservations
  getReservations(): Observable<Reservation[]> {
    return this.http.get<Reservation[]>(`${this.apiUrl}/user/reservations`);
  }

  createReservation(livreId: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/reservations`, { livreId });
  }

  cancelReservation(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/reservations/${id}`);
  }

  private loadUserInfo() {
    this.http.get<any>(`${this.apiUrl}/user/me`).subscribe({
      next: (user) => {
        this.userEmail.set(user.email);
        this.userName.set(user.prenom + ' ' + user.nom);
      },
      error: () => {
        this.logout();
      }
    });
  }
}