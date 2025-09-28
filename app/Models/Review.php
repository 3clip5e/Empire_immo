<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['property_id','bailleur_id','user_id','rating','comment'];

    public function user(){ return $this->belongsTo(User::class); }
    public function property(){ return $this->belongsTo(Property::class); }
    public function bailleur(){ return $this->belongsTo(User::class, 'bailleur_id'); }
}
