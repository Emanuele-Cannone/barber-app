<div>
    <div x-data="{ show: @entangle('showModal') }" x-init="show = false" x-cloak>
        <div x-show="show" class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $title }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Descrizione: {{ $description }}</p>
                            <p class="text-sm text-gray-500">Inizio: {{ $start }}</p>
                            <p class="text-sm text-gray-500">Fine: {{ $end }}</p>
                            <p class="text-sm text-gray-500">ID: {{ $id }}</p>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6">
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:text-sm" @click="show = false" @click="$wire.emit('closeModal')">Annulla</button>
                    </div>

                    @if(Auth::user()->can('manage-appointment'))
                    <form action="{{ route('admin.appointment.destroy', ['appointment' => $id]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:text-sm">Elimina</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>


</div>
