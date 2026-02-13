import { Component, inject, signal, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ApiService } from '../../services/api-service';
import { Categorie } from '../../models/categorie';

@Component({
  selector: 'app-categories-list',
  imports: [RouterLink],
  template: `
    <div class="container mt-4">
      <h2>Catégories</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Libellé</th>
            <th>Nb articles</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @for (cat of categories(); track cat.id) {
            <tr>
              <td>{{ cat.id }}</td>
              <td>{{ cat.libelle }}</td>
              <td>{{ cat.articles?.length ?? 0 }}</td>
              <td>
                <a [routerLink]="['/articles']" [queryParams]="{categorie: cat.id}"
                   class="btn btn-sm btn-primary">
                  Voir articles
                </a>
              </td>
            </tr>
          }
        </tbody>
      </table>
    </div>
  `
})
export class CategoriesList implements OnInit {
  private apiService = inject(ApiService);
  categories = signal<Categorie[]>([]);

  ngOnInit() {
    this.apiService.getCategories().subscribe(data => {
      this.categories.set(data);
    });
  }
}