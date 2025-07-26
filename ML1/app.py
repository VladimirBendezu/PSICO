from fastapi import FastAPI
from pydantic import BaseModel
import joblib
import numpy as np
from fastapi.middleware.cors import CORSMiddleware  # Importar CORS

app = FastAPI()

# Configurar CORS (Permitir solicitudes desde Laravel)
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # En producción, reemplaza "*" con la URL de tu frontend
    allow_methods=["POST", "GET"],  # Métodos permitidos
    allow_headers=["*"],  # Cabeceras permitidas
)

# Cargar el modelo
model = joblib.load('phq9_model.pkl')

class PHQ9Test(BaseModel):
    p1: int
    p2: int
    p3: int
    p4: int
    p5: int
    p6: int
    p7: int
    p8: int
    p9: int

@app.post("/predict")
def predict(test: PHQ9Test):
    features = np.array([[
        test.p1, test.p2, test.p3, test.p4, test.p5, test.p6, test.p7, test.p8, test.p9
    ]])
    total_score = int(np.sum(features))
    return {"score": total_score}

# Ruta de prueba para debug
@app.get("/test")
def test():
    return {"status": "API funcionando", "message": "¡Conectado correctamente!"}