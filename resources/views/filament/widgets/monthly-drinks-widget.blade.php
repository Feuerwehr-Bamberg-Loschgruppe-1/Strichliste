<div>
    <!-- Tab Navigation -->
    <div class="flex border-b border-gray-200">
        @foreach ($availableYears as $year)
            <button wire:click="setYear({{ $year }})"
                    class="py-2 px-4 {{ $year == $currentYear ? 'border-b-2 border-blue-500 text-blue-500' : '' }}">
                {{ $year }}
            </button>
        @endforeach
    </div>

    <!-- Tabelle anzeigen -->
    <div class="mt-4">
        {{ $this->table }}
    </div>
</div>
