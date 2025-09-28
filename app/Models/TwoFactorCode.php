<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TwoFactorCode extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','code','expires_at'];

    protected $dates = ['expires_at'];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
