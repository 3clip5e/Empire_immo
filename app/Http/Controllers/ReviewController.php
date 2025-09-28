<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(){ $this->middleware('auth'); }

    public function store(Request $r, Property $property)
    {
        $data = $r->validate(['rating'=>'required|integer|min:1|max:5','comment'=>'nullable|string']);
        Review::create([
            'property_id'=>$property->id,
            'bailleur_id'=>$property->user_id,
            'user_id'=>Auth::id(),
            'rating'=>$data['rating'],
            'comment'=>$data['comment'] ?? null
        ]);
        return back()->with('success','Merci pour votre avis');
    }
}
