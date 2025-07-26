from flask import Flask, request, jsonify
import joblib
import pandas as pd

app = Flask(__name__)

# Cargar de modelo previamente entrenado
model = joblib.load('personality_model.pkl')

# Recomendaciones basadas en la predicción
recomendaciones = {
    "AP": {
        "Libros": [
            "Fluir de Mihaly Csikszentmihalyi: Explora cómo alcanzar estados de máxima creatividad y concentración.",
            "1984 de George Orwell: Una obra de ciencia ficción que cuestiona el control y la libertad.",
            "Más allá del bien y del mal de Friedrich Nietzsche: Filosofía para cuestionar valores tradicionales."
        ],
        "Película": "Interestelar (2014) - Una exploración sobre lo desconocido y los límites de la humanidad."
    },
    "RE": {
        "Libros": [
            "Los 7 hábitos de la gente altamente efectiva de Stephen Covey: Desarrollo de hábitos positivos y productivos.",
            "El poder de los hábitos de Charles Duhigg: Cómo transformar hábitos para alcanzar metas.",
            "Nunca comas solo de Keith Ferrazzi: Estrategias para networking y organización."
        ],
        "Película": "El método (2005) - Enfocada en la estructura y la toma de decisiones en el ámbito laboral."
    },
    "EX": {
        "Libros": [
            "Cómo ganar amigos e influir sobre las personas de Dale Carnegie: Técnicas para relaciones sociales efectivas.",
            "De cero a uno de Peter Thiel: Innovación en relaciones y liderazgo.",
            "Nunca comas solo de Keith Ferrazzi: Fortalece habilidades de interacción grupal."
        ],
        "Película": "La La Land (2016) - Una celebración de la energía y las conexiones humanas."
    },
    "AM": {
        "Libros": [
            "El poder de la empatía de Roman Krznaric: Desarrollo de la comprensión mutua.",
            "Inteligencia emocional de Daniel Goleman: Cómo mejorar las relaciones personales.",
            "El arte de amar de Erich Fromm: Filosofía del amor y la bondad."
        ],
        "Película": "En busca de la felicidad (2006) - Una historia de generosidad y superación."
    },
    "NE": {
        "Libros": [
            "El fin de la ansiedad de Gio Zararri: Guía práctica para lidiar con la ansiedad.",
            "El poder del ahora de Eckhart Tolle: Técnicas para vivir en el presente y reducir el estrés.",
            "Invicto de Marcos Vázquez: Ejercicios para fortalecer la resiliencia mental."
        ],
        "Película": "Good Will Hunting (1997) - Sobre superar inseguridades y encontrar fortaleza interna."
    }
}

@app.route('/predict', methods=['POST'])
def predict():
    try:
        # Obtener los datos del POST request (esperamos los puntajes AP, RE, EX, AM, NE)
        data = request.get_json()
        print(f"Datos recibidos: {data}")  # Ver para depuración

        # Verificar que los datos contienen los puntajes de las características
        required_columns = ['AP', 'RE', 'EX', 'AM', 'NE']
        for column in required_columns:
            if column not in data:
                return jsonify({"error": f"Falta la columna {column}"}), 400

        # Convertir los datos en un DataFrame (como si fuera una fila)
        input_data = pd.DataFrame([data])

        # Realizar la predicción
        prediction = model.predict(input_data)[0]

        # Obtener las recomendaciones basadas en la predicción
        recomendacion = recomendaciones.get(prediction, {})

        # Devolver la respuesta con la recomendación
        return jsonify({
            "prediccion": prediction,
            "recomendaciones": recomendacion
        })

    except Exception as e:
        print(f"Error: {e}")  # Imprimir el error para depuración
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
