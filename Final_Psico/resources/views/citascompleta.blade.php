@extends('adminlte::page')

@section('title', 'Registrar Cita')

@section('content_header')
    <h1>Registrar Nueva Cita</h1>
@stop

@section('content')
    <form action="#" method="POST">
        @csrf

        <!-- Especialidad -->
        <div class="form-group">
            <label for="especialidad">Especialidad:</label>
            <select class="form-control" id="especialidad" name="especialidad" required>
                <option value="">Seleccione una opción</option>
                <option value="Psicoanalisis">Psicoanalisis</option>
                <option value="Cita Agendada Test">Cita Agendada Test</option>
                <option value="Terapia sistemica">Terapia sistemica</option>
                <option value="Terapia narrativa">Terapia narrativa</option>
                <option value="Consulta general">Consulta general</option>
                <option value="Terapia psicodinamica">Terapia psicodinamica</option>
            </select>
        </div>

        <!-- Edad -->
        <div class="form-group">
            <label for="edad">Edad:</label>
            <input type="number" class="form-control" id="edad" name="edad" min="18" max="75" placeholder="Ingrese edad" required>
        </div>

        <!-- Género -->
        <div class="form-group">
            <label for="genero">Género:</label>
            <select class="form-control" id="genero" name="genero" required>
                <option value="">Seleccione una opción</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select>
        </div>

        <!-- Ubicación -->
        <div class="form-group">
            <label for="ubicacion">Ubicación:</label>
            <select class="form-control" id="ubicacion" name="ubicacion" required>
                <option value="">Seleccione una opción</option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="Ubicacion_{{ $i }}">Ubicacion_{{ $i }}</option>
                @endfor
            </select>
        </div>

        <!-- Motivo -->
        <div class="form-group">
            <label for="motivo">Motivo:</label>
            <select class="form-control" id="motivo" name="motivo" required>
                <option value="">Seleccione una opción</option>
                <option value="Depresión">Depresión</option>
                <option value="Ansiedad">Ansiedad</option>
                <option value="Prueba psicologica">Prueba psicológica</option>
                <option value="Terapia de pareja">Terapia de pareja</option>
                <option value="Consulta general">Consulta general</option>
            </select>
        </div>

        <!-- Experiencia previa -->
        <div class="form-group">
            <label for="exp_prev">Citas previas:</label>
            <input type="number" class="form-control" id="exp_prev" name="exp_prev" min="0" max="20" placeholder="Ingrese la cantidad de citas previas" required>
        </div>

        <!-- Duración de la cita -->
        <div class="form-group">
            <label for="dur_cita">Duración de la cita (minutos):</label>
            <input type="number" class="form-control" id="dur_cita" name="dur_cita" min="30" max="360" placeholder="Duración en minutos" required>
        </div>

        <!-- Fecha -->
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="text" class="form-control" id="fecha" name="fecha" placeholder="AA/MM/DD" required>
        </div>

        <!-- Eventos locales -->
        <div class="form-group">
            <label for="eventos_locales">Eventos similares:</label>
            <input type="number" class="form-control" id="eventos_locales" name="eventos_locales" min="0" max="10" placeholder="Cantidad de eventos similares" required>
        </div>

        <!-- Condiciones climáticas -->
        <div class="form-group">
            <label for="condiciones_climaticas">Condiciones climáticas:</label>
            <select class="form-control" id="condiciones_climaticas" name="condiciones_climaticas" required>
                <option value="">Seleccione una opción</option>
                <option value="Frio">Frío</option>
                <option value="LLuvioso">Lluvioso</option>
                <option value="Soleado">Soleado</option>
            </select>
        </div>

        <!-- Promociones -->
        <div class="form-group">
            <label for="promociones">Si es de la universidad ingrese 1, si no lo es, ingrese 0:</label>
            <input type="number" class="form-control" id="promociones" name="promociones" min="0" max="60" placeholder="Ingrese 0 o 1" required>
        </div>

        <!-- Número de médicos -->
        <div class="form-group">
            <label for="num_medicos">Número de psicólogos visitados:</label>
            <input type="number" class="form-control" id="num_medicos" name="num_medicos" min="0" max="60" placeholder="Número de psicólogos visitados" required>
        </div>

        <!-- Tiempo de espera -->
        <div class="form-group">
            <label for="tiempo_espera">Tiempo de espera (minutos):</label>
            <input type="number" class="form-control" id="tiempo_espera" name="tiempo_espera" min="0" max="60" placeholder="Tiempo estimado de espera" required>
        </div>

        <!-- Demografía -->
        <div class="form-group">
            <label for="demografia">Considera que el tiempo de espera fue:</label>
            <select class="form-control" id="demografia" name="demografia" required>
                <option value="">Seleccione una opción</option>
                <option value="Alta">Alta</option>
                <option value="Media">Media</option>
                <option value="Baja">Baja</option>
            </select>
        </div>

        <!-- Botón para enviar -->
        <button type="submit" class="btn btn-primary">Registrar Cita</button>
    </form>
@stop
