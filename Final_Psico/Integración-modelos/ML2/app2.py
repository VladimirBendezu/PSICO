from flask import Flask, request, jsonify
from flask_cors import CORS
import pandas as pd
import joblib  # Para cargar el modelo
import numpy as np

app = Flask(__name__)
CORS(app)  # Permite CORS para solicitudes desde tu aplicación Laravel

# Carga de modelo entrenado
model = joblib.load('mes_siguiente.pkl')  # Asegúrate de tener el archivo del modelo en la misma carpeta

@app.route('/predict', methods=['POST'])
def predict():
    data = request.json['data']  # Obtén los datos enviados desde Laravel
    df = pd.DataFrame(data)  # Convierte los datos en un DataFrame si es necesario

    # Realiza la predicción
    predictions = model.predict(df)  # Asegúrate de que el modelo y el DataFrame sean compatibles

    return jsonify(predictions.tolist())  # Devuelve las predicciones como lista JSON

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)  # Cambia el puerto si es necesario
