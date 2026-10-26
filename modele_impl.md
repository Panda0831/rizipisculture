# Implémentation des modèles Eloquent - Rizipisculture

## Liste des modèles à créer

| Modèle | Fichier | Table | Description |
|---|---|---|---|
| `User` | `app/Models/User.php` | `users` | Existant (Breeze). À enrichir avec relation `exploitations()`. |
| `Exploitation` | `app/Models/Exploitation.php` | `exploitations` | Exploitation aquacole appartenant à un utilisateur. |
| `Bassin` | `app/Models/Bassin.php` | `bassins` | Bassin/étang/vivier rattaché à une exploitation. |
| `Espece` | `app/Models/Espece.php` | `especes` | Référentiel des espèces piscicoles. |
| `CycleElevage` | `app/Models/CycleElevage.php` | `cycle_elevages` | Cycle d'élevage actif ou historique. |
| `Aliment` | `app/Models/Aliment.php` | `aliments` | Aliments/intrants avec gestion de stock. |
| `DistributionAliment` | `app/Models/DistributionAliment.php` | `distribution_aliments` | Distribution d'aliment avec historique de prix. |
| `Mortalite` | `app/Models/Mortalite.php` | `mortalites` | Suivi des mortalités par cycle. |
| `PrelevementEau` | `app/Models/PrelevementEau.php` | `prelevement_eaux` | Relevés qualité eau (rattachés au bassin). |
| `Traitement` | `app/Models/Traitement.php` | `traitements` | Interventions sanitaires (rattachées au bassin). |
| `Recolte` | `app/Models/Recolte.php` | `recoltes` | Récoltes sur un cycle d'élevage. |
| `Vente` | `app/Models/Vente.php` | `ventes` | Ventes issues des récoltes. |

## Détails des modèles

### User (à compléter)
**Fichier:** `app/Models/User.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function exploitations(): HasMany
    {
        return $this->hasMany(Exploitation::class);
    }
}
```

### Exploitation
**Fichier:** `app/Models/Exploitation.php`

**Attributs (`fillable`):**
```php
['user_id', 'nom', 'adresse', 'superficie_ha', 'telephone', 'numero_siret', 'description']
```

**Casts:**
```php
[
    'superficie_ha' => 'decimal:2',
]
```

**Relations:**
- `belongsTo(User::class)` - `user()`
- `hasMany(Bassin::class)` - `bassins()`
- `hasMany(Aliment::class)` - `aliments()` (si aliments rattachés à l'exploitation)

### Bassin
**Fichier:** `app/Models/Bassin.php`

**Attributs (`fillable`):**
```php
['exploitation_id', 'nom', 'type', 'superficie_m2', 'profondeur_m', 'capacite_m3', 'surface_eau_m2', 'actif', 'observations']
```

**Casts:**
```php
[
    'superficie_m2' => 'decimal:2',
    'profondeur_m' => 'decimal:2',
    'capacite_m3' => 'decimal:2',
    'surface_eau_m2' => 'decimal:2',
    'actif' => 'boolean',
]
```

**Relations:**
- `belongsTo(Exploitation::class)` - `exploitation()`
- `hasMany(CycleElevage::class)` - `cycleElevages()`
- `hasMany(PrelevementEau::class)` - `prelevementEaux()`
- `hasMany(Traitement::class)` - `traitements()`
- `hasOne(CycleElevage::class)->where('statut', 'en_cours')` - `cycleEnCours()` (helper)

### Espece
**Fichier:** `app/Models/Espece.php`

**Attributs (`fillable`):**
```php
['nom_commun', 'nom_scientifique', 'nom_local', 'temperature_min', 'temperature_max', 'ph_min', 'ph_max', 'description']
```

**Casts:**
```php
[
    'temperature_min' => 'decimal:1',
    'temperature_max' => 'decimal:1',
    'ph_min' => 'decimal:1',
    'ph_max' => 'decimal:1',
]
```

**Relations:**
- `hasMany(CycleElevage::class)` - `cycleElevages()`

### CycleElevage
**Fichier:** `app/Models/CycleElevage.php`

**Attributs (`fillable`):**
```php
['bassin_id', 'espece_id', 'date_debut', 'date_fin', 'statut', 'effectif_depart', 'effectif_actuel', 'poids_moyen_depart_g', 'densite_alevins_m2', 'notes']
```

**Casts:**
```php
[
    'date_debut' => 'date',
    'date_fin' => 'date',
    'effectif_depart' => 'integer',
    'effectif_actuel' => 'integer',
    'poids_moyen_depart_g' => 'decimal:2',
    'densite_alevins_m2' => 'decimal:2',
]
```

**Relations:**
- `belongsTo(Bassin::class)` - `bassin()`
- `belongsTo(Espece::class)` - `espece()`
- `hasMany(DistributionAliment::class)` - `distributionAliments()`
- `hasMany(Mortalite::class)` - `mortalites()`
- `hasMany(PrelevementEau::class)` - `prelevementEaux()`
- `hasMany(Traitement::class)` - `traitements()`
- `hasMany(Recolte::class)` - `recoltes()`

### Aliment
**Fichier:** `app/Models/Aliment.php`

**Attributs (`fillable`):**
```php
['exploitation_id', 'nom', 'type', 'proteines_pourcent', 'prix_unitaire', 'unite', 'stock_actuel', 'stock_minimum']
```

**Casts:**
```php
[
    'proteines_pourcent' => 'decimal:2',
    'prix_unitaire' => 'decimal:2',
    'stock_actuel' => 'decimal:2',
    'stock_minimum' => 'decimal:2',
]
```

**Relations:**
- `belongsTo(Exploitation::class)` - `exploitation()` (nullable)
- `hasMany(DistributionAliment::class)` - `distributionAliments()`

### DistributionAliment
**Fichier:** `app/Models/DistributionAliment.php`

**Attributs (`fillable`):**
```php
['cycle_elevage_id', 'aliment_id', 'date_distribution', 'quantite_kg', 'prix_unitaire_kg', 'montant_total', 'heure', 'observations']
```

**Casts:**
```php
[
    'date_distribution' => 'date',
    'quantite_kg' => 'decimal:2',
    'prix_unitaire_kg' => 'decimal:2',
    'montant_total' => 'decimal:2',
]
```

**Relations:**
- `belongsTo(CycleElevage::class)` - `cycleElevage()`
- `belongsTo(Aliment::class)` - `aliment()`

### Mortalite
**Fichier:** `app/Models/Mortalite.php`

**Attributs (`fillable`):**
```php
['cycle_elevage_id', 'date_deces', 'nombre', 'cause', 'observations']
```

**Casts:**
```php
[
    'date_deces' => 'date',
    'nombre' => 'integer',
]
```

**Relations:**
- `belongsTo(CycleElevage::class)` - `cycleElevage()`

### PrelevementEau
**Fichier:** `app/Models/PrelevementEau.php`

**Attributs (`fillable`):**
```php
['bassin_id', 'cycle_elevage_id', 'date_prelevement', 'temperature_c', 'ph', 'oxygene_mg_l', 'ammoniac_mg_l', 'nitrites_mg_l', 'nitrates_mg_l', 'transparence_cm', 'observations']
```

**Casts:**
```php
[
    'date_prelevement' => 'datetime',
    'temperature_c' => 'decimal:1',
    'ph' => 'decimal:1',
    'oxygene_mg_l' => 'decimal:2',
    'ammoniac_mg_l' => 'decimal:2',
    'nitrites_mg_l' => 'decimal:2',
    'nitrates_mg_l' => 'decimal:2',
    'transparence_cm' => 'integer',
]
```

**Relations:**
- `belongsTo(Bassin::class)` - `bassin()`
- `belongsTo(CycleElevage::class)` - `cycleElevage()` (nullable)

### Traitement
**Fichier:** `app/Models/Traitement.php`

**Attributs (`fillable`):**
```php
['bassin_id', 'cycle_elevage_id', 'date_traitement', 'produit', 'motif', 'dose', 'quantite_appliquee', 'duree_jours', 'avec_arret_recolte', 'responsable', 'observations']
```

**Casts:**
```php
[
    'date_traitement' => 'date',
    'duree_jours' => 'integer',
    'avec_arret_recolte' => 'boolean',
]
```

**Relations:**
- `belongsTo(Bassin::class)` - `bassin()`
- `belongsTo(CycleElevage::class)` - `cycleElevage()` (nullable)

### Recolte
**Fichier:** `app/Models/Recolte.php`

**Attributs (`fillable`):**
```php
['cycle_elevage_id', 'date_recolte', 'type', 'quantite_kg', 'nombre_poissons', 'poids_moyen_g', 'prix_vente_unitaire_kg', 'observations']
```

**Casts:**
```php
[
    'date_recolte' => 'date',
    'quantite_kg' => 'decimal:2',
    'nombre_poissons' => 'integer',
    'poids_moyen_g' => 'decimal:2',
    'prix_vente_unitaire_kg' => 'decimal:2',
]
```

**Relations:**
- `belongsTo(CycleElevage::class)` - `cycleElevage()`
- `hasMany(Vente::class)` - `ventes()`

### Vente
**Fichier:** `app/Models/Vente.php`

**Attributs (`fillable`):**
```php
['recolte_id', 'date_vente', 'client_nom', 'client_contact', 'quantite_kg', 'prix_unitaire_kg', 'montant_total', 'mode_paiement', 'statut', 'numero_facture', 'notes']
```

**Casts:**
```php
[
    'date_vente' => 'date',
    'quantite_kg' => 'decimal:2',
    'prix_unitaire_kg' => 'decimal:2',
    'montant_total' => 'decimal:2',
]
```

**Relations:**
- `belongsTo(Recolte::class)` - `recolte()`

## Remarques d'implémentation

- Utiliser `HasFactory` sur les modèles si des factories sont nécessaires plus tard.
- Pour `CycleElevage`, prévoir une méthode `calculerEffectifActuel()` pour mettre à jour depuis `mortalites` et `recoltes`.
- Pour `DistributionAliment`, calculer automatiquement `montant_total = quantite_kg * prix_unitaire_kg` dans un observer/mutator si souhaité.
- Pour `Vente`, idem pour `montant_total`.
- Appliquer la validation "un seul cycle en_cours par bassin" au niveau Form Request / Service.
- `PrelevementEau` et `Traitement` restent accessibles depuis `Bassin` même sans cycle actif. 
