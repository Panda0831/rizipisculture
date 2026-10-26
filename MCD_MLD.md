# Modélisation des données - Rizipisculture (Corrigée)

## 1. MCD (Modèle Conceptuel de Données)

### Entités principales

- **USER** (utilisateur) : comptes utilisateurs de l'application (géré par Laravel Breeze)
- **EXPLOITATION** : exploitation agricole aquacole
- **BASSIN** : bassin/étang/vivier appartenant à une exploitation
- **ESPECE** : espèces piscicoles élevées
- **CYCLE_ELEVAGE** : cycle d'élevage dans un bassin avec une espèce donnée
- **ALIMENT** : aliments/intrants utilisés
- **DISTRIBUTION_ALIMENT** : distribution d'aliment lors d'un cycle
- **MORTALITE** : suivis des mortalités par cycle d'élevage
- **PRELEVEMENT_EAU** : relevés de qualité d'eau (rattachés au bassin, optionnellement à un cycle)
- **TRAITEMENT** : interventions sanitaires/traitements (rattachés au bassin, optionnellement à un cycle)
- **RECOLTE** : récoltes réalisées sur un cycle
- **VENTE** : ventes de produits issus des récoltes

### Relations et cardinalités

| Relation | Entités | Cardinalités | Description |
|---|---|---|---|
| POSSEDER | USER - EXPLOITATION | (1,1) - (0,N) | Un utilisateur peut posséder plusieurs exploitations. Une exploitation appartient à un utilisateur. |
| COMPOSER | EXPLOITATION - BASSIN | (1,1) - (0,N) | Une exploitation contient plusieurs bassins. Un bassin appartient à une exploitation. |
| ELEVAGE | BASSIN - CYCLE_ELEVAGE | (1,1) - (0,N) | Un bassin peut avoir plusieurs cycles d'élevage dans le temps. **Au plus un cycle avec statut `en_cours` par bassin.** |
| CONCERNER | ESPECE - CYCLE_ELEVAGE | (1,1) - (0,N) | Une espèce peut être utilisée dans plusieurs cycles. Un cycle concerne une espèce. |
| NOURRIR | CYCLE_ELEVAGE - DISTRIBUTION_ALIMENT | (1,1) - (0,N) | Un cycle fait l'objet de plusieurs distributions. |
| UTILISER | ALIMENT - DISTRIBUTION_ALIMENT | (1,1) - (0,N) | Un aliment peut être utilisé dans plusieurs distributions. |
| DEPOSER | CYCLE_ELEVAGE - MORTALITE | (1,1) - (0,N) | Un cycle fait l'objet de plusieurs déclarations de mortalité. |
| SURVEILLER | BASSIN - PRELEVEMENT_EAU | (1,1) - (0,N) | Un bassin fait l'objet de plusieurs prélèvements de qualité d'eau. Un prélèvement est rattaché à un bassin, optionnellement à un cycle d'élevage. |
| RATTACHER_CYCLE | CYCLE_ELEVAGE - PRELEVEMENT_EAU | (0,1) - (0,N) | Lien facultatif entre prélèvement et cycle d'élevage actif. |
| SOIGNER_BASSIN | BASSIN - TRAITEMENT | (1,1) - (0,N) | Un bassin peut faire l'objet de plusieurs traitements. Un traitement est rattaché à un bassin, optionnellement à un cycle d'élevage. |
| RATTACHER_CYCLE_TRAIT | CYCLE_ELEVAGE - TRAITEMENT | (0,1) - (0,N) | Lien facultatif entre traitement et cycle d'élevage actif. |
| PRODUIRE | CYCLE_ELEVAGE - RECOLTE | (1,1) - (0,N) | Un cycle donne lieu à une ou plusieurs récoltes (partielles/totales). |
| VENDRE | RECOLTE - VENTE | (1,1) - (0,N) | Une récolte peut être vendue en plusieurs lots/ventes. |

## 2. MLD (Modèle Logique de Données)

### Tables et attributs

#### users (existante - Laravel Breeze)
```sql
users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

#### exploitations
```sql
exploitations (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  nom VARCHAR(255) NOT NULL,
  adresse TEXT NULL,
  superficie_ha DECIMAL(10,2) NULL,
  telephone VARCHAR(20) NULL,
  numero_siret VARCHAR(14) NULL,
  description TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)
```

#### bassins
```sql
bassins (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  exploitation_id BIGINT NOT NULL,
  nom VARCHAR(255) NOT NULL,
  type ENUM('etang','vivier','bac','autre') DEFAULT 'etang',
  superficie_m2 DECIMAL(10,2) NULL,
  profondeur_m DECIMAL(5,2) NULL,
  capacite_m3 DECIMAL(10,2) NULL,
  surface_eau_m2 DECIMAL(10,2) NULL,
  actif BOOLEAN DEFAULT TRUE,
  observations TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (exploitation_id) REFERENCES exploitations(id) ON DELETE CASCADE
)
```

#### especes
```sql
especes (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  nom_commun VARCHAR(255) NOT NULL,
  nom_scientifique VARCHAR(255) NULL,
  nom_local VARCHAR(255) NULL,
  temperature_min DECIMAL(4,1) NULL,
  temperature_max DECIMAL(4,1) NULL,
  ph_min DECIMAL(3,1) NULL,
  ph_max DECIMAL(3,1) NULL,
  description TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

#### cycle_elevages
```sql
cycle_elevages (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  bassin_id BIGINT NOT NULL,
  espece_id BIGINT NOT NULL,
  date_debut DATE NOT NULL,
  date_fin DATE NULL,
  statut ENUM('en_cours','termine','annule') DEFAULT 'en_cours',
  effectif_depart INT UNSIGNED NOT NULL,
  effectif_actuel INT UNSIGNED NULL,
  poids_moyen_depart_g DECIMAL(8,2) NULL,
  densite_alevins_m2 DECIMAL(8,2) NULL,
  notes TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (bassin_id) REFERENCES bassins(id) ON DELETE CASCADE,
  FOREIGN KEY (espece_id) REFERENCES especes(id) ON DELETE RESTRICT,
  -- Contrainte métier : au plus un cycle en_cours par bassin (à appliquer côté validation Laravel si non supporté par SQLite)
  INDEX idx_cycle_bassin_statut (bassin_id, statut)
)
```

#### aliments
```sql
aliments (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  exploitation_id BIGINT NULL,
  nom VARCHAR(255) NOT NULL,
  type VARCHAR(100) NULL,
  proteines_pourcent DECIMAL(5,2) NULL,
  prix_unitaire DECIMAL(10,2) NULL,
  unite VARCHAR(20) DEFAULT 'kg',
  stock_actuel DECIMAL(10,2) DEFAULT 0,
  stock_minimum DECIMAL(10,2) DEFAULT 0,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (exploitation_id) REFERENCES exploitations(id) ON DELETE CASSETTE
)
```

#### distribution_aliments
```sql
distribution_aliments (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  cycle_elevage_id BIGINT NOT NULL,
  aliment_id BIGINT NOT NULL,
  date_distribution DATE NOT NULL,
  quantite_kg DECIMAL(10,2) NOT NULL,
  prix_unitaire_kg DECIMAL(10,2) NOT NULL,
  montant_total DECIMAL(10,2) NOT NULL,
  heure TIME NULL,
  observations TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (cycle_elevage_id) REFERENCES cycle_elevages(id) ON DELETE CASCADE,
  FOREIGN KEY (aliment_id) REFERENCES aliments(id) ON DELETE RESTRICT
)
```

#### mortalites
```sql
mortalites (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  cycle_elevage_id BIGINT NOT NULL,
  date_deces DATE NOT NULL,
  nombre INT UNSIGNED NOT NULL,
  cause VARCHAR(255) NULL,
  observations TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (cycle_elevage_id) REFERENCES cycle_elevages(id) ON DELETE CASCADE
)
```

#### prelevement_eaux
```sql
prelevement_eaux (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  bassin_id BIGINT NOT NULL,
  cycle_elevage_id BIGINT NULL,
  date_prelevement DATETIME NOT NULL,
  temperature_c DECIMAL(4,1) NULL,
  ph DECIMAL(3,1) NULL,
  oxygene_mg_l DECIMAL(4,2) NULL,
  ammoniac_mg_l DECIMAL(4,2) NULL,
  nitrites_mg_l DECIMAL(4,2) NULL,
  nitrates_mg_l DECIMAL(4,2) NULL,
  transparence_cm INT NULL,
  observations TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (bassin_id) REFERENCES bassins(id) ON DELETE CASCADE,
  FOREIGN KEY (cycle_elevage_id) REFERENCES cycle_elevages(id) ON DELETE SET NULL
)
```

#### traitements
```sql
traitements (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  bassin_id BIGINT NOT NULL,
  cycle_elevage_id BIGINT NULL,
  date_traitement DATE NOT NULL,
  produit VARCHAR(255) NOT NULL,
  motif TEXT NOT NULL,
  dose VARCHAR(100) NULL,
  quantite_appliquee VARCHAR(100) NULL,
  duree_jours INT NULL,
  avec_arret_recolte BOOLEAN DEFAULT FALSE,
  responsable VARCHAR(255) NULL,
  observations TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (bassin_id) REFERENCES bassins(id) ON DELETE CASCADE,
  FOREIGN KEY (cycle_elevage_id) REFERENCES cycle_elevages(id) ON DELETE SET NULL
)
```

#### recoltes
```sql
recoltes (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  cycle_elevage_id BIGINT NOT NULL,
  date_recolte DATE NOT NULL,
  type ENUM('partielle','totale') DEFAULT 'partielle',
  quantite_kg DECIMAL(10,2) NOT NULL,
  nombre_poissons INT NULL,
  poids_moyen_g DECIMAL(8,2) NULL,
  prix_vente_unitaire_kg DECIMAL(10,2) NULL,
  observations TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (cycle_elevage_id) REFERENCES cycle_elevages(id) ON DELETE CASCADE
)
```

#### ventes
```sql
ventes (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  recolte_id BIGINT NOT NULL,
  date_vente DATE NOT NULL,
  client_nom VARCHAR(255) NOT NULL,
  client_contact VARCHAR(255) NULL,
  quantite_kg DECIMAL(10,2) NOT NULL,
  prix_unitaire_kg DECIMAL(10,2) NOT NULL,
  montant_total DECIMAL(10,2) NOT NULL,
  mode_paiement ENUM('especes','cheque','virement','autre') DEFAULT 'especes',
  statut ENUM('en_attente','payee','annulee') DEFAULT 'en_attente',
  numero_facture VARCHAR(50) NULL,
  notes TEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (recolte_id) REFERENCES recoltes(id) ON DELETE RESTRICT
)
```

## 3. Règles métier à respecter

- **Unicité cycle actif** : un bassin ne peut avoir qu'un seul `cycle_elevages` avec `statut = 'en_cours'`. À contrôler par validation Laravel.
- **Historisation des prix** : `distribution_aliments` conserve `prix_unitaire_kg` et `montant_total` au moment de la distribution.
- **Suivi flexible** : `prelevement_eaux` et `traitements` sont rattachés au `bassin` (obligatoire). Le rattachement au `cycle_elevage` est facultatif pour permettre le suivi hors cycle actif.
- **Suivi des mortalités** : toute mortalité doit être enregistrée dans `mortalites` pour un calcul fiable de l'`effectif_actuel`.
- **Gestion multi-exploitations** : `aliments` peut être lié à `exploitation_id` (NULL pour un référentiel global). À adapter selon les besoins.
- **Traçabilité ventes** : `ventes.montant_total` est historisé même si redondant avec `quantite_kg * prix_unitaire_kg`.
- **Intégrité** : utiliser `ON DELETE CASCADE` pour les données dépendantes, `ON DELETE RESTRICT`/`SET NULL` selon l'importance historique. 
