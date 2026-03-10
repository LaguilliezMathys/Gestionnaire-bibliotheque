import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Categorie } from '../models/categorie';
import { Livre, LivreListResponse } from '../models/livre';
import { Auteur } from '../models/auteur';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private http = inject(HttpClient);
  private apiUrl = 'https://127.0.0.1:8000/api';

  // Livres
  getLivres(params?: { titre?: string; categorieId?: number; auteurId?: number; langue?: string; page?: number; limit?: number }): Observable<LivreListResponse> {
    let httpParams = new HttpParams();
    if (params) {
      if (params.titre) httpParams = httpParams.set('titre', params.titre);
      if (params.categorieId) httpParams = httpParams.set('categorieId', params.categorieId.toString());
      if (params.auteurId) httpParams = httpParams.set('auteurId', params.auteurId.toString());
      if (params.langue) httpParams = httpParams.set('langue', params.langue);
      if (params.page) httpParams = httpParams.set('page', params.page.toString());
      if (params.limit) httpParams = httpParams.set('limit', params.limit.toString());
    }
    return this.http.get<LivreListResponse>(`${this.apiUrl}/livres`, { params: httpParams });
  }

  getLivre(id: number): Observable<Livre> {
    return this.http.get<Livre>(`${this.apiUrl}/livres/${id}`);
  }

  getLangues(): Observable<string[]> {
    return this.http.get<string[]>(`${this.apiUrl}/livres/langues`);
  }

  // Catégories
  getCategories(): Observable<Categorie[]> {
    return this.http.get<Categorie[]>(`${this.apiUrl}/categories`);
  }

  getCategorie(id: number): Observable<Categorie> {
    return this.http.get<Categorie>(`${this.apiUrl}/categories/${id}`);
  }

  // Auteurs
  getAuteurs(): Observable<Auteur[]> {
    return this.http.get<Auteur[]>(`${this.apiUrl}/auteurs`);
  }

  getAuteur(id: number): Observable<Auteur> {
    return this.http.get<Auteur>(`${this.apiUrl}/auteurs/${id}`);
  }
}