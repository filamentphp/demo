<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('pages.contact-us');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
            'entreprise' => 'nullable|string',
            'telephone' => 'nullable|required_if:entreprise,on|max:20',
            'ville' => 'nullable|required_if:entreprise,on|max:100',
            'nom_entreprise' => 'nullable|required_if:entreprise,on|max:255',
            'num_pattente' => 'nullable|max:50',
        ]);


        Contact::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'message' => $request->message,
            'entreprise' => $request->has('entreprise') ? 1 : 0,
            'telephone' => $request->telephone,
            'ville' => $request->ville,
            'nom_entreprise' => $request->nom_entreprise,
            'num_pattente' => $request->num_pattente,
        ]);


        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès.');
    }
}
