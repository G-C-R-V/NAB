<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Horarios base</h2></x-slot>
    <div class="p-6 max-w-4xl">
        <form method="POST" action="{{ route('schedules.store') }}" class="flex gap-3 items-end">
            @csrf
            <label>Staff
                <select name="staff_id" class="border rounded p-2">
                    @foreach($staff as $st)
                        <option value="{{ $st->id }}">{{ $st->display_name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Día
                <select name="weekday" class="border rounded p-2">
                    @foreach(['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'] as $i=>$d)
                        <option value="{{ $i }}">{{ $d }}</option>
                    @endforeach
                </select>
            </label>
            <label>Inicio
                <input type="time" name="start_time" class="border rounded p-2">
            </label>
            <label>Fin
                <input type="time" name="end_time" class="border rounded p-2">
            </label>
            <x-primary-button>Agregar</x-primary-button>
        </form>

        <table class="mt-6 w-full text-sm">
            <tr class="text-left border-b"><th class="py-2">Staff</th><th>Día</th><th>Horario</th><th></th></tr>
            @foreach($schedules as $sch)
                <tr class="border-b">
                    <td class="py-2">{{ $sch->staff->display_name }}</td>
                    <td>{{ ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'][$sch->weekday] }}</td>
                    <td>{{ $sch->start_time }} - {{ $sch->end_time }}</td>
                    <td class="text-right">
                        <form method="POST" action="{{ route('schedules.destroy',$sch) }}">@csrf @method('DELETE')
                            <button class="text-red-600" onclick="return confirm('Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>

