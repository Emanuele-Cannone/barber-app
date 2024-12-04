
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

    <div x-data="{ modalOpen: false }"
         x-init="
            document.addEventListener('open-modal', () => {
                modalOpen = true;
            });
        "
         @keydown.escape.window="modalOpen = false"
         class="relative z-10 w-auto h-auto">

        <livewire:event-modal />


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
                            <div class="mt-5 sm:mt-6">
                                <button type="submit"
                                        class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
                                    Crea
                                </button>
                                <button type="button"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:text-sm"
                                        @click="modalOpen = false">Annulla
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </template>
    </div>


    <script type="text/javascript">
        let appointments = @js($appointments);
        let appointmentPermission = @js(auth()->user()->can('manage-appointment'));
        let isBarber = @js(auth()->user()->getAllPermissions()->count() === 1 && auth()->user()->getAllPermissions()->first()->name === 'see-appointment')
    </script>

</x-app-layout>



