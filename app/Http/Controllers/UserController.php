<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Bucht ein Getränk und zieht den Preis vom Guthaben des Benutzers ab.
     */
    public function bookDrink(Request $request, User $user, Item $item)
    {
        $transaction = new Transaction([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'amount' => $item->price,
            'is_paid' => false,
        ]);
        $transaction->save();

        $item->stock -= 1;
        $item->save();

        return response()->json(['message' => 'Drink booked successfully', 'balance' => $user->balance]);
    }

    /**
     * Bezahlt die Schulden des Benutzers und lädt ggf. Guthaben auf.
     */
    public function pay(Request $request, User $user)
    {
        // Betrag, den der Benutzer zahlen möchte, aus der Anfrage abrufen
        $amount = $request->input('amount');

        // Alle unbezahlten Transaktionen des Benutzers abrufen
        $transactions = Transaction::where('user_id', $user->id)->where('is_paid', false)->get();

        // Gesamtschulden des Benutzers berechnen
        $total_debt = $transactions->sum('amount');

        // Wenn der gezahlte Betrag die Gesamtschulden deckt oder übersteigt
        if ($amount >= $total_debt) {
            // Alle unbezahlten Transaktionen als bezahlt markieren
            foreach ($transactions as $transaction) {
                $transaction->is_paid = true;
                $transaction->save();
            }
            // Restbetrag als Guthaben auf das Benutzerkonto aufladen
            $user->balance += ($amount - $total_debt);
        } else {
            // Wenn der gezahlte Betrag die Gesamtschulden nicht deckt
            foreach ($transactions as $transaction) {
                // Transaktionen der Reihe nach als bezahlt markieren, bis der Betrag aufgebraucht ist
                if ($amount >= $transaction->amount) {
                    $amount -= $transaction->amount;
                    $transaction->is_paid = true;
                    $transaction->save();
                } else {
                    break;
                }
            }
        }

        // Benutzerkonto speichern
        $user->save();

        // Erfolgsnachricht und aktuelles Guthaben zurückgeben
        return response()->json(['message' => 'Payment processed successfully', 'balance' => $user->balance]);
    }
}
