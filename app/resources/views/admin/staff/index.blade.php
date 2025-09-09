<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Staff</h2></x-slot>
    <div class="p-6">
        <a href="{{ route('staff.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Nuevo</a>
        <table class="mt-4 w-full text-sm">
            <tr class="text-left border-b"><th class="py-2">Nombre</th><th>Usuario</th><th>Activo</th><th></th></tr>
            @foreach($staff as $st)
                <tr class="border-b">
                    <td class="py-2">{{ $st->display_name }}</td>
                    <td>{{ $st->user->email ?? '-' }}</td>
                    <td>{{ $st->active ? 'Sí' : 'No' }}</td>
                    <td class="text-right">
                        <a class="text-indigo-600" href="{{ route('staff.edit',$st) }}">Editar</a>
                        <form method="POST" action="{{ route('staff.destroy',$st) }}" class="inline">@csrf @method('DELETE')
                            <button class="text-red-600" onclick="return confirm('Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>

