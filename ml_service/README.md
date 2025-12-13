# ML Service untuk Prediksi Stres Mahasiswa

## Setup

1. Install dependencies:
```bash
pip install -r requirements.txt
```

2. Jalankan Flask server:
```bash
python app.py
```

Server akan berjalan di: `http://localhost:5000`

## API Endpoints

### 1. Health Check
```
GET /health
```

### 2. Get Features List
```
GET /features
```

### 3. Predict Stress
```
POST /predict
Content-Type: application/json

{
  "data": {
    "feature1": value1,
    "feature2": value2,
    ...
  }
}
```

Response:
```json
{
  "status": "success",
  "prediction": {
    "class": 2,
    "label": "High",
    "confidence": 85.5,
    "probabilities": {
      "Low": 5.2,
      "Medium": 9.3,
      "High": 85.5
    }
  },
  "explanation": {
    "top_factors": ["feature1", "feature2", ...],
    "feature_importance": {...}
  }
}
```
