<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PsicoSISTEMA</title>
    <!-- Incluye los estilos de AdminLTE o Bootstrap -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        .bg {
            background-image: url('{{ asset('images/portadaf.png') }}');
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .btn-custom {
            background-color: #4AC5A6; /* Color de fondo */
            color: white; /* Color del texto */
            border: none;
            padding: 15px 30px;
            margin: 10px;
            font-size: 18px;
            border-radius: 5px;
            text-transform: uppercase;
            text-decoration: none;
        }

        .btn-custom:hover {
            background-color: #3EA58B; /* Color del botón al hacer hover */
        }
    </style>
</head>
<body>
    <div class="bg">
        <a href="{{ route('login') }}" class="btn-custom">Iniciar Sesión</a>
        <a href="{{ route('register') }}" class="btn-custom">Registrarse</a>
    </div>
</body>
</html>
