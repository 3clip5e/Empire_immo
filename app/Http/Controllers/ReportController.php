<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct(){ $this->middleware('auth'); }

    public function store(Request $r, Property $property)
    {
        $data = $r->validate(['reason'=>'required|string','details'=>'nullable|string']);
        Report::create([
            'property_id'=>$property->id,'user_id'=>Auth::id(),
            'reason'=>$data['reason'],'details'=>$data['details'] ?? null
        ]);
        return back()->with('success','Signalement envoyé, merci');
    }
}
