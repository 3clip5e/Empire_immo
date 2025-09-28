<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['property_id','user_id','start_date','end_date','status','notes'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user(){ return $this->belongsTo(User::class); }
    public function property(){ return $this->belongsTo(Property::class); }
}
