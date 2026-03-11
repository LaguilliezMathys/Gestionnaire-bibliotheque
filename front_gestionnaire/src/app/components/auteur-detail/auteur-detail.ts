import { Component, inject, OnInit, signal } from '@angular/core';
import { SlicePipe } from '@angular/common';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { ApiService } from '../../services/api-service';
import { Auteur } from '../../models/auteur';

@Component({
  selector: 'app-auteur-detail',
  imports: [RouterLink, SlicePipe],
  templateUrl: './auteur-detail.html',
  styleUrl: './auteur-detail.css',
})
export class AuteurDetail implements OnInit {
  private route = inject(ActivatedRoute);
  private apiService = inject(ApiService);

  auteur = signal<Auteur | null>(null);

  ngOnInit() {
    const id = Number(this.route.snapshot.paramMap.get('id'));
    this.apiService.getAuteur(id).subscribe({
      next: (data) => this.auteur.set(data),
    });
  }
}
