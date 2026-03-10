import { Component, inject, OnInit, signal } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { ApiService } from '../../services/api-service';
import { AuthService } from '../../services/auth.service';
import { Livre } from '../../models/livre';

@Component({
  selector: 'app-livre-detail',
  imports: [RouterLink],
  templateUrl: './livre-detail.html',
  styleUrl: './livre-detail.css',
})
export class LivreDetail implements OnInit {
  private route = inject(ActivatedRoute);
  private apiService = inject(ApiService);
  authService = inject(AuthService);

  livre = signal<Livre | null>(null);
  message = signal('');
  messageType = signal('');
  loading = signal(true);
  reserving = signal(false);

  ngOnInit() {
    const id = Number(this.route.snapshot.paramMap.get('id'));
    this.apiService.getLivre(id).subscribe({
      next: (data) => {
        this.livre.set(data);
        this.loading.set(false);
      },
      error: () => {
        this.loading.set(false);
        this.message.set('Livre introuvable.');
      }
    });
  }

  reserver() {
    const l = this.livre();
    if (!l) return;

    this.reserving.set(true);
    this.authService.createReservation(l.id).subscribe({
      next: () => {
        this.reserving.set(false);
        this.message.set('Réservation effectuée avec succès !');
        this.messageType.set('success');
      },
      error: (err) => {
        this.reserving.set(false);
        this.message.set(err.error?.message || 'Erreur lors de la réservation.');
        this.messageType.set('danger');
      }
    });
  }
}
