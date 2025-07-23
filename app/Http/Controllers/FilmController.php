<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilmController extends Controller
{
    // Liste de tous les films
    public function index()
    {
        $data['meta_title'] = "Liste films";
        $data['films'] = Film::orderBy('id', 'desc')->get();
        return view('film.index', $data);
    }

    // Créer un film
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $film = new Film();
        $film->titre = $request->titre;
        $film->description = $request->description;

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = date('YmdHis') . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/films/'), $filename);
            $film->photo = $filename;
        }

        $film->save();

        // Réponse JSON pour AJAX + message succès
        return response()->json(['success' => 'Film ajouté avec succès']);
       
    }

    // Affiche un film pour l'édition via AJAX
    public function show($id)
    {
        $film = Film::findOrFail($id);
            // Ajoute URL image complète à retourner dans la réponse JSON
        $film->photo_url = $film->photo ? asset('upload/films/' . $film->photo) : null;
        
        return response()->json($film);
    }

    // Mettre à jour un film
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $film = Film::findOrFail($id);
        $film->titre = $request->titre;
        $film->description = $request->description;

        if ($request->hasFile('photo')) {
            // Supprimer ancienne photo si existante (optionnel)
            if ($film->photo && file_exists(public_path('upload/films/' . $film->photo))) {
                unlink(public_path('upload/films/' . $film->photo));
            }

            $file = $request->file('photo');
            $filename = date('YmdHis') . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/films/'), $filename);
            $film->photo = $filename;
        }

        $film->save();

        return response()->json(['success' => 'Film mis à jour avec succès']);
    }

    // Supprimer un film
    public function destroy($id)
    {
        $film = Film::findOrFail($id);

        // Supprimer la photo si existante
        if ($film->photo && file_exists(public_path('upload/films/' . $film->photo))) {
            unlink(public_path('upload/films/' . $film->photo));
        }

        $film->delete();

        return response()->json(['success' => 'Film supprimé avec succès']);
    }
}
