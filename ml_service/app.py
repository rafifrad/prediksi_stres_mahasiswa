from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import numpy as np
import shap
import warnings
warnings.filterwarnings('ignore')

app = Flask(__name__)
CORS(app)  # Enable CORS untuk Laravel

# Load model dan preprocessing
model = None
scaler = None
feature_names = None
explainer = None

try:
    model = joblib.load('models/model_stress_voting.joblib')
    scaler = joblib.load('models/scaler_stress.joblib')
    feature_names = joblib.load('models/feature_names.joblib')
    shap_background = joblib.load('models/shap_background_data.joblib')
    
    # Initialize SHAP explainer
    try:
        explainer = shap.Explainer(model.predict, shap_background)
        print("✅ SHAP explainer loaded!")
    except Exception as shap_error:
        print(f"⚠️ SHAP explainer warning: {str(shap_error)}")
        print("   Continuing without SHAP explanations...")
        explainer = None
    
    print("✅ Model loaded successfully!")
    print(f"📊 Features: {list(feature_names)}")
except Exception as e:
    print(f"❌ Error loading model: {str(e)}")
    model = None

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'status': 'healthy',
        'model_loaded': model is not None,
        'features': list(feature_names) if model is not None else []
    })

@app.route('/predict', methods=['POST'])
def predict():
    """
    Endpoint untuk prediksi stress
    Input JSON format:
    {
        "data": {
            "feature1": value1,
            "feature2": value2,
            ...
        }
    }
    """
    try:
        if model is None:
            return jsonify({
                'error': 'Model not loaded',
                'status': 'error'
            }), 500
        
        # Get input data
        input_data = request.json
        
        if 'data' not in input_data:
            return jsonify({
                'error': 'Missing "data" field in request',
                'status': 'error'
            }), 400
        
        data = input_data['data']
        
        # Convert data to array in correct feature order
        features_array = []
        missing_features = []
        
        for feature in feature_names:
            if feature in data:
                features_array.append(float(data[feature]))
            else:
                missing_features.append(feature)
        
        if missing_features:
            return jsonify({
                'error': f'Missing features: {missing_features}',
                'status': 'error',
                'required_features': list(feature_names)
            }), 400
        
        # Convert to numpy array and reshape
        X = np.array(features_array).reshape(1, -1)
        
        # Scale features
        X_scaled = scaler.transform(X)
        
        # Make prediction
        prediction = model.predict(X_scaled)[0]
        prediction_proba = model.predict_proba(X_scaled)[0]
        
        # Get SHAP values for explanation (if available)
        feature_importance = {}
        top_factors = []
        
        if explainer is not None:
            try:
                shap_values = explainer(X_scaled)
                # Create feature importance from SHAP values
                for idx, feature in enumerate(feature_names):
                    feature_importance[feature] = float(shap_values.values[0][idx])
                
                # Sort features by absolute importance
                sorted_importance = dict(sorted(
                    feature_importance.items(), 
                    key=lambda x: abs(x[1]), 
                    reverse=True
                ))
                top_factors = list(sorted_importance.keys())[:5]
            except Exception as shap_error:
                print(f"⚠️ SHAP calculation error: {str(shap_error)}")
                # Fallback: use feature values as importance
                for idx, feature in enumerate(feature_names):
                    feature_importance[feature] = float(features_array[idx])
                sorted_importance = feature_importance
                top_factors = list(feature_names)[:5]
        else:
            # Fallback when SHAP is not available
            for idx, feature in enumerate(feature_names):
                feature_importance[feature] = float(features_array[idx])
            sorted_importance = feature_importance
            top_factors = list(feature_names)[:5]
        
        # Map prediction to label
        stress_labels = {
            0: 'Low',
            1: 'Medium', 
            2: 'High'
        }
        
        response = {
            'status': 'success',
            'prediction': {
                'class': int(prediction),
                'label': stress_labels.get(int(prediction), 'Unknown'),
                'confidence': float(max(prediction_proba)) * 100,
                'probabilities': {
                    'Low': float(prediction_proba[0]) * 100,
                    'Medium': float(prediction_proba[1]) * 100,
                    'High': float(prediction_proba[2]) * 100
                }
            },
            'explanation': {
                'top_factors': top_factors,
                'feature_importance': sorted_importance
            },
            'input_data': data
        }
        
        return jsonify(response)
        
    except Exception as e:
        return jsonify({
            'error': str(e),
            'status': 'error'
        }), 500

@app.route('/features', methods=['GET'])
def get_features():
    """Get list of required features"""
    if model is None:
        return jsonify({
            'error': 'Model not loaded',
            'status': 'error'
        }), 500
    
    return jsonify({
        'status': 'success',
        'features': list(feature_names),
        'total_features': len(feature_names)
    })

if __name__ == '__main__':
    # Use threaded=True for better stability
    app.run(host='0.0.0.0', port=5000, debug=False, threaded=True)
