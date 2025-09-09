<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded border">
                <div class="text-sm text-gray-500">Turnos hoy</div>
                <div class="text-3xl font-semibold">{{ $stats['appointments_today'] }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded border">
                <div class="text-sm text-gray-500">Órdenes pendientes</div>
                <div class="text-3xl font-semibold">{{ $stats['orders_pending'] }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded border">
                <div class="text-sm text-gray-500">Pagos aprobados</div>
                <div class="text-3xl font-semibold">{{ $stats['payments_approved'] }}</div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 flex gap-3">
            <a class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded" href="{{ route('admin.appointments.index') }}">Turnos</a>
            <a class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded" href="{{ route('services.index') }}">Servicios</a>
            <a class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded" href="{{ route('staff.index') }}">Staff</a>
            <a class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded" href="{{ route('admin.settings.edit') }}">Settings</a>
            <a class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded" href="{{ route('products.index') }}">Productos</a>
        </div>
    </div>
</x-app-layout>

