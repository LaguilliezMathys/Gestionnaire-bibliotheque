import { Component, inject, OnInit, signal } from '@angular/core';
import { SlicePipe } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { ApiService } from '../../services/api-service';
import { Livre, LivreListResponse } from '../../models/livre';
import { Categorie } from '../../models/categorie';

@Component({
  selector: 'app-catalogue',
  imports: [FormsModule, RouterLink, SlicePipe],
  templateUrl: './catalogue.html',
  styleUrl: './catalogue.css',
})
export class Catalogue implements OnInit {
  private apiService = inject(ApiService);

  livres = signal<Livre[]>([]);
  categories = signal<Categorie[]>([]);
  langues = signal<string[]>([]);

  // Filtres
  searchTitre = '';
  selectedCategorie = 0;
  selectedLangue = '';

  // Pagination
  page = signal(1);
  limit = 12;
  total = signal(0);
  loading = signal(false);

  ngOnInit() {
    this.apiService.getCategories().subscribe(data => this.categories.set(data));
    this.apiService.getLangues().subscribe(data => this.langues.set(data));
    this.rechercher();
  }

  rechercher() {
    this.page.set(1);
    this.chargerLivres();
  }

  chargerLivres() {
    this.loading.set(true);
    const params: any = { page: this.page(), limit: this.limit };
    if (this.searchTitre) params.titre = this.searchTitre;
    if (this.selectedCategorie) params.categorieId = this.selectedCategorie;
    if (this.selectedLangue) params.langue = this.selectedLangue;

    this.apiService.getLivres(params).subscribe((data: LivreListResponse) => {
      this.livres.set(data.livres);
      this.total.set(data.total);
      this.loading.set(false);
    });
  }

  get totalPages(): number {
    return Math.ceil(this.total() / this.limit);
  }

  pagePrecedente() {
    if (this.page() > 1) {
      this.page.set(this.page() - 1);
      this.chargerLivres();
    }
  }

  pageSuivante() {
    if (this.page() < this.totalPages) {
      this.page.set(this.page() + 1);
      this.chargerLivres();
    }
  }

  resetFiltres() {
    this.searchTitre = '';
    this.selectedCategorie = 0;
    this.selectedLangue = '';
    this.rechercher();
  }
}
