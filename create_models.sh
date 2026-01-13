#!/bin/bash

# 1. Modèle Categorie
cat > app/Models/Categorie.php << 'EOF'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'icone',
        'couleur',
        'ordre',
        'est_active'
    ];

    protected $casts = [
        'est_active' => 'boolean',
        'ordre' => 'integer'
    ];

    public function ressources()
    {
        return $this->belongsToMany(Ressource::class, 'categorie_ressource');
    }
}
EOF

# 2. Modèle Tag
cat > app/Models/Tag.php << 'EOF'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['nom', 'slug'];

    public function ressources()
    {
        return $this->belongsToMany(Ressource::class, 'ressource_tag');
    }
}
EOF

# 3. Modèle Role
cat > app/Models/Role.php << 'EOF'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['nom', 'slug', 'description'];

    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class);
    }
}
EOF

# 4. Modèle Utilisateur (mise à jour)
cat > app/Models/Utilisateur.php << 'EOF'
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'telephone',
        'adresse',
        'code_postal',
        'ville',
        'role_id',
        'est_actif',
        'cgu_accepte'
    ];

    protected $hidden = ['mot_de_passe', 'remember_token'];

    protected $casts = [
        'est_actif' => 'boolean',
        'cgu_accepte' => 'boolean',
        'date_inscription' => 'datetime'
    ];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function ressources()
    {
        return $this->hasMany(Ressource::class, 'auteur_id');
    }

    public function favoris()
    {
        return $this->belongsToMany(Ressource::class, 'favoris')
            ->withPivot('date_ajout')
            ->withTimestamps();
    }

    public function activites()
    {
        return $this->belongsToMany(Activite::class, 'participants', 'utilisateur_id', 'activite_id')
            ->withPivot('statut', 'date_inscription')
            ->withTimestamps();
    }
}
EOF

# 5. Modèle Ressource
cat > app/Models/Ressource.php << 'EOF'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ressource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titre',
        'description',
        'contenu',
        'type_ressource',
        'type_relation',
        'niveau',
        'statut',
        'niveau_partage',
        'url_externe',
        'url_image',
        'duree_lecture',
        'auteur_id',
        'date_creation',
        'date_publication',
        'nb_vues',
        'nb_favoris'
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'date_publication' => 'datetime',
        'nb_vues' => 'integer',
        'nb_favoris' => 'integer'
    ];

    public function auteur()
    {
        return $this->belongsTo(Utilisateur::class, 'auteur_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Categorie::class, 'categorie_ressource');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'ressource_tag');
    }
}
EOF

# 6. Modèle Activite
cat > app/Models/Activite.php << 'EOF'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'type',
        'statut',
        'date_debut',
        'date_fin',
        'lieu',
        'en_ligne',
        'lien_visio',
        'nb_participants_max',
        'nb_participants_actuels',
        'organisateur_id'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'en_ligne' => 'boolean',
        'nb_participants_max' => 'integer',
        'nb_participants_actuels' => 'integer'
    ];

    public function organisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'organisateur_id');
    }

    public function participants()
    {
        return $this->belongsToMany(Utilisateur::class, 'participants', 'activite_id', 'utilisateur_id')
            ->withPivot('statut', 'date_inscription')
            ->withTimestamps();
    }
}
EOF

echo "✅ Tous les modèles ont été créés !"
