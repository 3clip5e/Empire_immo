<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;
    protected $fillable = ['property_id','user_id','reason','details','status'];

    public function user(){ return $this->belongsTo(User::class); }
    public function property(){ return $this->belongsTo(Property::class); }
}
