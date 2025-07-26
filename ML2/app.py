from flask import Flask, request, jsonify
import joblib
import numpy as np
import random
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

# Cargar el modelo y el scaler
model = joblib.load('best_personality_model.pkl')
scaler = joblib.load('scaler.pkl')

# Nombres de los rasgos en orden
TRAITS = ['Apertura', 'Responsabilidad', 'Extroversión', 'Amabilidad', 'Neuroticismo']

# Recomendaciones por rasgo
RECOMMENDATIONS = {
    "apertura": {
        "libros": [
            "Big Magic – Elizabeth Gilbert",
            "El poder del ahora – Eckhart Tolle",
            "La historia interminable – Michael Ende",
            "Zen y el arte del mantenimiento de la motocicleta – Robert M. Pirsig",
            "Siddhartha – Hermann Hesse",
            "La insoportable levedad del ser – Milan Kundera",
            "Kafka en la orilla – Haruki Murakami",
            "El alquimista – Paulo Coelho",
            "Homo Deus – Yuval Noah Harari",
            "Una habitación propia – Virginia Woolf",
            "Rayuela – Julio Cortázar",
        ],
        "peliculas": [
            "Inception",
            "Amélie",
            "La sociedad de los poetas muertos",
            "Comer, rezar, amar",
            "El árbol de la vida",
            "Cloud Atlas",
            "Her",
            "Into the Wild",
            "El show de Truman",
            "Big Fish",
            "Boyhood",
        ],
        "canciones": [
            "Imagine – John Lennon",
            "Across the Universe – The Beatles",
            "Space Oddity – David Bowie",
            "Bohemian Rhapsody – Queen",
            "Tomorrow Never Knows – The Beatles",
            "Dreams – Fleetwood Mac",
            "Starman – David Bowie",
            "Strawberry Fields Forever – The Beatles",
            "Pure Imagination – Gene Wilder",
            "Come Away With Me – Norah Jones",
            "Unwritten – Natasha Bedingfield",
        ],
    },
    "responsabilidad": {
        "libros": [
            "Los 7 hábitos de la gente altamente efectiva – Stephen R. Covey",
            "Hábitos atómicos – James Clear",
            "El club de las 5 de la mañana – Robin Sharma",
            "Deep Work – Cal Newport",
            "Organízate con eficacia – David Allen",
            "Esencialismo – Greg McKeown",
            "El monje que vendió su Ferrari – Robin Sharma",
            "Grit – Angela Duckworth",
            "Mindset – Carol Dweck",
            "La semana laboral de 4 horas – Tim Ferriss",
            "La magia del orden – Marie Kondo",
        ],
        "peliculas": [
            "En busca de la felicidad",
            "Julie & Julia",
            "El discurso del rey",
            "La teoría del todo",
            "Moneyball",
            "Temple Grandin",
            "El fundador",
            "Erin Brockovich",
            "Coco antes de Chanel",
            "Una mente maravillosa",
            "Coach Carter",
        ],
        "canciones": [
            "Eye of the Tiger – Survivor",
            "Stronger – Kanye West",
            "Don't Stop Believin' – Journey",
            "Hall of Fame – The Script ft. will.i.am",
            "Titanium – David Guetta ft. Sia",
            "Work – Rihanna",
            "Lose Yourself – Eminem",
            "Unstoppable – Sia",
            "Roar – Katy Perry",
            "The Climb – Miley Cyrus",
            "Start Me Up – The Rolling Stones",
        ],
    },
    "extroversión": {
        "libros": [
            "Cómo ganar amigos e influir sobre las personas – Dale Carnegie",
            "El lenguaje corporal – Allan & Barbara Pease",
            "The Charisma Myth – Olivia Fox Cabane",
            "Talk Like TED – Carmine Gallo",
            "The Art of Public Speaking – Stephen Lucas",
            "Quiet (Silencio) – Susan Cain (contrapunto útil)",
            "Dare to Lead – Brené Brown",
            "Blink – Malcolm Gladwell",
            "The Tipping Point – Malcolm Gladwell",
            "You Just Don't Understand – Deborah Tannen",
            "Presence – Amy Cuddy",
        ],
        "peliculas": [
            "La red social",
            "Yes Man",
            "Los becarios (The Internship)",
            "El diablo viste a la moda",
            "Legally Blonde",
            "School of Rock",
            "Mamma Mia!",
            "The Greatest Showman",
            "Catch Me If You Can",
            "The Wolf of Wall Street",
            "Birdman",
        ],
        "canciones": [
            "Happy – Pharrell Williams",
            "Can't Stop the Feeling – Justin Timberlake",
            "Don't Stop Me Now – Queen",
            "Feel This Moment – Pitbull ft. Christina Aguilera",
            "On Top of the World – Imagine Dragons",
            "Uptown Funk – Bruno Mars",
            "I Gotta Feeling – Black Eyed Peas",
            "Wake Me Up – Avicii",
            "Levels – Avicii",
            "Party Rock Anthem – LMFAO",
            "Dynamite – BTS",
        ],
    },
    "amabilidad": {
        "libros": [
            "El arte de amar – Erich Fromm",
            "Los cuatro acuerdos – Don Miguel Ruiz",
            "La inteligencia emocional – Daniel Goleman",
            "El poder de la empatía – Roman Krznaric",
            "Amar lo que es – Byron Katie",
            "Cuando digo no, me siento culpable – Manuel J. Smith",
            "Radical Compassion – Tara Brach",
            "The Empath's Survival Guide – Judith Orloff",
            "Boundaries – Dr. Henry Cloud",
            "The Gifts of Imperfection – Brené Brown",
            "Nonviolent Communication – Marshall Rosenberg",
        ],
        "peliculas": [
            "Cadena de favores",
            "Intensamente",
            "La vida es bella",
            "Wonder",
            "Un sueño posible (The Blind Side)",
            "La terminal",
            "Forrest Gump",
            "Extraordinario",
            "Marley y yo",
            "El niño con el pijama de rayas",
            "El buen Samaritano",
        ],
        "canciones": [
            "Heal the World – Michael Jackson",
            "What a Wonderful World – Louis Armstrong",
            "Count on Me – Bruno Mars",
            "You've Got a Friend – James Taylor",
            "One Love – Bob Marley",
            "True Colors – Cyndi Lauper",
            "Fix You – Coldplay",
            "Lean on Me – Bill Withers",
            "I'll Be There for You – The Rembrandts",
            "Someone Like You – Adele",
            "Bridge Over Troubled Water – Simon & Garfunkel",
        ],
    },
    "neuroticismo": {
        "libros": [
            "El cerebro ansioso – Pittman y Karle",
            "Mindfulness para reducir el estrés – Jon Kabat-Zinn",
            "Cuando todo se derrumba – Pema Chödrön",
            "Ansiedad – Scott Stossel",
            "Los engaños de la mente – Elsa Punset",
            "La trampa de la felicidad – Russ Harris",
            "Ya no seas codependiente – Melody Beattie",
            "Amar lo que es – Byron Katie",
            "El poder de la hora – Eckhart Tolle",
            "La rueda de la vida – Elisabeth Kübler-Ross",
            "Curación emocional – David Servan-Schreiber",
        ],
        "peliculas": [
            "Una mente brillante",
            "Las ventajas de ser invisible",
            "El lado bueno de las cosas (Silver Linings Playbook)",
            "Girl, Interrupted",
            "El club de la pelea",
            "The Perfection",
            "Bajo la misma estrella",
            "Frances Ha",
            "Melancholia",
            "Black Swan",
            "Inside Out",
        ],
        "canciones": [
            "Let It Be – The Beatles",
            "Fix You – Coldplay",
            "1-800-273-8255 – Logic ft. Alessia Cara, Khalid",
            "Demons – Imagine Dragons",
            "Breathe Me – Sia",
            "The A Team – Ed Sheeran",
            "Hurt – Johnny Cash",
            "Everybody Hurts – R.E.M.",
            "Creep – Radiohead",
            "In My Blood – Shawn Mendes",
            "Skyscraper – Demi Lovato",
        ],
    },
}

@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.get_json()

        if not data:
            return jsonify({"error": "No se recibió JSON válido"}), 400

        keys = [
            'ap_1', 'ap_2', 'ap_3', 'ap_4', 'ap_5',
            're_1', 're_2', 're_3', 're_4', 're_5',
            'ex_1', 'ex_2', 'ex_3', 'ex_4', 'ex_5',
            'am_1', 'am_2', 'am_3', 'am_4', 'am_5',
            'ne_1', 'ne_2', 'ne_3', 'ne_4', 'ne_5'
        ]

        if not all(k in data for k in keys):
            return jsonify({"error": "Faltan campos en el JSON enviado"}), 400

        input_data = [data[k] for k in keys]
        input_scaled = scaler.transform([input_data])
        predictions = model.predict(input_scaled)[0]

        results = {}
        trait_mapping = {
            'Apertura': 'apertura',
            'Responsabilidad': 'responsabilidad', 
            'Extroversión': 'extroversión',
            'Amabilidad': 'amabilidad',
            'Neuroticismo': 'neuroticismo'
        }

        for i, trait in enumerate(TRAITS):
            trait_key = trait_mapping.get(trait, trait.lower())
            score = float(max(1, min(5, round(predictions[i], 2))))
            rec = RECOMMENDATIONS.get(trait_key, {})

            results[trait] = {
                "score": score,
                "recomendaciones": {
                    "libro": random.choice(rec.get("libros", ["No disponible"])) if rec.get("libros") else "No disponible",
                    "pelicula": random.choice(rec.get("peliculas", ["No disponible"])) if rec.get("peliculas") else "No disponible",
                    "cancion": random.choice(rec.get("canciones", ["No disponible"])) if rec.get("canciones") else "No disponible"
                }
            }

        dominant_trait = TRAITS[np.argmax(predictions)]
        
        # Convertir numpy arrays a listas python para JSON serialization
        puntuaciones = {}
        for i, trait in enumerate(TRAITS):
            puntuaciones[trait] = float(predictions[i])

        response_data = {
            "perfil_completo": results,
            "rasgo_dominante": dominant_trait,
            "puntuaciones": puntuaciones
        }

        return jsonify(response_data)

    except Exception as e:
        print(f"Error en predict: {str(e)}")
        import traceback
        traceback.print_exc()
        return jsonify({"error": f"Error interno del servidor: {str(e)}"}), 500

if __name__ == '__main__':
    app.run(debug=True, host='127.0.0.1', port=5000)