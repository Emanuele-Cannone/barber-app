<x-app-layout>

    @push('scripts')
        <script type="module">
            Livewire.on('user-created', (e) => {
                Swal.fire({
                    icon: "success",
                    title: e[0].message,
                    showConfirmButton: false,
                    timer: 1500
                })
            });
            Livewire.on('role-changed', (e) => {
                Swal.fire({
                    icon: "success",
                    title: e[0].message,
                    showConfirmButton: false,
                    timer: 1500
                })
            });
        </script>
    @endpush

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:user-table/>
                    <livewire:create-user-modal/>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



