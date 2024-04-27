<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create permission:') }}
            </h2>
            <div class=" space-x-4 sm:-my-px sm:ml-10 flex">
                <x-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">
                    {{ __('User') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.role.index')" :active="request()->routeIs('admin.role.*')">
                    {{ __('Role') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.permission.index')" :active="request()->routeIs('admin.permission.*')">
                    {{ __('Permission') }}
                </x-nav-link>
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sm:flex grid sm:grid-cols-2 grid-cols-1 sm:space-x-2 justify-between sm:space-y-0 space-y-6">
                <div class="flex-col max-w-xl w-full sm:w-1/2">
                    <div class="bg-white shadow sm:rounded-lg w-full overflow-auto p-4 sm:p-8">
                        <x-form.post method="post" action="{{ route('admin.permission.store') }}">
                            <div>
                                <x-form.label for="name" :value="__('Name:')" />
                                <x-form.input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name" />
                                <x-form.error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-form.label for="comment" :value="__('Comment:')" />
                                <x-form.input id="comment" name="comment" type="text" class="mt-1 block w-full" :value="old('comment')" autocomplete="comment" />
                                <x-form.error class="mt-2" :messages="$errors->get('comment')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-buttons.primary>{{ __('Create') }}</x-buttons.primary>
                            </div>
                        </x-form.post>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
