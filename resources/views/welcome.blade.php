<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    </head>
    <body>
        <form method="GET" action="{{ route('welcome') }}">
            <label for="birth">Birth day</label>
            <input id="birth" type="date" name="birth" required value="{{ old('birth', request()->birth) }}">
            <hr>

            <label for="start_date">Start date</label>
            <input id="start_date" type="date" name="start_date" required value="{{ old('start_date', request()->start_date) }}">
            <hr>
            <label for="end_date">End date</label>
            <input id="end_date" type="date" name="end_date" required value="{{ old('end_date', request()->end_date) }}">
            <hr>

            <label for="standart">Standart</label>
            <input id="standart" type="radio" name="type" value="standart" @checked(request()->type == 'standart')>
            <label for="student">Student</label>
            <input id="student" type="radio" name="type" value="student" @checked(request()->type == 'student')>
            <label for="sport">Sport</label>
            <input id="sport" type="radio" name="type" value="sport" @checked(request()->type == 'sport')>
            <label for="pregnant">Pregnant</label>
            <input id="pregnant" type="radio" name="type" value="pregnant" @checked(request()->type == 'pregnant')>
            <hr>
            <label for="shengen">Shengen</label>
            <input id="shengen" type="checkbox" name="shengen" @checked(request()->shengen == true)>
            <hr>

            <button type="submit">Отправить</button>
        </form>
        <hr>

        @if (isset($insurances))
            <p>
                Your age: {{ $data->start_date }}
            </p>
            <p>
                {{-- Count of month: {{ $data->count_of_month }} --}}
            </p>
            @foreach ($insurances as $insurance)
                <p>
                    {{ $insurance->insurance ?? $insurance->type }} - {{ $insurance->price }}
                </p>
            @endforeach
        @endif
    </body>
</html>
