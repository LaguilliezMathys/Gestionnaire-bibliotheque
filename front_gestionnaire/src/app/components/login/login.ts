import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-login',
  imports: [FormsModule],
  template: `
    <div class="container mt-4">
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="card" style="margin-top: 2rem">
            <div class="card-body p-4">
              <div class="text-center mb-4">
                <p class="display-4 mb-2" style="color: var(--primary)">Connexion</p>
                <h3 class="fw-bold">Connexion adhérent</h3>
                <p class="text-muted">Accédez à votre espace personnel</p>
              </div>
              @if (errorMessage) {
                <div class="alert alert-danger">{{ errorMessage }}</div>
              }
              <form (ngSubmit)="onSubmit()">
                <div class="mb-3">
                  <label for="email" class="form-label fw-bold">Email</label>
                  <input type="email" class="form-control" id="email"
                         [(ngModel)]="email" name="email" required
                         placeholder="votre.email&#64;exemple.fr">
                </div>
                <div class="mb-4">
                  <label for="password" class="form-label fw-bold">Mot de passe</label>
                  <input type="password" class="form-control" id="password"
                         [(ngModel)]="password" name="password" required
                         placeholder="Votre mot de passe">
                </div>
                <button type="submit" class="btn btn-primary w-100" [disabled]="loading">
                  @if (loading) {
                    <span class="spinner-border spinner-border-sm me-1"></span>
                  }
                  Se connecter
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  `
})
export class Login {
  private authService = inject(AuthService);
  private router = inject(Router);

  email = '';
  password = '';
  errorMessage = '';
  loading = false;

  onSubmit() {
    this.errorMessage = '';
    this.loading = true;
    this.authService.login(this.email, this.password).subscribe({
      next: (response) => {
        this.loading = false;
        this.authService.handleLoginSuccess(response.token);
        this.router.navigate(['/mon-compte']);
      },
      error: () => {
        this.loading = false;
        this.errorMessage = 'Email ou mot de passe incorrect.';
      }
    });
  }
}