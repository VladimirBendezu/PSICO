import sys
import joblib
import json
import numpy as np
import os

# Obtener ruta absoluta del directorio donde está este script
current_dir = os.path.dirname(os.path.abspath(__file__))

# Construir ruta absoluta al archivo .pkl dentro del mismo directorio
model_path = os.path.join(current_dir, 'phq9_model.pkl')

# Cargar modelo usando la ruta absoluta correcta
model = joblib.load(model_path)

# Leer datos JSON desde argumento
input_json = sys.argv[1]
input_data = json.loads(input_json)

# Obtener características en el orden esperado (p1-p9)
features = [input_data[f'p{i}'] for i in range(1, 10)]
features = np.array(features).reshape(1, -1)

# Hacer predicción
prediction = model.predict(features)[0]

# Resultado
print(prediction)
