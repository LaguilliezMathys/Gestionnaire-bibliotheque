import { Component, inject, signal, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { DatePipe } from '@angular/common';
import { ApiService } from '../../services/api-service';
import { Article } from '../../models/article';

@Component({
  selector: 'app-articles-list',
  imports: [DatePipe],
  template: `
    <div class="container mt-4">
      <h2>Articles</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Catégorie</th>
            <th>Publié</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @for (art of articles(); track art.id) {
            <tr>
              <td>{{ art.id }}</td>
              <td>{{ art.titre }}</td>
              <td>{{ art.categorie?.libelle }}</td>
              <td>
                @if (art.publie) {
                  <span class="badge bg-success">Oui</span>
                } @else {
                  <span class="badge bg-danger">Non</span>
                }
              </td>
              <td>{{ art.date_creation | date:'dd/MM/yyyy' }}</td>
            </tr>
          }
        </tbody>
      </table>
    </div>
  `
})
export class ArticlesList implements OnInit {
  private apiService = inject(ApiService);
  private route = inject(ActivatedRoute);
  articles = signal<Article[]>([]);

  ngOnInit() {
    this.apiService.getArticles().subscribe(data => {
      const categorieId = this.route.snapshot.queryParams['categorie'];
      if (categorieId) {
        this.articles.set(data.filter(a => a.categorie?.id === +categorieId));
      } else {
        this.articles.set(data);
      }
    });
  }
}