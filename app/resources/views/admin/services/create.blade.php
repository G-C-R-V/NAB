<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Nuevo servicio</h2></x-slot>
    <div class="p-6 max-w-xl">
        <form method="POST" action="{{ route('services.store') }}" class="space-y-3">
            @csrf
            <x-input-label value="Nombre" />
            <x-text-input name="name" class="w-full" />
            <x-input-label value="Duración (min)" />
            <x-text-input type="number" name="duration_minutes" class="w-full" />
            <x-input-label value="Precio" />
            <x-text-input type="number" step="0.01" name="price" class="w-full" />
            <label><input type="checkbox" name="active" value="1" checked> Activo</label>
            <x-primary-button>Guardar</x-primary-button>
        </form>
    </div>
</x-app-layout>

