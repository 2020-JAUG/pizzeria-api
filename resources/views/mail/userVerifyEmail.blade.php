<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzeria</title>
</head>

<body>
    <main>
        <div>
            <div class="body-text">
                <h1>¡Bienvenida/o {{ $name }}!</h1>
                <div>
                    <p><span> Pulse el botón para verificar su cuenta</span></p>
                    <div class="button-centered button">
                        <a href="{{ $url }}" onclick="add()"><img src="{{ asset('images/verificar.png') }}" alt="Verify button"></a>
                    </div>
                </div>
            </div>
        </div>

    </main>
</body>

</html>