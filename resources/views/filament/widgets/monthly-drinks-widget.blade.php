<div>
    <x-filament::section>
        <x-slot name="heading">
            Monatliche Getränke
        </x-slot>
        <div class="flex flex-col gap-y-6">
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

            <!-- Table Section -->
            <div>
                {{ $this->table }}
            </div>
        </div>
    </x-filament::section>
</div>
