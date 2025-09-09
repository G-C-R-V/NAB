<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Editar servicio</h2></x-slot>
    <div class="p-6 max-w-xl">
        <form method="POST" action="{{ route('services.update',$service) }}" class="space-y-3">
            @csrf @method('PUT')
            <x-input-label value="Nombre" />
            <x-text-input name="name" class="w-full" value="{{ $service->name }}" />
            <x-input-label value="Duración (min)" />
            <x-text-input type="number" name="duration_minutes" class="w-full" value="{{ $service->duration_minutes }}" />
            <x-input-label value="Precio" />
            <x-text-input type="number" step="0.01" name="price" class="w-full" value="{{ $service->price }}" />
            <label><input type="checkbox" name="active" value="1" @checked($service->active)> Activo</label>
            <x-primary-button>Guardar</x-primary-button>
        </form>
    </div>
</x-app-layout>

