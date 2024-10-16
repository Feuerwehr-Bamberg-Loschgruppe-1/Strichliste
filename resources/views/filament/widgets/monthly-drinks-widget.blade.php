<div>
    <x-filament::section>
        <x-slot name="heading">
            Monatliche Getränke
        </x-slot>
        <!-- Tab Navigation -->
        <x-filament::tabs label="Getränke pro Monat" class="flex justify-center max-w-max">
            @foreach ($availableYears as $year)
                <x-filament::tabs.item
                    wire:click="setYear({{ $year }})"
                    :active="$currentYear == $year"
                >
                    {{ $year }}
                </x-filament::tabs.item>
            @endforeach
        </x-filament::tabs>

        <div class="mt-4">
            {{ $this->table }}
        </div>
    </x-filament::section>
</div>