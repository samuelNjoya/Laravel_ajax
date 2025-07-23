<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
   // protected $table = 'films';
   //protected $fillable = ['titre', 'description', 'photo'];

    // Méthode pour récupérer le chemin complet de la photo
    public function getPhotoUrl()
    {
        return $this->photo ? asset('upload/films/' . $this->photo) : null;
    }
}
