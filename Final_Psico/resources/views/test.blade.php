@extends('adminlte::page')

@section('title', 'Cuestionario PHQ-9')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-gradient-info text-white d-flex align-items-center">
                <i class="fas fa-brain mr-2"></i>
                <h4 class="mb-0">🧠 Cuestionario de Salud del Paciente - PHQ-9</h4>
            </div>

            <div class="card-body">
                {{-- Mensajes iniciales --}}
                @if (session('test_result'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Resultado:</strong> {{ session('test_result') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Mensaje de procesamiento --}} 
                <div id="processing-message" class="alert alert-warning alert-dismissible fade" style="display:none;">
                    <i class="fas fa-hourglass-half"></i> Lamina está evaluando tu cuestionario, por favor espera...
                </div>

                {{-- Resultado final --}}
                <div id="test-response-message" class="mt-3"></div>

                {{-- Formulario PHQ-9 --}}
                <form id="phq9-form" action="{{ route('tests.submit') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="font-weight-bold">Nombre completo</label>
                            <input type="text" name="full_name" class="form-control form-control-lg rounded-pill" required placeholder="Ej: Juan Pérez">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="age" class="font-weight-bold">Edad</label>
                            <input type="number" name="age" class="form-control form-control-lg rounded-pill" required min="12" max="50" placeholder="Ej: 25">
                        </div>
                    </div>

                    @php
                        $questions = [
                            'p1' => 'Poco interés o placer en hacer cosas',
                            'p2' => 'Sentirse deprimido, decaído o sin esperanza',
                            'p3' => 'Dificultad para dormir o dormir demasiado',
                            'p4' => 'Sentirse cansado o con poca energía',
                            'p5' => 'Falta de apetito o comer en exceso',
                            'p6' => 'Sentirse mal consigo mismo - o que es un fracaso o que ha quedado mal consigo mismo o con su familia',
                            'p7' => 'Dificultad para concentrarse en cosas, como leer el periódico o ver la televisión',
                            'p8' => 'Moverse o hablar tan despacio que otras personas podrían haberlo notado. O lo contrario: estar tan inquieto o agitado que se ha estado moviendo mucho más de lo habitual',
                            'p9' => 'Pensamientos de que estaría mejor muerto o de hacerse daño de alguna manera'
                        ];
                        $options = [
                            0 => 'Nunca',
                            1 => 'Varios días',
                            2 => 'Más de la mitad de los días',
                            3 => 'Casi todos los días'
                        ];
                    @endphp

                    @foreach ($questions as $key => $question)
                        <div class="form-group question-card p-3 mb-3 rounded shadow-sm">
                            <label class="font-weight-bold">{{ $loop->iteration }}. {{ $question }}</label>
                            <select name="{{ $key }}" class="form-control rounded-pill" required>
                                <option value="" disabled selected>Selecciona una opción</option>
                                @foreach ($options as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-end mt-4">
                        <button id="submit-btn" type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm">
                            Enviar Respuestas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        body {
            background-color: #f4f6f9;
        }

        .question-card {
            border-left: 5px solid white;
            transition: all 0.3s ease-in-out;
        }

        .question-completed {
            border-left: 5px solid #28a745;
            background-color: #054710;
        }

        select.form-control {
            padding-right: 2rem;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.2);
        }

        #submit-btn {
            font-weight: bold;
            transition: all 0.3s ease;
        }

        #submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-progress {
            position: relative;
            overflow: hidden;
        }

        .btn-progress::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: repeating-linear-gradient(
                to right,
                #fff,
                #fff 20px,
                #e0e0e0 20px,
                #e0e0e0 40px
            );
            animation: loadingAnim 1s infinite linear;
            opacity: 0.4;
        }

        @keyframes loadingAnim {
            from { background-position: 0 0; }
            to { background-position: 100px 0; }
        }
    </style>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('phq9-form');
    const submitBtn = form.querySelector('#submit-btn');
    const processingMessage = document.getElementById('processing-message');
    const responseMessage = document.getElementById('test-response-message');

    // Marcar preguntas completadas visualmente
    document.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', function () {
            const group = this.closest('.form-group');
            group.classList.add('question-completed');
        });

        if (select.value) {
            select.closest('.form-group').classList.add('question-completed');
        }
    });

    // Manejar envío del formulario
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        responseMessage.innerHTML = '';
        processingMessage.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';
        submitBtn.classList.add('btn-progress');

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            setTimeout(() => {
                processingMessage.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.textContent = 'Enviar Respuestas';
                submitBtn.classList.remove('btn-progress');

                if (data.message) {
                    responseMessage.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Resultado:</strong> ${data.message}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>`;
                    if (data.severity === 'none' || data.severity === 'minimal') {
                        celebrate();
                    }
                } else if (data.error) {
                    responseMessage.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                }
            }, 1500);
        })
        .catch(error => {
            console.error(error);
            processingMessage.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.textContent = 'Enviar Respuestas';
            submitBtn.classList.remove('btn-progress');
            responseMessage.innerHTML = `<div class="alert alert-danger">Ocurrió un error al enviar. Intente nuevamente.</div>`;
        });
    });

    // Confeti opcional para resultados leves o nulos
    function celebrate() {
        const colors = ['#28a745', '#20c997', '#17a2b8', '#ffc107'];
        const container = document.createElement('div');
        container.style.position = 'fixed';
        container.style.top = '0';
        container.style.left = '0';
        container.style.width = '100%';
        container.style.height = '100%';
        container.style.pointerEvents = 'none';
        container.style.zIndex = '10000';
        document.body.appendChild(container);

        for (let i = 0; i < 50; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.style.position = 'absolute';
                confetti.style.width = '10px';
                confetti.style.height = '10px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.borderRadius = '50%';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-10px';
                confetti.style.opacity = '0.8';
                container.appendChild(confetti);

                const animation = confetti.animate([
                    { top: '-10px', opacity: 0.8 },
                    { top: '100vh', opacity: 0 }
                ], {
                    duration: 1000 + Math.random() * 2000,
                    easing: 'cubic-bezier(0.1, 0.8, 0.9, 1)'
                });

                animation.onfinish = () => confetti.remove();
            }, i * 100);
        }

        setTimeout(() => container.remove(), 6000);
    }
});
</script>
@stop