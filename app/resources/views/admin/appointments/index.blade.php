<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Turnos</h2></x-slot>
    <div class="p-6">
        <table class="w-full text-sm">
            <tr class="text-left border-b"><th class="py-2">#</th><th>Fecha</th><th>Cliente</th><th>Staff</th><th>Servicio</th><th>Estado</th><th></th></tr>
            @foreach($appointments as $a)
                <tr class="border-b">
                    <td class="py-2">{{ $a->id }}</td>
                    <td>{{ $a->start_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $a->customer->name }}</td>
                    <td>{{ $a->staff->display_name }}</td>
                    <td>{{ $a->service->name }}</td>
                    <td>{{ $a->status }}</td>
                    <td class="text-right">
                        <form method="POST" action="{{ route('admin.appointments.confirm',$a) }}" class="inline">@csrf @method('PATCH')
                            <button class="text-emerald-600">Confirmar</button>
                        </form>
                        <form method="POST" action="{{ route('admin.appointments.cancel',$a) }}" class="inline">@csrf @method('PATCH')
                            <button class="text-red-600">Cancelar</button>
                        </form>
                        <form method="POST" action="{{ route('admin.appointments.done',$a) }}" class="inline">@csrf @method('PATCH')
                            <button class="text-indigo-600">Hecho</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="mt-3">{{ $appointments->links() }}</div>
    </div>
</x-app-layout>

