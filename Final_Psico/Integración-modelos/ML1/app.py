from flask import Flask, request, jsonify

app = Flask(__name__)

# Modelo predictivo
def predict_priority(data):
    # Determinación de los datos
    if data.get('stress_level') == 'Alto' or data.get('self_harm') == 'Sí' or data.get('suicidal_thoughts') == 'Sí':
        return 'ALTA'
    else:
        return 'BAJA'

# Ruta para recibir los datos y hacer la predicción
@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()  # Obtener los datos JSON de la solicitud
    prediction = predict_priority(data)  # Realizar la predicción

    # Imprimir la predicción en la terminal
    print(f"Predicción realizada: {prediction}")

    # Retornar la respuesta como JSON
    return jsonify({'prioridad': prediction})

if __name__ == '__main__':
    app.run(debug=True)
