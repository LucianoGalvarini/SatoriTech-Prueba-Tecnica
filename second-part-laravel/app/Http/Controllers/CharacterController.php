<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;

class CharacterController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function saveCharacter(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return response()->json(['error' => 'Invalid JSON data'], 400);
            }

            foreach ($data as $characterData) {
                $existingCharacter = Character::where('name', $characterData['name'])->first();

                if (!$existingCharacter) {
                    $character = new Character;
                    $character->name = $characterData['name'];
                    $character->status = $characterData['status'];
                    $character->species = $characterData['species'];
                    $character->image = $characterData['image'];
                    $character->save();
                }
            }

            return response()->json(['message' => 'Characters saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save characters'], 500);
        }
    }

    public function showCharacters()
    {
        $characters = Character::all();
        return view('home', ['characters' => $characters]);
    }
}
