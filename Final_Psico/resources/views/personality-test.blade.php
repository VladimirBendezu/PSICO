@extends('adminlte::page')
@section('title', 'Test de Personalidad OCEAN')

{{-- Agregar meta tag para CSRF --}}
@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-gradient-purple text-white d-flex align-items-center">
                <i class="fas fa-user-circle mr-2"></i>
                <h4 class="mb-0">🧠 Test de Personalidad OCEAN</h4>
            </div>
            <div class="card-body">
                {{-- Mensajes iniciales --}}
                @if (session('test_registered'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Resultado:</strong> {{ session('test_registered') }}
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
                    <i class="fas fa-hourglass-half"></i> Lamina está evaluando tu test, por favor espera...
                </div>
                {{-- Resultado final --}}
                <div id="test-response-message" class="mt-3"></div>

                {{-- Formulario OCEAN dividido por secciones --}}
                <form id="ocean-form" action="{{ route('personality-test.submit') }}" method="POST">
                    @csrf

                    <!-- Sección AP - Apertura -->
                    <div class="step step-ap active">
                        <h4><i class="fas fa-lightbulb text-info"></i> 1. Apertura (AP)</h4>
                        @php
                            $ap_questions = [
                                'Disfruto aprendiendo sobre temas nuevos e inusuales.',
                                'Me gustan las actividades creativas, como la pintura, la escritura o la música.',
                                'Prefiero la variedad a la rutina.',
                                'Me gusta explorar diferentes formas de ver el mundo.',
                                'Me siento atraído/a por las experiencias nuevas y diferentes.'
                            ];
                        @endphp
                        @foreach($ap_questions as $index => $question)
                            <div class="form-group question-card p-3 mb-3 rounded shadow-sm">
                                <label>{{ $loop->iteration }}. {{ $question }}</label>
                                <select name="ap_{{ $index + 1 }}" class="form-control custom-select rounded-pill" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    <option value="1">Totalmente en desacuerdo</option>
                                    <option value="2">En desacuerdo</option>
                                    <option value="3">Neutral</option>
                                    <option value="4">De acuerdo</option>
                                    <option value="5">Totalmente de acuerdo</option>
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sección RE - Responsabilidad -->
                    <div class="step step-re" style="display: none;">
                        <h4><i class="fas fa-check-double text-success"></i> 2. Responsabilidad (RE)</h4>
                        @php
                            $re_questions = [
                                'Soy una persona organizada y mantengo mis cosas en orden.',
                                'Planifico mis actividades y cumplo mis compromisos.',
                                'Trabajo de manera metódica y detallada.',
                                'Prefiero terminar las tareas antes de descansar o relajarme.',
                                'Soy cuidadoso/a y detallista en mi trabajo.'
                            ];
                        @endphp
                        @foreach($re_questions as $index => $question)
                            <div class="form-group question-card p-3 mb-3 rounded shadow-sm">
                                <label>{{ $loop->iteration }}. {{ $question }}</label>
                                <select name="re_{{ $index + 1 }}" class="form-control custom-select rounded-pill" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    <option value="1">Totalmente en desacuerdo</option>
                                    <option value="2">En desacuerdo</option>
                                    <option value="3">Neutral</option>
                                    <option value="4">De acuerdo</option>
                                    <option value="5">Totalmente de acuerdo</option>
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sección EX - Extraversión -->
                    <div class="step step-ex" style="display: none;">
                        <h4><i class="fas fa-smile-wink text-primary"></i> 3. Extraversión (EX)</h4>
                        @php
                            $ex_questions = [
                                'Me siento cómodo/a al interactuar con muchas personas.',
                                'Disfruto estar rodeado/a de gente y socializar.',
                                'Me considero una persona enérgica y activa.',
                                'Disfruto siendo el centro de atención en las reuniones.',
                                'Me gusta participar en actividades grupales.'
                            ];
                        @endphp
                        @foreach($ex_questions as $index => $question)
                            <div class="form-group question-card p-3 mb-3 rounded shadow-sm">
                                <label>{{ $loop->iteration }}. {{ $question }}</label>
                                <select name="ex_{{ $index + 1 }}" class="form-control custom-select rounded-pill" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    <option value="1">Totalmente en desacuerdo</option>
                                    <option value="2">En desacuerdo</option>
                                    <option value="3">Neutral</option>
                                    <option value="4">De acuerdo</option>
                                    <option value="5">Totalmente de acuerdo</option>
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sección AM - Afabilidad -->
                    <div class="step step-am" style="display: none;">
                        <h4><i class="fas fa-hands-helping text-warning"></i> 4. Afabilidad (AM)</h4>
                        @php
                            $am_questions = [
                                'Trato de ser amable y comprensivo/a con todos.',
                                'Me gusta ayudar a los demás sin esperar nada a cambio.',
                                'Evito los conflictos y trato de llevarme bien con todos.',
                                'Me considero una persona generosa y de buen corazón.',
                                'Confío en que las personas son, en su mayoría, buenas.'
                            ];
                        @endphp
                        @foreach($am_questions as $index => $question)
                            <div class="form-group question-card p-3 mb-3 rounded shadow-sm">
                                <label>{{ $loop->iteration }}. {{ $question }}</label>
                                <select name="am_{{ $index + 1 }}" class="form-control custom-select rounded-pill" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    <option value="1">Totalmente en desacuerdo</option>
                                    <option value="2">En desacuerdo</option>
                                    <option value="3">Neutral</option>
                                    <option value="4">De acuerdo</option>
                                    <option value="5">Totalmente de acuerdo</option>
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sección NE - Neuroticismo -->
                    <div class="step step-ne" style="display: none;">
                        <h4><i class="fas fa-sad-tear text-danger"></i> 5. Neuroticismo (NE)</h4>
                        @php
                            $ne_questions = [
                                'Frecuentemente me siento ansioso/a o preocupado/a.',
                                'Suelo reaccionar intensamente a situaciones de estrés.',
                                'A menudo me siento inseguro/a o con baja autoestima.',
                                'Tiendo a sentirme triste o desanimado/a sin motivo aparente.',
                                'Me resulta difícil recuperarme rápidamente de situaciones difíciles.'
                            ];
                        @endphp
                        @foreach($ne_questions as $index => $question)
                            <div class="form-group question-card p-3 mb-3 rounded shadow-sm">
                                <label>{{ $loop->iteration }}. {{ $question }}</label>
                                <select name="ne_{{ $index + 1 }}" class="form-control custom-select rounded-pill" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    <option value="1">Totalmente en desacuerdo</option>
                                    <option value="2">En desacuerdo</option>
                                    <option value="3">Neutral</option>
                                    <option value="4">De acuerdo</option>
                                    <option value="5">Totalmente de acuerdo</option>
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <!-- Botones de navegación -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" id="prev-btn" class="btn btn-secondary btn-lg px-4 rounded-pill shadow-sm" style="display: none;">Anterior</button>
                        <button type="button" id="next-btn" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm">Siguiente</button>
                        <button type="submit" id="submit-btn" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm" style="display: none;">Enviar Test</button>
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
        border-left: 5px solid;
        transition: all 0.3s ease-in-out;
    }
    .question-completed {
        border-left: 5px solid #28a745;
        background-color: #054710;
    }
    select.form-control {
        padding-right: 2rem;
        background-color: #fff;
        color: #333;
        font-size: 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    select.form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.2);
    }
    select option {
        background-color: #fff;
        color: #333;
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
    const steps = document.querySelectorAll('.step');
    let currentStep = 0;

    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');

    // Mostrar paso actual
    function showStep(index) {
        steps.forEach((step, i) => {
            step.style.display = i === index ? 'block' : 'none';
        });

        prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
        nextBtn.style.display = index === steps.length - 1 ? 'none' : 'inline-block';
        submitBtn.style.display = index === steps.length - 1 ? 'inline-block' : 'none';
    }

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

    // Navegación entre pasos
    prevBtn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });

    showStep(currentStep);

    // Manejar envío del formulario
    const form = document.getElementById('ocean-form');
    const processingMessage = document.getElementById('processing-message');
    const responseMessage = document.getElementById('test-response-message');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        responseMessage.innerHTML = '';
        processingMessage.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';
        submitBtn.classList.add('btn-progress');

        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            // Verificar si la respuesta es JSON válida
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                throw new Error('La respuesta no es JSON válida');
            }
        })
        .then(data => {
            setTimeout(() => {
                processingMessage.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.textContent = 'Enviar Test';
                submitBtn.classList.remove('btn-progress');
                
                if (data.success && data.message) {
                    responseMessage.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>🎉 ¡Resultado obtenido!</strong><br>
                            ${data.message}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>`;
                    celebrate();
                } else if (data.error) {
                    responseMessage.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> ${data.error}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>`;
                } else {
                    responseMessage.innerHTML = `
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Advertencia:</strong> Respuesta inesperada del servidor.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>`;
                }
            }, 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            processingMessage.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.textContent = 'Enviar Test';
            submitBtn.classList.remove('btn-progress');
            responseMessage.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> Ocurrió un error al procesar el test. Por favor, intenta nuevamente.
                    <br><small>Detalles técnicos: ${error.message}</small>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`;
        });
    });

    // Confeti para resultados exitosos
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