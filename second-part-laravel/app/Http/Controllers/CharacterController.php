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

            return $this->successResponse('Characters saved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to save characters');
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

    public function showCharacters()
    {
        try {
            $characters = Character::all();
            return view('home', ['characters' => $characters]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to show characters');
        }
    }

    private function successResponse($message)
    {
        return response()->json(['message' => $message], 200);
    }

    private function errorResponse($message)
    {
        return response()->json(['error' => $message], 500);
    }
}
