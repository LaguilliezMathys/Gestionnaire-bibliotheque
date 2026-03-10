import { Component, inject, OnInit, signal } from '@angular/core';
import { SlicePipe } from '@angular/common';
import { RouterLink } from '@angular/router';
import { ApiService } from '../../services/api-service';
import { Auteur } from '../../models/auteur';

@Component({
  selector: 'app-auteurs',
  imports: [RouterLink, SlicePipe],
  templateUrl: './auteurs.html',
  styleUrl: './auteurs.css',
})
export class Auteurs implements OnInit {
  private apiService = inject(ApiService);

  auteurs = signal<Auteur[]>([]);

  ngOnInit() {
    this.apiService.getAuteurs().subscribe(data => this.auteurs.set(data));
  }
}
