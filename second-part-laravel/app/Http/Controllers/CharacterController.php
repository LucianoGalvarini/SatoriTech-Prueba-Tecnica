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
                return $this->invalidJsonResponse();
            }

            $this->saveCharacters($data);

            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    private function invalidJsonResponse()
    {
        return response()->json(['error' => 'Invalid JSON data'], 400);
    }

    private function saveCharacters(array $data)
    {
        foreach ($data as $characterData) {
            $existingCharacter = Character::where('name', $characterData['name'])->first();

            if (!$existingCharacter) {
                $this->createCharacter($characterData);
            }
        }
    }

    private function createCharacter(array $characterData)
    {
        $character = new Character;
        $character->name = $characterData['name'];
        $character->status = $characterData['status'];
        $character->species = $characterData['species'];
        $character->image = $characterData['image'];
        $character->save();
    }

    private function successResponse()
    {
        return response()->json(['message' => 'Characters saved successfully']);
    }

    private function errorResponse()
    {
        return response()->json(['error' => 'Failed to save characters'], 500);
    }


    public function showCharacters()
    {
        $characters = Character::all();
        return view('home', ['characters' => $characters]);
    }
}
