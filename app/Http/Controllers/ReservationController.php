<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function __construct(){ $this->middleware('auth'); }

    public function index()
    {
        // reservations du client
        $reservations = Auth::user()->role === 'bailleur'
            ? Reservation::whereHas('property', fn($q)=> $q->where('user_id', Auth::id()))->with('property','user')->latest()->paginate(10)
            : Auth::user()->reservations()->with('property')->latest()->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function store(Request $r, Property $property)
    {
        $data = $r->validate([
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date',
            'notes'=>'nullable|string'
        ]);

        // Vérifier disponibilité simple (non overlapping)
        $overlap = Reservation::where('property_id', $property->id)
            ->where('status','<>','cancelled')
            ->where(function($q) use ($data){
                $q->whereBetween('start_date', [$data['start_date'],$data['end_date']])
                  ->orWhereBetween('end_date', [$data['start_date'],$data['end_date']])
                  ->orWhere(function($q2) use ($data){ $q2->where('start_date','<',$data['start_date'])->where('end_date','>',$data['end_date']); });
            })->exists();

        if($overlap) return back()->with('error','Dates indisponibles');

        $reservation = Reservation::create([
            'property_id'=>$property->id,
            'user_id'=>Auth::id(),
            'start_date'=>$data['start_date'],
            'end_date'=>$data['end_date'],
            'notes'=>$data['notes'] ?? null,
            'status'=>'pending'
        ]);

        // notifier le bailleur par mail (simple)
        Mail::to($property->owner->email)->send(new \App\Mail\ContactOwnerMail($property, [
            'name'=>Auth::user()->name,
            'email'=>Auth::user()->email,
            'message'=>"Nouvelle demande de réservation du {$data['start_date']} au {$data['end_date']}."
        ]));

        return redirect()->route('reservations.index')->with('success','Réservation envoyée');
    }

    public function updateStatus(Request $r, Reservation $reservation)
    {
        $this->authorize('update', $reservation); // policy idéal (bailleur contrôle)
        $r->validate(['status'=>'required|in:pending,confirmed,cancelled,completed']);
        $reservation->update(['status'=>$r->status]);
        return back()->with('success','Statut mis à jour');
    }

    public function destroy(Reservation $reservation)
    {
        if(Auth::id() !== $reservation->user_id && Auth::user()->role !== 'admin') abort(403);
        $reservation->update(['status'=>'cancelled']);
        return back()->with('success','Réservation annulée');
    }
}
