<x-guest-layout>
    <div class="max-w-2xl mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-4">Confirmación de turno</h1>
        <div class="bg-white dark:bg-gray-800 rounded border p-4">
            <div class="mb-2">Turno #{{ $appointment->id }} - {{ $appointment->service->name }}</div>
            <div class="text-sm text-gray-600 mb-2">Con {{ $appointment->staff->display_name }} el {{ $appointment->start_at->locale('es_AR')->translatedFormat('d/m/Y H:i') }}</div>
            <div class="text-sm mb-2">Estado: <span class="font-medium">{{ ucfirst($appointment->status) }}</span></div>
            <div class="text-sm">Seña requerida: ${{ number_format($depositAmount,2,',','.') }} ({{ $depositPercent }}%)</div>
        </div>

        <div class="mt-4 flex gap-3">
            <form method="POST" action="{{ route('appointments.pay', $appointment) }}">
                @csrf
                <button class="px-5 py-2 bg-emerald-600 text-white rounded">Pagar seña</button>
            </form>
            <a href="{{ route('appointments.ics', $appointment) }}" class="px-5 py-2 bg-gray-200 dark:bg-gray-700 rounded">Agregar a Google Calendar</a>
            @php($phone = \App\Models\Setting::get('business.phone','+5491112345678'))
            @php($text = rawurlencode('Hola, tengo una consulta sobre mi turno '.$appointment->id.' el '.$appointment->start_at->format('d/m/Y').' '.$appointment->start_at->format('H:i')))
            <a target="_blank" href="https://wa.me/{{ ltrim($phone,'+') }}?text={{ $text }}" class="px-5 py-2 bg-green-600 text-white rounded">Escribir por WhatsApp</a>
        </div>

        <div class="mt-6 text-xs text-gray-500">
            Política: la seña no es reembolsable si cancelás dentro de {{ (int)(\App\Models\Setting::get('appointments.cancel_window_hours',48) ?? 48) }}h.
        </div>
    </div>
</x-guest-layout>

