<div class="bg-white shadow sm:rounded-lg w-full overflow-auto">
    <table {{ $attributes->merge(['class' => 'w-full p-4 ']) }}>
        {{ $slot }}
    </table>
</div>
