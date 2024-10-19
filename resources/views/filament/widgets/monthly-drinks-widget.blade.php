<x-filament::section class="monthly-drinks-widget">
    <div class="flex flex-col gap-y-6">
        <div class="flex items-center justify-between header-section">
            <p class="flex-shrink-0 whitespace-nowrap title" style="margin-right: 1rem;">Monatliche Getränke</p>
            <!-- Tab Navigation -->
            <x-filament::tabs label="Getränke pro Monat" class="justify-end tabs-navigation">
                @foreach ($availableYears as $year)
                    <x-filament::tabs.item
                        wire:click="setYear({{ $year }})"
                        :active="$currentYear == $year"
                        class="tab-item-{{ $year }}"
                    >
                        {{ $year }}
                    </x-filament::tabs.item>
                @endforeach
            </x-filament::tabs>
        </div>
        <!-- Table Section -->
        <div class="table-section" style="margin-left: -1.5rem; margin-right: -1.5rem; margin-bottom: -1.5rem;">
            {{ $this->table }}
        </div>
    </div>
</x-filament::section>
