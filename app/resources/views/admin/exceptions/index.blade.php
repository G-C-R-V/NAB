<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Feriados y bloqueos</h2></x-slot>
    <div class="p-6 max-w-4xl">
        <form method="POST" action="{{ route('exceptions.store') }}" class="flex flex-wrap gap-3 items-end">
            @csrf
            <label>Staff
                <select name="staff_id" class="border rounded p-2">
                    @foreach($staff as $st)
                        <option value="{{ $st->id }}">{{ $st->display_name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Fecha
                <input type="date" name="date" class="border rounded p-2">
            </label>
            <label>Inicio
                <input type="time" name="start_time" class="border rounded p-2">
            </label>
            <label>Fin
                <input type="time" name="end_time" class="border rounded p-2">
            </label>
            <label><input type="checkbox" name="is_closed" value="1"> Día cerrado</label>
            <input name="note" placeholder="Nota" class="border rounded p-2" />
            <x-primary-button>Agregar</x-primary-button>
        </form>

        <table class="mt-6 w-full text-sm">
            <tr class="text-left border-b"><th class="py-2">Staff</th><th>Fecha</th><th>Bloqueo</th><th>Nota</th><th></th></tr>
            @foreach($exceptions as $ex)
                <tr class="border-b">
                    <td class="py-2">{{ $ex->staff->display_name }}</td>
                    <td>{{ $ex->date->format('d/m/Y') }}</td>
                    <td>{{ $ex->is_closed ? 'Cerrado todo el día' : ($ex->start_time.'-'.$ex->end_time) }}</td>
                    <td>{{ $ex->note }}</td>
                    <td class="text-right">
                        <form method="POST" action="{{ route('exceptions.destroy',$ex) }}">@csrf @method('DELETE')
                            <button class="text-red-600" onclick="return confirm('Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>

