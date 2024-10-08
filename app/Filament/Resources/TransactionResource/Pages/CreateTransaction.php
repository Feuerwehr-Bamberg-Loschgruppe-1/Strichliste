<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Item;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    // Diese Methode wird aufgerufen, bevor die Transaktion erstellt wird, um die Formulardaten zu ändern
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['category'] === 'booking') {
            // Abrufen des Produkts und Setzen des amount-Werts auf den Preis des Produkts
            $item = Item::find($data['item_id']);
            if ($item) {
                $data['amount'] = $item->price;
            }
            $data['is_paid'] = false; // Setze is_paid auf false für Buchungen
        } elseif ($data['category'] === 'payment') {
            $data['is_paid'] = null; // Setze is_paid auf null für Zahlungen
            $data['item_id'] = null; // Setze item_id auf null für Zahlungen
        }

        return $data;
    }

    // Diese Methode wird nach der Erstellung der Transaktion aufgerufen
    protected function afterCreate(): void
    {
        // Die gerade erstellte Transaktion abrufen
        $transaction = $this->record;

        // Benutzer abrufen, der mit der Transaktion verknüpft ist
        $user = User::find($transaction->user_id);

        // Überprüfen, ob die Transaktion eine Buchung ist
        if ($transaction->category === 'booking') {
            // Das gebuchte Produkt abrufen
            $item = Item::find($transaction->item_id);

            // Bestand des Produkts verringern, wenn das Produkt existiert
            if ($item) {
                $item->stock -= 1;
                $item->save();
            }

            // Guthaben des Benutzers anpassen
            if ($user) {
                if ($user->balance >= $transaction->amount) {
                    // Wenn das Guthaben ausreicht, die Buchung als bezahlt markieren und vom Guthaben abziehen
                    $user->balance -= $transaction->amount;
                    $transaction->is_paid = true;
                    Log::info("Booking: User balance after booking: {$user->balance}");
                } else {
                    // Wenn das Guthaben nicht ausreicht, die Buchung als unbezahlt lassen und Betrag abziehen
                    $user->balance -= $transaction->amount;
                    $transaction->is_paid = false;
                    Log::info("Booking: User balance after booking (insufficient funds): {$user->balance}");
                }
                // Transaktion und Benutzer speichern
                $transaction->save();
                $user->save();
            }
        }
        // Überprüfen, ob die Transaktion eine Zahlung ist
        elseif ($transaction->category === 'payment') {
            // Alle offenen Transaktionen (is_paid = false) für den Benutzer abrufen
            $openTransactions = Transaction::where('user_id', $transaction->user_id)
                ->where('is_paid', false)
                ->get();

            // Initialisieren des verbleibenden Betrags mit dem Betrag der aktuellen Transaktion
            $remainingAmount = $transaction->amount;
            Log::info("Payment: Initial remaining amount: {$remainingAmount}");
            Log::info("Payment: User initial balance: {$user->balance}");

            // Guthaben des Benutzers erhöhen
            $user->balance += $remainingAmount;
            Log::info("Payment: User balance after adding payment: {$user->balance}");

            // Überprüfen, ob das Guthaben jetzt 0 oder größer ist und offene Transaktionen bezahlt werden können
            if ($user->balance >= 0) {
                foreach ($openTransactions as $openTransaction) {
                    $openTransaction->is_paid = true;
                    $openTransaction->save();
                    Log::info("Payment: Open transaction marked as paid because user balance is 0 or greater.");
                }
            }

            // Benutzer speichern
            $user->save();
        }
    }
}
