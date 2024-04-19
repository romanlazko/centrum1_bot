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

            <label for="count_of_month">Count of month</label>
            <input id="count_of_month" type="number" name="count_of_month" required value="{{ old('count_of_month', request()->count_of_month) }}">
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
                Your age: {{ $birth->diff(now())->y }}
            </p>
            @foreach ($insurances as $insurance)
                <p>
                    {{ $insurance->insurance ?? $insurance->type }} - {{ $insurance->price }}
                </p>
            @endforeach
        @endif
    </body>
</html>
