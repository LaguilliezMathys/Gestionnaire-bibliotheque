import { Component, inject, signal, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { ApiService } from '../../services/api-service';
import { Categorie } from '../../models/categorie';

@Component({
  selector: 'app-add-article',
  imports: [FormsModule],
  template: `
    <div class="container mt-4">
      <h2>Ajouter un article</h2>
      <form (ngSubmit)="onSubmit()">
        <div class="mb-3">
          <label for="titre" class="form-label">Titre</label>
          <input type="text" class="form-control" id="titre"
                 [(ngModel)]="titre" name="titre" required>
        </div>
        <div class="mb-3">
          <label for="contenu" class="form-label">Contenu</label>
          <textarea class="form-control" id="contenu"
                    [(ngModel)]="contenu" name="contenu" rows="4"></textarea>
        </div>
        <div class="mb-3">
          <label for="categorie" class="form-label">Catégorie</label>
          <select class="form-select" id="categorie"
                  [(ngModel)]="categorieId" name="categorie" required>
            <option value="">-- Choisir --</option>
            @for (cat of categories(); track cat.id) {
              <option [value]="cat.id">{{ cat.libelle }}</option>
            }
          </select>
        </div>
        <div class="form-check mb-3">
          <input type="checkbox" class="form-check-input" id="publie"
                 [(ngModel)]="publie" name="publie">
          <label class="form-check-label" for="publie">Publié</label>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
      </form>
    </div>
  `
})
export class AddArticle implements OnInit {
  private apiService = inject(ApiService);
  private router = inject(Router);
  categories = signal<Categorie[]>([]);

  titre = '';
  contenu = '';
  categorieId = '';
  publie = false;

  ngOnInit() {
    this.apiService.getCategories().subscribe(data => {
      this.categories.set(data);
    });
  }

  onSubmit() {
    const article = {
      titre: this.titre,
      contenu: this.contenu,
      categorie_id: +this.categorieId,
      publie: this.publie
    };
    this.apiService.createArticle(article).subscribe(() => {
      this.router.navigate(['/articles']);
    });
  }
}