# Backlog & Sprints - Rizipisculture

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
- [ ] CRUD Exploitations (index, create, show, edit, update, delete)
- [ ] Politiques d'accès (un utilisateur ne voit que ses exploitations)
- [ ] Form Requests pour validation
- [ ] Vues Blade pour le CRUD
- [ ] Tests feature pour le CRUD exploitations
- [x] Factory et tests des relations/cascades du modèle

## Sprint 2 : Gestion des bassins / parcelles aquacoles (En cours)
- [x] Modèle `Bassin` (nom, type, superficie, capacité, exploitation_id)
- [x] Relation Exploitation -> Bassins
- [ ] CRUD Bassins par exploitation
- [ ] Gestion des types de bassins (étang, vivier, bac, etc.)
- [ ] Validation et règles métier
- [ ] Tests associés

## Sprint 3 : Espèces et cycles d'élevage (À faire)
- [ ] Modèle `Espece` (nom commun, nom scientifique, paramètres optimaux)
- [ ] Modèle `CycleElevage` (date début/fin, statut, effectif, bassin_id, espece_id)
- [ ] Gestion des cycles d'élevage (ouverture/fermeture)
- [ ] Suivi de l'effectif
- [ ] Historique par bassin/exploitation
- [ ] Tests

## Sprint 4 : Alimentation et intrants (En cours)
- [x] Modèle `Aliment` avec migration, factory et relation à `Exploitation`
- [ ] Modèle `DistributionAliment` (date, quantité, cycle_elevage_id, aliment_id)
- [ ] Suivi de la consommation
- [ ] Calculs de taux de conversion alimentaire (FCR)
- [ ] Gestion des stocks d'aliments
- [ ] Alertes stock faible
- [ ] Tests

## Sprint 5 : Qualité de l'eau et suivi sanitaire (À faire)
- [ ] Modèle `PrelevementEau` (pH, température, oxygène, ammoniac, nitrites, etc.)
- [ ] Modèle `Traitement` / `InterventionSanitaire`
- [ ] Graphiques de suivi qualité eau
- [ ] Alertes seuils critiques
- [ ] Carnet sanitaire
- [ ] Tests

## Sprint 6 : Récoltes, ventes et traçabilité (À faire)
- [ ] Modèle `Recolte` (date, quantité, poids moyen, cycle_elevage_id)
- [ ] Modèle `Vente` (client, quantité, prix unitaire, montant, date)
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
