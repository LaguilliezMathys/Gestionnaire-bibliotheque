import { Routes } from '@angular/router';
import { Home } from './components/home/home';
import { Catalogue } from './components/catalogue/catalogue';
import { LivreDetail } from './components/livre-detail/livre-detail';
import { Auteurs } from './components/auteurs/auteurs';
import { AuteurDetail } from './components/auteur-detail/auteur-detail';
import { Login } from './components/login/login';
import { MonCompte } from './components/mon-compte/mon-compte';
import { authGuard } from './guards/auth.guard';

export const routes: Routes = [
  { path: '', component: Home },
  { path: 'catalogue', component: Catalogue },
  { path: 'livres/:id', component: LivreDetail },
  { path: 'auteurs', component: Auteurs },
  { path: 'auteurs/:id', component: AuteurDetail },
  { path: 'login', component: Login },
  { path: 'mon-compte', component: MonCompte, canActivate: [authGuard] },
];