<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Bucht ein Getränk und zieht den Preis vom Guthaben des Benutzers ab.
     */
    public function bookDrink(Request $request, User $user, Item $item)
    {
        $user->balance -= $item->price;
        $user->save();

        return response()->json(['message' => 'Drink booked successfully', 'balance' => $user->balance]);
    }

    /**
     * Lädt Guthaben auf das Konto des Benutzers auf.
     */
    public function addFunds(Request $request, User $user)
    {
        $amount = $request->input('amount');
        $user->balance += $amount;
        $user->save();

        return response()->json(['message' => 'Funds added successfully', 'balance' => $user->balance]);
    }
}
