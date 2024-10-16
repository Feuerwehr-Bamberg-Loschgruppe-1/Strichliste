<div>
    <!-- Tab Navigation -->
    <x-filament::tabs label="Getränke pro Monat">
        @foreach ($availableYears as $year)
            <x-filament::tabs.item
                wire:click="setYear({{ $year }})"
                :active="$currentYear == $year"
            >
                {{ $year }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    <!-- Tabelle mit wire:key neu laden, wenn forceRerender sich ändert -->
    <div class="mt-4">
        {{ $this->table }}
    </div>
</div>
