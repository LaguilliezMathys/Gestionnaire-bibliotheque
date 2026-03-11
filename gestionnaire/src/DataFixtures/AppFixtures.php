<?php

namespace App\DataFixtures;

use App\Entity\Adherent;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // ========== UTILISATEURS (back-office) ==========
        $admin = new Utilisateur();
        $admin->setEmail('admin@biblio.fr');
        $admin->setNom('Martin');
        $admin->setPrenom('Sophie');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $biblio = new Utilisateur();
        $biblio->setEmail('biblio@biblio.fr');
        $biblio->setNom('Dupont');
        $biblio->setPrenom('Jean');
        $biblio->setRoles(['ROLE_BIBLIO']);
        $biblio->setPassword($this->passwordHasher->hashPassword($biblio, 'biblio'));
        $manager->persist($biblio);

        // ========== CATEGORIES ==========
        $catRoman = new Categorie();
        $catRoman->setNom('Roman');
        $catRoman->setDescription('Œuvres de fiction narrative en prose');
        $manager->persist($catRoman);

        $catSF = new Categorie();
        $catSF->setNom('Science-Fiction');
        $catSF->setDescription('Romans explorant des mondes futuristes et technologies imaginaires');
        $manager->persist($catSF);

        $catPolicier = new Categorie();
        $catPolicier->setNom('Policier');
        $catPolicier->setDescription('Romans d\'enquêtes criminelles et de suspense');
        $manager->persist($catPolicier);

        $catHistoire = new Categorie();
        $catHistoire->setNom('Histoire');
        $catHistoire->setDescription('Romans historiques et récits du passé');
        $manager->persist($catHistoire);

        $catFantasy = new Categorie();
        $catFantasy->setNom('Fantasy');
        $catFantasy->setDescription('Mondes imaginaires, magie et créatures fantastiques');
        $manager->persist($catFantasy);

        // ========== AUTEURS ==========
        $auteurs = [];

        $a1 = new Auteur();
        $a1->setNom('Hugo');
        $a1->setPrenom('Victor');
        $a1->setDescription('Écrivain, poète et dramaturge français du XIXe siècle.');
        $a1->setDateNaissance(new \DateTime('1802-02-26'));
        $a1->setDateDeces(new \DateTime('1885-05-22'));
        $a1->setNationalite('Française');
        $manager->persist($a1);
        $auteurs[] = $a1;

        $a2 = new Auteur();
        $a2->setNom('Zola');
        $a2->setPrenom('Émile');
        $a2->setDescription('Écrivain et journaliste français, chef de file du naturalisme.');
        $a2->setDateNaissance(new \DateTime('1840-04-02'));
        $a2->setDateDeces(new \DateTime('1902-09-29'));
        $a2->setNationalite('Française');
        $manager->persist($a2);
        $auteurs[] = $a2;

        $a3 = new Auteur();
        $a3->setNom('Asimov');
        $a3->setPrenom('Isaac');
        $a3->setDescription('Écrivain américain d\'origine russe, auteur majeur de science-fiction.');
        $a3->setDateNaissance(new \DateTime('1920-01-02'));
        $a3->setDateDeces(new \DateTime('1992-04-06'));
        $a3->setNationalite('Américaine');
        $manager->persist($a3);
        $auteurs[] = $a3;

        $a4 = new Auteur();
        $a4->setNom('Christie');
        $a4->setPrenom('Agatha');
        $a4->setDescription('Romancière britannique, reine du roman policier.');
        $a4->setDateNaissance(new \DateTime('1890-09-15'));
        $a4->setDateDeces(new \DateTime('1976-01-12'));
        $a4->setNationalite('Britannique');
        $manager->persist($a4);
        $auteurs[] = $a4;

        $a5 = new Auteur();
        $a5->setNom('Tolkien');
        $a5->setPrenom('J.R.R.');
        $a5->setDescription('Écrivain britannique, auteur du Seigneur des Anneaux.');
        $a5->setDateNaissance(new \DateTime('1892-01-03'));
        $a5->setDateDeces(new \DateTime('1973-09-02'));
        $a5->setNationalite('Britannique');
        $manager->persist($a5);
        $auteurs[] = $a5;

        $a6 = new Auteur();
        $a6->setNom('Camus');
        $a6->setPrenom('Albert');
        $a6->setDescription('Écrivain, philosophe et journaliste français. Prix Nobel de littérature 1957.');
        $a6->setDateNaissance(new \DateTime('1913-11-07'));
        $a6->setDateDeces(new \DateTime('1960-01-04'));
        $a6->setNationalite('Française');
        $manager->persist($a6);
        $auteurs[] = $a6;

        $a7 = new Auteur();
        $a7->setNom('Dumas');
        $a7->setPrenom('Alexandre');
        $a7->setDescription('Écrivain français, auteur de romans historiques populaires.');
        $a7->setDateNaissance(new \DateTime('1802-07-24'));
        $a7->setDateDeces(new \DateTime('1870-12-05'));
        $a7->setNationalite('Française');
        $manager->persist($a7);
        $auteurs[] = $a7;

        // ========== LIVRES ==========
        $livres = [];

        $l1 = new Livre();
        $l1->setTitre('Les Misérables');
        $l1->setIsbn('9782070409228');
        $l1->setResume('L\'histoire de Jean Valjean, ancien forçat, dans la France du XIXe siècle.');
        $l1->setLangue('Français');
        $l1->setDateSortie(new \DateTime('1862-04-03'));
        $l1->setDisponible(true);
        $l1->setCategorie($catRoman);
        $l1->addAuteur($a1);
        $manager->persist($l1);
        $livres[] = $l1;

        $l2 = new Livre();
        $l2->setTitre('Notre-Dame de Paris');
        $l2->setIsbn('9782070411429');
        $l2->setResume('L\'histoire de Quasimodo et Esmeralda dans le Paris médiéval.');
        $l2->setLangue('Français');
        $l2->setDateSortie(new \DateTime('1831-03-16'));
        $l2->setDisponible(true);
        $l2->setCategorie($catRoman);
        $l2->addAuteur($a1);
        $manager->persist($l2);
        $livres[] = $l2;

        $l3 = new Livre();
        $l3->setTitre('Germinal');
        $l3->setIsbn('9782070360024');
        $l3->setResume('La vie des mineurs du nord de la France au XIXe siècle.');
        $l3->setLangue('Français');
        $l3->setDateSortie(new \DateTime('1885-03-01'));
        $l3->setDisponible(true);
        $l3->setCategorie($catRoman);
        $l3->addAuteur($a2);
        $manager->persist($l3);
        $livres[] = $l3;

        $l4 = new Livre();
        $l4->setTitre('L\'Assommoir');
        $l4->setIsbn('9782070409914');
        $l4->setResume('L\'histoire de Gervaise Macquart dans le Paris ouvrier.');
        $l4->setLangue('Français');
        $l4->setDateSortie(new \DateTime('1877-01-01'));
        $l4->setDisponible(true);
        $l4->setCategorie($catRoman);
        $l4->addAuteur($a2);
        $manager->persist($l4);
        $livres[] = $l4;

        $l5 = new Livre();
        $l5->setTitre('Fondation');
        $l5->setIsbn('9782070360536');
        $l5->setResume('Premier tome du Cycle de Fondation, l\'effondrement d\'un empire galactique.');
        $l5->setLangue('Français');
        $l5->setDateSortie(new \DateTime('1951-05-01'));
        $l5->setDisponible(true);
        $l5->setCategorie($catSF);
        $l5->addAuteur($a3);
        $manager->persist($l5);
        $livres[] = $l5;

        $l6 = new Livre();
        $l6->setTitre('I, Robot');
        $l6->setIsbn('9780553294385');
        $l6->setResume('Recueil de nouvelles sur les robots et les lois de la robotique.');
        $l6->setLangue('Anglais');
        $l6->setDateSortie(new \DateTime('1950-12-02'));
        $l6->setDisponible(true);
        $l6->setCategorie($catSF);
        $l6->addAuteur($a3);
        $manager->persist($l6);
        $livres[] = $l6;

        $l7 = new Livre();
        $l7->setTitre('Dix petits nègres');
        $l7->setIsbn('9782253010050');
        $l7->setResume('Dix personnes sont invitées sur une île par un mystérieux inconnu.');
        $l7->setLangue('Français');
        $l7->setDateSortie(new \DateTime('1939-11-06'));
        $l7->setDisponible(true);
        $l7->setCategorie($catPolicier);
        $l7->addAuteur($a4);
        $manager->persist($l7);
        $livres[] = $l7;

        $l8 = new Livre();
        $l8->setTitre('Le Crime de l\'Orient-Express');
        $l8->setIsbn('9782253004721');
        $l8->setResume('Un meurtre à bord du célèbre train, enquête d\'Hercule Poirot.');
        $l8->setLangue('Français');
        $l8->setDateSortie(new \DateTime('1934-01-01'));
        $l8->setDisponible(true);
        $l8->setCategorie($catPolicier);
        $l8->addAuteur($a4);
        $manager->persist($l8);
        $livres[] = $l8;

        $l9 = new Livre();
        $l9->setTitre('Le Seigneur des Anneaux');
        $l9->setIsbn('9782267021998');
        $l9->setResume('La quête de Frodon pour détruire l\'Anneau Unique.');
        $l9->setLangue('Français');
        $l9->setDateSortie(new \DateTime('1954-07-29'));
        $l9->setDisponible(true);
        $l9->setCategorie($catFantasy);
        $l9->addAuteur($a5);
        $manager->persist($l9);
        $livres[] = $l9;

        $l10 = new Livre();
        $l10->setTitre('Le Hobbit');
        $l10->setIsbn('9782267028034');
        $l10->setResume('L\'aventure de Bilbo Baggins avec les nains et le dragon Smaug.');
        $l10->setLangue('Français');
        $l10->setDateSortie(new \DateTime('1937-09-21'));
        $l10->setDisponible(true);
        $l10->setCategorie($catFantasy);
        $l10->addAuteur($a5);
        $manager->persist($l10);
        $livres[] = $l10;

        $l11 = new Livre();
        $l11->setTitre('L\'Étranger');
        $l11->setIsbn('9782070360048');
        $l11->setResume('Meursault, un homme indifférent au monde qui l\'entoure.');
        $l11->setLangue('Français');
        $l11->setDateSortie(new \DateTime('1942-06-15'));
        $l11->setDisponible(true);
        $l11->setCategorie($catRoman);
        $l11->addAuteur($a6);
        $manager->persist($l11);
        $livres[] = $l11;

        $l12 = new Livre();
        $l12->setTitre('La Peste');
        $l12->setIsbn('9782070360420');
        $l12->setResume('Une épidémie de peste à Oran, Algérie.');
        $l12->setLangue('Français');
        $l12->setDateSortie(new \DateTime('1947-06-10'));
        $l12->setDisponible(true);
        $l12->setCategorie($catRoman);
        $l12->addAuteur($a6);
        $manager->persist($l12);
        $livres[] = $l12;

        $l13 = new Livre();
        $l13->setTitre('Les Trois Mousquetaires');
        $l13->setIsbn('9782070409322');
        $l13->setResume('Les aventures de d\'Artagnan et ses compagnons.');
        $l13->setLangue('Français');
        $l13->setDateSortie(new \DateTime('1844-03-14'));
        $l13->setDisponible(true);
        $l13->setCategorie($catHistoire);
        $l13->addAuteur($a7);
        $manager->persist($l13);
        $livres[] = $l13;

        $l14 = new Livre();
        $l14->setTitre('Le Comte de Monte-Cristo');
        $l14->setIsbn('9782070405312');
        $l14->setResume('L\'histoire d\'Edmond Dantès, injustement emprisonné, et sa vengeance.');
        $l14->setLangue('Français');
        $l14->setDateSortie(new \DateTime('1844-08-28'));
        $l14->setDisponible(true);
        $l14->setCategorie($catHistoire);
        $l14->addAuteur($a7);
        $manager->persist($l14);
        $livres[] = $l14;

        $l15 = new Livre();
        $l15->setTitre('Foundation and Empire');
        $l15->setIsbn('9780553293371');
        $l15->setResume('Second tome du Cycle de Fondation.');
        $l15->setLangue('Anglais');
        $l15->setDateSortie(new \DateTime('1952-06-01'));
        $l15->setDisponible(true);
        $l15->setCategorie($catSF);
        $l15->addAuteur($a3);
        $manager->persist($l15);
        $livres[] = $l15;

        $l16 = new Livre();
        $l16->setTitre('Murder on the Nile');
        $l16->setIsbn('9780062073556');
        $l16->setResume('A Hercule Poirot mystery set on a Nile cruise.');
        $l16->setLangue('Anglais');
        $l16->setDateSortie(new \DateTime('1937-11-01'));
        $l16->setDisponible(true);
        $l16->setCategorie($catPolicier);
        $l16->addAuteur($a4);
        $manager->persist($l16);
        $livres[] = $l16;

        $l17 = new Livre();
        $l17->setTitre('Le Silmarillion');
        $l17->setIsbn('9782267013078');
        $l17->setResume('Recueil de mythes et légendes de la Terre du Milieu.');
        $l17->setLangue('Français');
        $l17->setDateSortie(new \DateTime('1977-09-15'));
        $l17->setDisponible(true);
        $l17->setCategorie($catFantasy);
        $l17->addAuteur($a5);
        $manager->persist($l17);
        $livres[] = $l17;

        $l18 = new Livre();
        $l18->setTitre('Nana');
        $l18->setIsbn('9782070414000');
        $l18->setResume('L\'ascension et la chute d\'une courtisane parisienne.');
        $l18->setLangue('Français');
        $l18->setDateSortie(new \DateTime('1880-02-01'));
        $l18->setDisponible(true);
        $l18->setCategorie($catRoman);
        $l18->addAuteur($a2);
        $manager->persist($l18);
        $livres[] = $l18;

        $l19 = new Livre();
        $l19->setTitre('La Chute');
        $l19->setIsbn('9782070360659');
        $l19->setResume('Un monologue de Jean-Baptiste Clamence dans un bar d\'Amsterdam.');
        $l19->setLangue('Français');
        $l19->setDateSortie(new \DateTime('1956-05-01'));
        $l19->setDisponible(true);
        $l19->setCategorie($catRoman);
        $l19->addAuteur($a6);
        $manager->persist($l19);
        $livres[] = $l19;

        $l20 = new Livre();
        $l20->setTitre('Vingt Ans Après');
        $l20->setIsbn('9782070411818');
        $l20->setResume('Suite des Trois Mousquetaires, vingt ans plus tard.');
        $l20->setLangue('Français');
        $l20->setDateSortie(new \DateTime('1845-01-21'));
        $l20->setDisponible(true);
        $l20->setCategorie($catHistoire);
        $l20->addAuteur($a7);
        $manager->persist($l20);
        $livres[] = $l20;

        // ========== ADHERENTS ==========
        $adherents = [];

        $adherentData = [
            ['email' => 'pierre.durand@email.fr', 'nom' => 'Durand', 'prenom' => 'Pierre', 'numTel' => '0601020304', 'adressePostale' => '12 rue des Lilas, 31000 Toulouse', 'dateNaissance' => '1990-05-15'],
            ['email' => 'marie.bernard@email.fr', 'nom' => 'Bernard', 'prenom' => 'Marie', 'numTel' => '0605060708', 'adressePostale' => '5 avenue Jean Jaurès, 31500 Toulouse', 'dateNaissance' => '1985-08-22'],
            ['email' => 'luc.petit@email.fr', 'nom' => 'Petit', 'prenom' => 'Luc', 'numTel' => '0611223344', 'adressePostale' => '8 place du Capitole, 31000 Toulouse', 'dateNaissance' => '1995-02-10'],
            ['email' => 'emma.moreau@email.fr', 'nom' => 'Moreau', 'prenom' => 'Emma', 'numTel' => '0655667788', 'adressePostale' => '22 rue Alsace-Lorraine, 31000 Toulouse', 'dateNaissance' => '1992-11-03'],
            ['email' => 'julien.garcia@email.fr', 'nom' => 'Garcia', 'prenom' => 'Julien', 'numTel' => '0699887766', 'adressePostale' => '3 allée des Demoiselles, 31400 Toulouse', 'dateNaissance' => '1988-07-28'],
            ['email' => 'claire.roux@email.fr', 'nom' => 'Roux', 'prenom' => 'Claire', 'numTel' => '0633445566', 'adressePostale' => '15 rue de Metz, 31000 Toulouse', 'dateNaissance' => '1993-04-17'],
            ['email' => 'thomas.leroy@email.fr', 'nom' => 'Leroy', 'prenom' => 'Thomas', 'numTel' => '0677889900', 'adressePostale' => '7 boulevard de Strasbourg, 31000 Toulouse', 'dateNaissance' => '1991-01-30'],
            ['email' => 'sarah.simon@email.fr', 'nom' => 'Simon', 'prenom' => 'Sarah', 'numTel' => '0612345678', 'adressePostale' => '45 route de Blagnac, 31700 Blagnac', 'dateNaissance' => '1997-09-12'],
            ['email' => 'nicolas.laurent@email.fr', 'nom' => 'Laurent', 'prenom' => 'Nicolas', 'numTel' => '0698765432', 'adressePostale' => '11 chemin de Lapujade, 31200 Toulouse', 'dateNaissance' => '1986-12-05'],
            ['email' => 'camille.michel@email.fr', 'nom' => 'Michel', 'prenom' => 'Camille', 'numTel' => '0654321098', 'adressePostale' => '28 rue du Taur, 31000 Toulouse', 'dateNaissance' => '1994-06-21'],
        ];

        foreach ($adherentData as $i => $data) {
            $adherent = new Adherent();
            $adherent->setEmail($data['email']);
            $adherent->setNom($data['nom']);
            $adherent->setPrenom($data['prenom']);
            $adherent->setNumTel($data['numTel']);
            $adherent->setAdressePostale($data['adressePostale']);
            $adherent->setDateNaissance(new \DateTime($data['dateNaissance']));
            $adherent->setActif(true);
            $adherent->setDateAdhesion(new \DateTime('-' . ($i * 30 + 10) . ' days'));
            $adherent->setPassword($this->passwordHasher->hashPassword($adherent, 'adherent'));
            $manager->persist($adherent);
            $adherents[] = $adherent;
        }

        // Suspendre un adhérent pour tester
        $adherents[9]->setActif(false);

        $manager->flush();

        // ========== EMPRUNTS ==========
        // Emprunts en cours (non rendus)
        $e1 = new Emprunt();
        $e1->setAdherent($adherents[0]);
        $e1->setLivre($l1);
        $e1->setDateEmprunt(new \DateTime('-5 days'));
        $e1->setDateRetourPrevue(new \DateTime('+10 days'));
        $l1->setDisponible(false);
        $manager->persist($e1);

        $e2 = new Emprunt();
        $e2->setAdherent($adherents[0]);
        $e2->setLivre($l5);
        $e2->setDateEmprunt(new \DateTime('-3 days'));
        $e2->setDateRetourPrevue(new \DateTime('+12 days'));
        $l5->setDisponible(false);
        $manager->persist($e2);

        $e3 = new Emprunt();
        $e3->setAdherent($adherents[1]);
        $e3->setLivre($l7);
        $e3->setDateEmprunt(new \DateTime('-10 days'));
        $e3->setDateRetourPrevue(new \DateTime('+5 days'));
        $l7->setDisponible(false);
        $manager->persist($e3);

        $e4 = new Emprunt();
        $e4->setAdherent($adherents[2]);
        $e4->setLivre($l9);
        $e4->setDateEmprunt(new \DateTime('-12 days'));
        $e4->setDateRetourPrevue(new \DateTime('+3 days'));
        $l9->setDisponible(false);
        $manager->persist($e4);

        // Emprunt en retard
        $e5 = new Emprunt();
        $e5->setAdherent($adherents[3]);
        $e5->setLivre($l13);
        $e5->setDateEmprunt(new \DateTime('-20 days'));
        $e5->setDateRetourPrevue(new \DateTime('-5 days'));
        $l13->setDisponible(false);
        $manager->persist($e5);

        $e6 = new Emprunt();
        $e6->setAdherent($adherents[4]);
        $e6->setLivre($l3);
        $e6->setDateEmprunt(new \DateTime('-18 days'));
        $e6->setDateRetourPrevue(new \DateTime('-3 days'));
        $l3->setDisponible(false);
        $manager->persist($e6);

        // Emprunts terminés (rendus)
        $e7 = new Emprunt();
        $e7->setAdherent($adherents[0]);
        $e7->setLivre($l2);
        $e7->setDateEmprunt(new \DateTime('-30 days'));
        $e7->setDateRetourPrevue(new \DateTime('-15 days'));
        $e7->setDateRetourEffective(new \DateTime('-16 days'));
        $manager->persist($e7);

        $e8 = new Emprunt();
        $e8->setAdherent($adherents[1]);
        $e8->setLivre($l4);
        $e8->setDateEmprunt(new \DateTime('-40 days'));
        $e8->setDateRetourPrevue(new \DateTime('-25 days'));
        $e8->setDateRetourEffective(new \DateTime('-26 days'));
        $manager->persist($e8);

        $e9 = new Emprunt();
        $e9->setAdherent($adherents[2]);
        $e9->setLivre($l6);
        $e9->setDateEmprunt(new \DateTime('-35 days'));
        $e9->setDateRetourPrevue(new \DateTime('-20 days'));
        $e9->setDateRetourEffective(new \DateTime('-22 days'));
        $manager->persist($e9);

        $e10 = new Emprunt();
        $e10->setAdherent($adherents[5]);
        $e10->setLivre($l8);
        $e10->setDateEmprunt(new \DateTime('-25 days'));
        $e10->setDateRetourPrevue(new \DateTime('-10 days'));
        $e10->setDateRetourEffective(new \DateTime('-11 days'));
        $manager->persist($e10);

        $e11 = new Emprunt();
        $e11->setAdherent($adherents[3]);
        $e11->setLivre($l10);
        $e11->setDateEmprunt(new \DateTime('-45 days'));
        $e11->setDateRetourPrevue(new \DateTime('-30 days'));
        $e11->setDateRetourEffective(new \DateTime('-31 days'));
        $manager->persist($e11);

        $e12 = new Emprunt();
        $e12->setAdherent($adherents[6]);
        $e12->setLivre($l11);
        $e12->setDateEmprunt(new \DateTime('-50 days'));
        $e12->setDateRetourPrevue(new \DateTime('-35 days'));
        $e12->setDateRetourEffective(new \DateTime('-36 days'));
        $manager->persist($e12);

        $e13 = new Emprunt();
        $e13->setAdherent($adherents[7]);
        $e13->setLivre($l12);
        $e13->setDateEmprunt(new \DateTime('-28 days'));
        $e13->setDateRetourPrevue(new \DateTime('-13 days'));
        $e13->setDateRetourEffective(new \DateTime('-14 days'));
        $manager->persist($e13);

        $e14 = new Emprunt();
        $e14->setAdherent($adherents[4]);
        $e14->setLivre($l14);
        $e14->setDateEmprunt(new \DateTime('-60 days'));
        $e14->setDateRetourPrevue(new \DateTime('-45 days'));
        $e14->setDateRetourEffective(new \DateTime('-44 days'));
        $manager->persist($e14);

        $e15 = new Emprunt();
        $e15->setAdherent($adherents[8]);
        $e15->setLivre($l15);
        $e15->setDateEmprunt(new \DateTime('-55 days'));
        $e15->setDateRetourPrevue(new \DateTime('-40 days'));
        $e15->setDateRetourEffective(new \DateTime('-42 days'));
        $manager->persist($e15);

        // ========== RESERVATIONS ==========
        $r1 = new Reservation();
        $r1->setAdherent($adherents[5]);
        $r1->setLivre($l1); // livre emprunté par adherent[0]
        $r1->setDateReservation(new \DateTime('-2 days'));
        $manager->persist($r1);

        $r2 = new Reservation();
        $r2->setAdherent($adherents[6]);
        $r2->setLivre($l7); // livre emprunté par adherent[1]
        $r2->setDateReservation(new \DateTime('-1 day'));
        $manager->persist($r2);

        $manager->flush();
    }
}
