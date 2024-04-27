<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users:') }}
            </h2>
            <div class="space-x-4 sm:-my-px sm:ml-10 flex">
                <x-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">
                    {{ __('User') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.role.index')" :active="request()->routeIs('admin.role.*')">
                    {{ __('Role') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.permission.index')" :active="request()->routeIs('admin.permission.*')">
                    {{ __('Permission') }}
                </x-nav-link>
                <x-a-buttons.secondary href="{{ route('admin.user.create') }}" class="float-right">
                    {{ __("✚") }}
                </x-a-buttons.secondary>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-table.table class="whitespace-nowrap">
                <x-table.thead>
                    <tr>
                        <x-table.th>id</x-table.th>
                        @foreach ($attributes as $attribute)
                            <x-table.th>{{ ucfirst($attribute) }}</x-table.th>
                        @endforeach
                        <x-table.th>Role</x-table.th>
                        <x-table.th>Action</x-table.th>
                    </tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse ($users as $index => $user)
                        <tr class="@if($index % 2 === 0) bg-gray-100 @endif">
                            <x-table.td>{{ $user->id }}</x-table.td>
                            @foreach ($attributes as $attribute)
                                <x-table.td>{{ $user->$attribute }}</x-table.th>
                            @endforeach
                            <x-table.td>
                                @forelse ($user->roles as $role)
                                    <x-badge color="">
                                        <a href="{{ route('admin.role.edit', $role) }}">{{ $role->name }}</a>
                                    </x-badge>
                                @empty
                                    
                                @endforelse
                                
                            </x-table.td>
                            <x-table.buttons>
                                <x-a-buttons.primary href="{{ route('admin.user.edit', $user) }}">Edit</x-a-buttons.primary>
                                <form action="{{ route('admin.user.destroy', $user) }}" method="post" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-buttons.danger>Delete</x-buttons.dangertton>
                                </form>
                            </x-table.buttons>
                        </tr>
                    @empty
                    @endforelse
                </x-table.tbody>
            </x-table.table>
        </div>
    </div>
</x-app-layout>