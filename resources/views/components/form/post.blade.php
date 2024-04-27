<form method="post" action="{{ $action }}">
    @csrf
    @method($method)
    <div class="space-y-6">
        {{ $slot }}
    </div>
</form>