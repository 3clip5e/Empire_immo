<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model {
    use HasFactory;

    protected $fillable = [ 'user_id','title','description','address','rooms','price','city','lat','lng', 'type' ];

    public function images() {
        return $this->hasMany( PropertyImage::class );
    }

    public function owner() {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function averageRating() {
        return $this->hasMany( \App\Models\Review::class )->avg( 'rating' ) ?? 0;
    }

    // Relation avec les réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }


     // Relation avec les reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
