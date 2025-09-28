<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(Request $r){
        $q = $r->query('q');
        $props = Property::when($q, function($qBuilder) use ($q) {
                $qBuilder->where('title','like','%'.$q.'%')->orWhere('city','like','%'.$q.'%');
            })
            ->with('images','owner')
            ->paginate(9);
        return view('properties.index', compact('props'));
    }

    public function create(){
        if (Auth::user()->role !== 'bailleur') abort(403);
        return view('properties.create');
    }

    public function store(Request $r){
        if (Auth::user()->role !== 'bailleur') abort(403);
        $data = $r->validate([
            'title'=>'required|string',
            'description'=>'nullable|string',
            'address'=>'nullable|string',
            'rooms'=>'nullable|integer',
            'price'=>'nullable|integer',
            'city'=>'nullable|string',
            'images.*'=>'image|max:5120'
        ]);

        $prop = Property::create(array_merge($data,['user_id'=>Auth::id()]));

        if ($r->hasFile('images')){
            foreach($r->file('images') as $img){
                $path = $img->store('properties','public');
                PropertyImage::create(['property_id'=>$prop->id,'path'=>$path]);
            }
        }

        return redirect()->route('properties.show',$prop)->with('success','Bien créé');
    }

    public function show(Property $property){
        $property->load('images','owner');
        return view('properties.show', compact('property'));
    }

    public function edit(Property $property){
        if (Auth::id() !== $property->user_id) abort(403);
        return view('properties.edit', compact('property'));
    }

    public function update(Request $r, Property $property){
        if (Auth::id() !== $property->user_id) abort(403);
        $data = $r->validate([
            'title'=>'required|string',
            'description'=>'nullable|string',
            'address'=>'nullable|string',
            'rooms'=>'nullable|integer',
            'price'=>'nullable|integer',
            'city'=>'nullable|string',
            'images.*'=>'image|max:5120'
        ]);
        $property->update($data);
        if ($r->hasFile('images')){
            foreach($r->file('images') as $img){
                $path = $img->store('properties','public');
                PropertyImage::create(['property_id'=>$property->id,'path'=>$path]);
            }
        }
        return back()->with('success','Mis à jour');
    }

    public function destroy(Property $property){
        if (Auth::id() !== $property->user_id) abort(403);
        $property->delete();
        return redirect()->route('properties.index')->with('success','Supprimé');
    }
}
