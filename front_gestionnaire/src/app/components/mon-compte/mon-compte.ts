import { Component, inject, OnInit, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { Emprunt } from '../../models/emprunt';
import { Reservation } from '../../models/reservation';
import { Adherent } from '../../models/adherent';

@Component({
  selector: 'app-mon-compte',
  imports: [FormsModule, RouterLink],
  templateUrl: './mon-compte.html',
  styleUrl: './mon-compte.css',
})
export class MonCompte implements OnInit {
  private authService = inject(AuthService);

  profil = signal<Adherent | null>(null);
  emprunts = signal<Emprunt[]>([]);
  reservations = signal<Reservation[]>([]);
  message = signal('');
  messageType = signal('');

  // Formulaire profil
  editTelephone = '';
  editAdresse = '';
  editMode = false;

  ongletActif = 'emprunts';

  ngOnInit() {
    this.chargerDonnees();
  }

  chargerDonnees() {
    this.authService.getProfil().subscribe(data => {
      this.profil.set(data);
      this.editTelephone = data.telephone || '';
      this.editAdresse = data.adresse || '';
    });
    this.authService.getEmprunts().subscribe(data => this.emprunts.set(data));
    this.authService.getReservations().subscribe(data => this.reservations.set(data));
  }

  sauvegarderProfil() {
    this.authService.updateProfil({
      telephone: this.editTelephone,
      adresse: this.editAdresse
    }).subscribe({
      next: (data) => {
        this.profil.set(data);
        this.editMode = false;
        this.message.set('Profil mis à jour.');
        this.messageType.set('success');
      },
      error: () => {
        this.message.set('Erreur lors de la mise à jour.');
        this.messageType.set('danger');
      }
    });
  }

  annulerReservation(id: number) {
    if (confirm('Annuler cette réservation ?')) {
      this.authService.cancelReservation(id).subscribe({
        next: () => {
          this.message.set('Réservation annulée.');
          this.messageType.set('success');
          this.authService.getReservations().subscribe(data => this.reservations.set(data));
        },
        error: () => {
          this.message.set('Erreur lors de l\'annulation.');
          this.messageType.set('danger');
        }
      });
    }
  }

  deconnexion() {
    this.authService.logout();
  }
}
