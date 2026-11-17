# Rizipisculture — Plateforme de gestion d'exploitation

Application web **Laravel 13** de gestion d'une exploitation rizipisculture (volet
aquacole) : bassins, espèces, cycles d'élevage, alimentation, mortalité, qualité de
l'eau, récoltes et ventes.

Projet individuel universitaire mené selon une démarche **Scrum** — backlog et plan
de sprints dans [`../BACKLOG.md`](../BACKLOG.md) et [`todo.md`](todo.md).

## État d'avancement

| Partie | Contenu | État |
|---|---|---|
| Sprint 0 — Initialisation | Laravel 13.38, Breeze (auth), architecture, SQLite, Git | ✅ Terminé |
| Modèle de données | 12 modèles Eloquent, 11 tables métier, migrations, factories, tests | ✅ Terminé |
| CRUD Exploitations / Bassins | Contrôleurs, Form Requests, Policies, vues Blade | 🚧 En cours |
| Espèces & cycles, alimentation, eau, récoltes, ventes | Interfaces utilisateur, règles métier, services | ⬜ À venir |
| Dashboard, API REST, tests de sécurité, rapport | Finitions (Sprint 5 du backlog) | ⬜ À venir |

## Modèle de données

12 modèles Eloquent reliés entre eux (MCD / MLD complets dans [`MCD_MLD.md`](MCD_MLD.md),
spécification dans [`modele_impl.md`](modele_impl.md)) :

| Modèle | Table | Rôle |
|---|---|---|
| `User` | `users` | Compte utilisateur (Breeze), possède des exploitations |
| `Exploitation` | `exploitations` | Exploitation appartenant à un utilisateur |
| `Bassin` | `bassins` | Bassin / étang / vivier d'une exploitation |
| `Espece` | `especes` | Référentiel des espèces (paramètres optimaux pH / température) |
| `CycleElevage` | `cycle_elevages` | Cycle d'élevage (bassin × espèce), statut `en_cours` / `termine` / `annule` |
| `Aliment` | `aliments` | Aliments / intrants avec stock actuel et seuil minimum |
| `DistributionAliment` | `distribution_aliments` | Distribution d'aliment (prix et montant historisés) |
| `Mortalite` | `mortalites` | Déclarations de mortalité par cycle |
| `PrelevementEau` | `prelevement_eaux` | Relevés qualité d'eau (pH, O₂, NH₃, NO₂⁻, NO₃⁻, température…) |
| `Traitement` | `traitements` | Interventions sanitaires par bassin |
| `Recolte` | `recoltes` | Récoltes partielles / totales d'un cycle |
| `Vente` | `ventes` | Ventes issues des récoltes (facturation, mode de paiement) |

Règles métier structurantes :
- au plus **un cycle en cours par bassin** — garanti en base par un index unique partiel (validation Form Request à venir) ;
- conservation de l'**historique des prix** (`distribution_aliments`, `ventes`) ;
- prélèvements et traitements rattachés au **bassin**, avec lien facultatif au cycle.

## Pile technique

| Élément | Choix |
|---|---|
| Framework | Laravel 13.38 (PHP 8.5) |
| Authentification | Laravel Breeze 2.4 (Blade + Tailwind) |
| Base de données | SQLite (`database/database.sqlite`) |
| Tests | PHPUnit 12 (`php artisan test`) |

## Installation

Prérequis : PHP ≥ 8.3 avec les extensions usuelles de Laravel, Composer, Node.js + npm.

```bash
git clone <dépôt> rizipisculture
cd rizipisculture
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
```

## Lancement

```bash
php artisan dev     # ou : php artisan serve
```

Compte de démonstration créé par le seeder (`php artisan db:seed`) :

| Email | Mot de passe |
|---|---|
| `test@example.com` | `password` |

## Tests

```bash
php artisan test
```

37 tests verts (authentification, profil, modèles et relations, cascades).

## Structure du projet

```
app/Models/                 12 modèles Eloquent (relations, casts)
app/Http/Controllers/       Auth Breeze + Profile (CRUD métier à venir)
database/migrations/        Schéma complet (11 tables métier)
database/factories/         Factories de tous les modèles métier
tests/Feature/              Tests fonctionnels (auth, modèles, relations)
MCD_MLD.md                  Modèle conceptuel et logique de données
modele_impl.md              Spécification des modèles Eloquent
todo.md                     Suivi des sprints
```

## Feuille de route

Prochaines étapes (voir [`../BACKLOG.md`](../BACKLOG.md)) :
1. CRUD Exploitations / Bassins avec Form Requests, Policies d'isolation et vues Blade ;
2. Gestion des espèces et des cycles d'élevage (règle « un seul cycle en cours par bassin ») ;
3. Alimentation : distributions avec transaction, décrément de stock et refus du stock négatif ;
4. Module eau : alertes sur seuils critiques (O₂ < 3 mg/L), historique graphique ;
5. Récoltes, ventes, indicateurs de rentabilité et dashboard.
