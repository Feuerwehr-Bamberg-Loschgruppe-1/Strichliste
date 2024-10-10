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
    // Definiert die zugehörige Ressource
    protected static string $resource = TransactionResource::class;

    // Diese Methode wird aufgerufen, bevor die Transaktion erstellt wird, um die Formulardaten zu ändern
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Überprüft, ob die Kategorie 'booking' ist
        if ($data['category'] === 'booking') {
            // Abrufen des Produkts anhand der item_id
            $item = Item::find($data['item_id']);
            // Wenn das Produkt existiert, setze den amount-Wert auf den Preis des Produkts
            if ($item) {
                $data['amount'] = $item->price;
            }
            // Setze is_paid auf false für Buchungen
            $data['is_paid'] = false;
        } elseif ($data['category'] === 'payment') {
            // Setze is_paid auf null für Zahlungen
            $data['is_paid'] = null;
        }

        // Gibt die modifizierten Daten zurück
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
                // Wenn das Guthaben ausreicht, die Buchung als bezahlt markieren und vom Guthaben abziehen
                if ($user->balance >= $transaction->amount) {
                    $user->balance -= $transaction->amount;
                    $transaction->is_paid = true;
                } else {
                    // Wenn das Guthaben nicht ausreicht, die Buchung als unbezahlt lassen und Betrag abziehen
                    $user->balance -= $transaction->amount;
                    $transaction->is_paid = false;
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

            // Guthaben des Benutzers erhöhen
            $user->balance += $transaction->amount;

            // Summe der offenen Transaktionen berechnen
            $totalOpenAmount = $openTransactions->sum('amount');

            // Überprüfen, ob das Guthaben ausreicht, um offene Transaktionen zu bezahlen
            foreach ($openTransactions as $openTransaction) {
                if ($user->balance >= - ($totalOpenAmount - $openTransaction->amount)) {
                    $openTransaction->is_paid = true;
                    $openTransaction->save();
                    $totalOpenAmount -= $openTransaction->amount;
                }
            }
            // Benutzer speichern
            $user->save();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
