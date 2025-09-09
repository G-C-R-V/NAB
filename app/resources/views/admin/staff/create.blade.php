<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Nuevo staff</h2></x-slot>
    <div class="p-6 max-w-2xl">
        <form method="POST" action="{{ route('staff.store') }}" class="space-y-3">
            @csrf
            <label class="block">Usuario
                <select name="user_id" class="w-full border rounded p-2">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </label>
            <label class="block">Nombre para mostrar
                <input name="display_name" class="w-full border rounded p-2" />
            </label>
            <label class="block">Bio
                <textarea name="bio" class="w-full border rounded p-2"></textarea>
            </label>
            <label><input type="checkbox" name="active" value="1" checked> Activo</label>
            <label class="block">Servicios
                <select name="services[]" class="w-full border rounded p-2" multiple size="5">
                    @foreach($services as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </label>
            <x-primary-button>Guardar</x-primary-button>
        </form>
    </div>
</x-app-layout>

