# Backlog & Sprints - Rizipisculture

> État au 17/11/2026 : modèle de données complet (12 modèles Eloquent, 11 tables métier,
> migrations, factories, 37 tests verts). Le volet pisciculture est couvert au niveau
> données ; le volet riz (parcelles, campagnes riz, volume d'eau alloué) reste planifié —
> voir `../BACKLOG.md`.

## Sprint 0 : Initialisation (Terminé) ✅
- [x] Initialisation du projet Laravel 13
- [x] Configuration SQLite
- [x] Installation et scaffolding Laravel Breeze (Blade + auth)
- [x] Mise en place de l'architecture (app/Services, app/Policies, app/Events, app/Listeners, app/Jobs, app/Http/Requests, app/Http/Resources)
- [x] Initialisation Git
- [x] Tests d'authentification, profil et modèles verts (27 tests)
- [x] Structure de base prête pour le développement

## Sprint 1 : Gestion des exploitations (En cours)
- [x] Modèle `Exploitation` (nom, adresse, superficie, propriétaire, etc.)
- [x] Migration et relation avec `User`
- [x] Factory et tests des relations/cascades du modèle
- [ ] CRUD Exploitations (index, create, show, edit, update, delete)
- [ ] Politiques d'accès (un utilisateur ne voit que ses exploitations)
- [ ] Form Requests pour validation
- [ ] Vues Blade pour le CRUD
- [ ] Tests feature pour le CRUD exploitations

## Sprint 2 : Gestion des bassins / parcelles aquacoles (En cours)
- [x] Modèle `Bassin` (nom, type, superficie, capacité, exploitation_id)
- [x] Relation Exploitation -> Bassins
- [x] Migration et factory de `Bassin`
- [ ] CRUD Bassins par exploitation
- [ ] Gestion des types de bassins (étang, vivier, bac, etc.)
- [ ] Validation et règles métier
- [ ] Tests associés

## Sprint 3 : Espèces et cycles d'élevage (En cours — modèle fait)
- [x] Modèle `Espece` (nom commun, nom scientifique, paramètres optimaux)
- [x] Modèle `CycleElevage` (date début/fin, statut, effectif, bassin_id, espece_id)
- [x] Migrations et factories des deux modèles
- [x] Relations `Bassin.cycleElevages()`, `Bassin.cycleEnCours()`, `Espece.cycleElevages()`
- [ ] CRUD Espèces et cycles d'élevage
- [ ] Gestion des cycles d'élevage (ouverture/fermeture)
- [x] Règle métier : au plus un cycle `en_cours` par bassin (index unique partiel en BDD, testé)
- [ ] Validation Form Request de l'unicité du cycle actif (message utilisateur propre)
- [ ] Suivi de l'effectif (depuis mortalités et récoltes)
- [ ] Historique par bassin/exploitation
- [ ] Tests

## Sprint 4 : Alimentation et intrants (En cours — modèle fait)
- [x] Modèle `Aliment` avec migration, factory et relation à `Exploitation`
- [x] Modèle `DistributionAliment` avec migration et factory (prix et montant historisés)
- [ ] CRUD Aliments et distributions
- [ ] Suivi de la consommation
- [ ] Calculs de taux de conversion alimentaire (FCR)
- [ ] Gestion des stocks d'aliments (StockService, transaction, refus du stock négatif)
- [ ] Alertes stock faible
- [ ] Tests

## Sprint 5 : Qualité de l'eau et suivi sanitaire (En cours — modèle fait)
- [x] Modèle `PrelevementEau` (pH, température, oxygène, ammoniac, nitrites, nitrates, transparence)
- [x] Modèle `Traitement` (interventions sanitaires, rattachées au bassin)
- [x] Migrations et factories des deux modèles
- [ ] Saisie des prélèvements et traitements (CRUD)
- [ ] Graphiques de suivi qualité eau
- [ ] Alertes seuils critiques (ex. oxygène < 3 mg/L)
- [ ] Carnet sanitaire
- [ ] Tests

## Sprint 6 : Récoltes, ventes et traçabilité (En cours — modèle fait)
- [x] Modèle `Recolte` (date, type, quantité, poids moyen, cycle_elevage_id)
- [x] Modèle `Vente` (client, quantité, prix unitaire, montant, mode de paiement, statut)
- [x] Migrations et factories des deux modèles
- [ ] CRUD Récoltes et ventes
- [ ] Calcul de rentabilité par cycle
- [ ] Traçabilité (de l'alevin à la vente)
- [ ] Facturation basique
- [ ] Rapports et tableaux de bord
- [ ] Tests

## Sprint 7 : Tableau de bord et rapports (À faire)
- [ ] Dashboard avec KPIs par exploitation
- [ ] Statistiques globales
- [ ] Graphiques (Chart.js ou similaire)
- [ ] Export PDF/CSV
- [ ] Notifications et rappels
- [ ] Optimisations et tests finaux

## Livrables transverses (en continu)
- [x] MCD / MLD à jour (`MCD_MLD.md`) et spécification des modèles (`modele_impl.md`)
- [x] README du projet (installation, lancement, tests, compte de démo)
- [ ] Seeders : jeu de données rizipisculture crédible et reconstructible en 1 commande
- [ ] API REST (consultation + création, isolation par exploitation)
- [ ] Rapport technique (15–25 p.)
