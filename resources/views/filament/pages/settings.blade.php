<x-filament-panels::page>
    <x-slot name="heading">
        {{ $this->getTitle() }}
    </x-slot>

    <form wire:submit="save" class="space-y-6">
        {{ $this->content(\Filament\Schemas\Schema::make()) }}

        <div class="flex justify-end gap-3">
            {{ $this->getActions() }}
        </div>
    </form>
</x-filament-panels::page>
