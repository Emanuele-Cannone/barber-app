@push('scripts')
    @vite(['resources/js/calendar.js'])
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('event-deleted', (e) => {
                Swal.fire({
                    icon: "success",
                    title: e[0].message,
                    showConfirmButton: false,
                    timer: 1500
                });

                setTimeout(function () {
                    location.reload();
                }, 1500)
            });
        });
    </script>

    <script type="text/javascript">
        let appointments = @js($appointments);
        let appointmentPermission = @js(auth()->user()->can('manage-appointment'));
        let isBarber = @js(auth()->user()->getAllPermissions()->count() === 1 && auth()->user()->getAllPermissions()->first()->name === 'see-appointment')
    </script>
@endpush

<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <livewire:delete-appointment/>

    <div x-data="{ modalOpen: false }"
         x-init="
            document.addEventListener('open-modal', () => {
                modalOpen = true;
            });
        "
         @keydown.escape.window="modalOpen = false"
         class="relative z-10 w-auto h-auto">

        <livewire:event-modal/>


        <template x-teleport="body">
            <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen"
                 x-cloak>
                <div x-show="modalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="modalOpen=false" class="absolute inset-0 w-full h-full bg-black bg-opacity-40"></div>
                <div x-show="modalOpen"
                     x-trap.inert.noscroll="modalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full py-6 bg-white px-7 sm:max-w-lg sm:rounded-lg">
                    <div class="relative w-auto">
                        <form action="{{ route('admin.appointment.store') }}" method="post">
                            @csrf
                            <div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Aggiungi Appuntamento</h3>
                                    <div class="mt-2">
                                        <input name="name" type="text" placeholder="Nome"
                                               class="mt-1 text-gray-500 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('name') <span
                                            class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-2">
                                        <input name="contact" type="tel" placeholder="Telefono" required
                                               class="mt-1 text-gray-500 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('contact') <span
                                            class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-2">
                                        <select name="service_id"
                                                class="mt-1 text-gray-500 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            @php foreach ($services as $service) { @endphp
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                            @php } @endphp
                                        </select>
                                    </div>
                                    <div class="mt-2">
                                    <textarea name="description" type="text"
                                              placeholder="Descrizione del servizio"
                                              class="mt-1 text-gray-500 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                        @error('description') <span
                                            class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-2">
                                        <div class="relative max-w-xl flex w-full flex-col rounded-xl bg-white shadow">
                                            <nav class="flex min-w-[240px] flex-row gap-1 p-2">
                                                @foreach($barbers as $barber)
                                                    <div
                                                        role="button"
                                                        class="flex w-full items-center rounded-lg p-0 transition-all hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100"
                                                    >
                                                        <label
                                                            for="{{'B_'.$barber->id}}"
                                                            class="flex w-full cursor-pointer items-center px-3 py-2"
                                                        >
                                                            <div class="inline-flex items-center">
                                                                <label class="relative flex items-center cursor-pointer"
                                                                       for="{{'B_'.$barber->id}}">
                                                                    <input
                                                                        name="barber_id"
                                                                        type="radio"
                                                                        class="peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-slate-300 checked:border-slate-400 transition-all"
                                                                        id="{{'B_'.$barber->id}}"
                                                                        value="{{ $barber->id }}"
                                                                    />
                                                                    <span
                                                                        class="absolute bg-slate-800 w-3 h-3 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></span>
                                                                </label>
                                                                <label
                                                                    class="ml-2 text-slate-600 cursor-pointer text-sm"
                                                                    for="{{'B_'.$barber->id}}">
                                                                    {{ $barber->name }}
                                                                </label>
                                                            </div>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </nav>
                                        </div>
                                    </div>
                                    <div class="flex justify-between">
                                        <div class="mt-2">
                                            <input name="start" type="date"
                                                   class="mt-1 text-gray-500 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            @error('date') <span
                                                class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mt-2">
                                            <input name="time" type="time"
                                                   class="mt-1 text-gray-500 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            @error('time') <span
                                                class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6 flex justify-between">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Crea</button>

                                <button type="button" @click="document.querySelectorAll('input[name=\'barber_id\']').forEach(el => el.checked = false)" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Reset</button>

                                <button type="button" @click="modalOpen = false" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Annulla</button>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </template>
    </div>


    @if(session('success'))
        <script type="module">
            Swal.fire({
                icon: "success",
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    @endif


</x-app-layout>



