@extends('adminlte::page')

@section('title', 'PsicoSISTEMA')

@section('content_header')
    <h1>Hola, {{ Auth::user()->name }} 😃</h1>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@stop

@section('content')
    <div class="welcome-container">
        <div class="welcome-card">
            <h1 class="welcome-title">Bienvenido a PsicoSISTEMA</h1>
            <p class="welcome-message">Tu plataforma integral para el bienestar psicológico y el autoconocimiento</p>
            
            <div class="welcome-image">
                <img src="{{ asset('images/psychology-welcome.jpeg') }}" alt="Bienvenido a PsicoSISTEMA">
            </div>
            
            @if(!auth()->user()->has_completed_test)
                <div class="first-time-message">
                    <h3>¡Es tu primera vez aquí!</h3>
                    <p>Para comenzar tu viaje de autodescubrimiento, por favor realiza nuestro test de evaluación inicial.</p>
                    <a href="{{ route('tests.show') }}" class="btn btn-primary btn-lg">Realizar Test de Evaluación</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Resto del contenido (chatbot, WhatsApp, etc.) -->
    <div id="dynamic-content"></div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Imagen de ayuda y WhatsApp -->
    <div class="help-buttons">
        <img src="{{ asset('images/lamina.png') }}" alt="Ayuda" id="toggle-chatbot" class="help-image">
        <a href="https://wa.me/987654321" target="_blank" class="whatsapp-help">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" width="50" height="50">
        </a>
    </div>

    <!-- Contenedor del Chatbot -->
    <div id="chatbot-container" class="chatbot hidden">
        <div class="chatbot-header">
            <span>Chat de Ayuda</span>
            <button id="close-chatbot" class="close-chatbot">✖</button>
        </div>
        <div class="chatbot-messages" id="chatbot-messages">
            <!-- Aquí aparecerán los mensajes del chatbot -->
        </div>
        <div class="chatbot-input">
            <input type="text" id="user-input" placeholder="Escribe un mensaje..." />
            <button id="send-message">Enviar</button>
        </div>
    </div>
@stop

@section('css')
    <style>
        :root {
            --primary-color: #454D55;
            --primary-light: #5d6772;
            --primary-dark: #2e343a;
            --accent-color: #4AC5A6;
            --accent-dark: #3a9b84;
            --light-bg: #f8f9fa;
            --text-dark: #333;
            --text-light: #f8f9fa;
        }
        
        .welcome-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 2rem;
            background: var(--primary-color);
        }
        
        .welcome-card {
            background: #343A40;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 3rem;
            max-width: 900px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .welcome-title {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .welcome-message {
            font-size: 1.2rem;
            color: white;
            margin-bottom: 2rem;
        }
        
        .welcome-image img {
            max-width: 300px;
            margin: 0 auto 2rem;
            display: block;
            border-radius: 10px;
        }
        
        .first-time-message {
            background: var(--light-bg);
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
            border-left: 5px solid var(--accent-color);
        }
        
        .first-time-message h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .first-time-message p {
            margin-bottom: 1.5rem;
            color: var(--text-dark);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
            transform: translateY(-2px);
            color: white;
        }
        
        .dashboard-options {
            margin-top: 2rem;
        }
        
        .dashboard-options h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        
        .option-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: var(--light-bg);
            border-radius: 15px;
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: white;
        }
        
        .option-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        /* Estilos para el chatbot y WhatsApp */
        .help-buttons {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1050;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .help-image {
            width: 60px;
            height: 60px;
            cursor: pointer;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .whatsapp-help img {
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .whatsapp-help img:hover {
            transform: scale(1.1);
            transition: transform 0.3s;
        }

        .chatbot {
            position: fixed;
            bottom: 80px;
            left: 20px;
            width: 300px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .chatbot-header {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px 10px 0 0;
        }

        .chatbot-messages {
            padding: 10px;
            max-height: 200px;
            overflow-y: auto;
            flex-grow: 1;
            color: var(--text-dark);
            background: var(--light-bg);
        }

        .chatbot-messages .user-message {
            text-align: right;
            color: var(--text-dark);
            background: white;
            padding: 8px 12px;
            border-radius: 15px 15px 0 15px;
            margin: 5px;
            display: inline-block;
            max-width: 80%;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .chatbot-messages .bot-message {
            text-align: left;
            color: var(--text-dark);
            background: white;
            padding: 8px 12px;
            border-radius: 15px 15px 15px 0;
            margin: 5px;
            display: inline-block;
            max-width: 80%;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .chatbot-input {
            display: flex;
            border-top: 1px solid #ddd;
            background: white;
            border-radius: 0 0 10px 10px;
        }

        .chatbot-input input {
            flex-grow: 1;
            padding: 10px;
            border: none;
            border-radius: 0 0 0 10px;
            outline: none;
        }

        .chatbot-input button {
            background: var(--primary-color);
            color: var(--text-light);
            border: none;
            padding: 10px 20px;
            border-radius: 0 0 10px 0;
            cursor: pointer;
            transition: background 0.3s;
        }

        .chatbot-input button:hover {
            background: var(--primary-light);
        }

        .hidden {
            display: none;
        }
    </style>
@stop

@section('js')
    <script>
$(document).ready(function () {
    const chatbotContainer = $('#chatbot-container');
    const toggleChatbot = $('#toggle-chatbot');
    const closeChatbot = $('#close-chatbot');
    const messagesContainer = $('#chatbot-messages');
    const userInput = $('#user-input');
    const sendMessage = $('#send-message');

    // Abrir/cerrar chatbot
    toggleChatbot.click(() => chatbotContainer.toggleClass('hidden'));
    closeChatbot.click(() => chatbotContainer.addClass('hidden'));

    // Función para mostrar mensajes
    function addMessage(text, isUser = false) {
        const messageClass = isUser ? 'user-message' : 'bot-message';
        messagesContainer.append(`<div class="${messageClass}">${text}</div>`);
        messagesContainer.scrollTop(messagesContainer.prop("scrollHeight"));
    }

    // Función para normalizar texto (elimina tildes y convierte a minúsculas)
    function normalizeText(text) {
        return text
            .toLowerCase()
            .normalize('NFD') // Descompone caracteres con tilde
            .replace(/[\u0300-\u036f]/g, ''); // Elimina los diacríticos
    }

    // Respuestas del chatbot
    const responses = {
        "hola": "¡Hola! ¿En qué puedo ayudarte?",
        "adios": "¡Hasta luego! 😊",
        "gracias": "¡De nada! Estoy aquí para ayudarte.",
        "¿como estas?": "¡Estoy aquí para ayudarte, siempre disponible!",
        "¿cual es tu nombre?": "Soy tu asistenta virutal Lamina, siempre lista para ayudarte.",
        "motivame": "Recuerda: 'El éxito es la suma de pequeños esfuerzos repetidos día tras día'. ¡Tú puedes!",
        "me siento triste": "Lamento escuchar eso. Recuerda que no estás solo y que siempre hay una solución. Respira profundo y da un paso a la vez.",
        "default": "Lo siento, no entiendo eso. ¿Puedes intentarlo de otra manera?"
    };

    // Simular respuestas del chatbot
    function chatbotReply(userMessage) {
        const normalizedMessage = normalizeText(userMessage); // Normaliza el mensaje del usuario
        const reply = responses[normalizedMessage] || responses["default"];
        setTimeout(() => addMessage(reply), 500);
    }

    // Enviar mensaje
    sendMessage.click(() => {
        const message = userInput.val().trim();
        if (message) {
            addMessage(message, true);
            userInput.val('');
            chatbotReply(message);
        }
    });

    // Enviar mensaje con Enter
    userInput.keypress(function (e) {
        if (e.which === 13) sendMessage.click();
    });
});

    </script>
@stop