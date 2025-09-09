<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Servicios</h2></x-slot>
    <div class="p-6">
        <a href="{{ route('services.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Nuevo</a>
        <table class="mt-4 w-full text-sm">
            <tr class="text-left border-b">
                <th class="py-2">Nombre</th><th>Duración</th><th>Precio</th><th>Activo</th><th></th>
            </tr>
            @foreach($services as $s)
                <tr class="border-b">
                    <td class="py-2">{{ $s->name }}</td>
                    <td>{{ $s->duration_minutes }}m</td>
                    <td>${{ number_format($s->price,2,',','.') }}</td>
                    <td>{{ $s->active? 'Sí':'No' }}</td>
                    <td class="text-right">
                        <a class="text-indigo-600" href="{{ route('services.edit',$s) }}">Editar</a>
                        <form method="POST" action="{{ route('services.destroy',$s) }}" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600" onclick="return confirm('Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>

