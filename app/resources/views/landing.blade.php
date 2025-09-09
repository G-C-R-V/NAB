<x-guest-layout>
    <div class="max-w-3xl mx-auto text-center py-16">
        <h1 class="text-4xl font-bold mb-4">Bienvenido</h1>
        <p class="text-gray-500 mb-8">Reservá tu turno y encargá postres, fácil y rápido.</p>
        <div class="flex gap-4 justify-center">
            <a href="{{ route('appointments.select') }}" class="px-6 py-3 bg-indigo-600 text-white rounded">Reservar turno</a>
            <a href="{{ route('shop.index') }}" class="px-6 py-3 bg-emerald-600 text-white rounded">Ver tienda</a>
        </div>
        @php($legal = \App\Models\Setting::get('legal.text', ''))
        @if($legal)
            <div class="mt-8 text-xs text-gray-400">{!! nl2br(e($legal)) !!}</div>
        @endif
    </div>
</x-guest-layout>

