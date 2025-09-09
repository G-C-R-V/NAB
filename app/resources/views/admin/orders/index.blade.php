<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Órdenes</h2></x-slot>
    <div class="p-6">
        <table class="w-full text-sm">
            <tr class="text-left border-b"><th class="py-2">#</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Entrega estimada</th><th></th></tr>
            @foreach($orders as $o)
                <tr class="border-b">
                    <td class="py-2">{{ $o->id }}</td>
                    <td>{{ $o->customer->name }}</td>
                    <td>${{ number_format($o->total,2,',','.') }}</td>
                    <td>{{ $o->status }}</td>
                    <td>{{ optional($o->delivery_date_estimate)->format('d/m/Y H:i') }}</td>
                    <td class="text-right">
                        <form method="POST" action="{{ route('admin.orders.deliver',$o) }}">@csrf @method('PATCH')
                            <button class="text-emerald-600">Marcar entregado</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>

