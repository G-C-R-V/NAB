<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Settings</h2></x-slot>
    <div class="p-6 max-w-3xl">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            <label>Seña turnos (%)<input name="appointments_deposit_percent" type="number" step="0.1" value="{{ $settings['appointments_deposit_percent'] }}" class="w-full border rounded p-2" /></label>
            <label>Ventana cancelación turnos (h)<input name="appointments_cancel_window_hours" type="number" value="{{ $settings['appointments_cancel_window_hours'] }}" class="w-full border rounded p-2" /></label>
            <label>Buffer entre turnos (min)<input name="appointments_buffer_minutes" type="number" value="{{ $settings['appointments_buffer_minutes'] }}" class="w-full border rounded p-2" /></label>
            <label>Seña pedidos (%)<input name="orders_deposit_percent" type="number" step="0.1" value="{{ $settings['orders_deposit_percent'] }}" class="w-full border rounded p-2" /></label>
            <label>Ventana cancelación pedidos (h)<input name="orders_cancel_window_hours" type="number" value="{{ $settings['orders_cancel_window_hours'] }}" class="w-full border rounded p-2" /></label>
            <label>Whatsapp negocio<input name="business_phone" value="{{ $settings['business_phone'] }}" class="w-full border rounded p-2" /></label>
            <label class="sm:col-span-2">Textos legales<textarea name="legal_text" class="w-full border rounded p-2" rows="4">{{ $settings['legal_text'] }}</textarea></label>
            <div class="sm:col-span-2"><x-primary-button>Guardar</x-primary-button></div>
        </form>
    </div>
</x-app-layout>

