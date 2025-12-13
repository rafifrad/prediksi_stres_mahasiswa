import joblib
import numpy as np

print("=" * 60)
print("🔍 Analyzing Model Components")
print("=" * 60)

# Load all components
scaler = joblib.load('models/scaler_stress.joblib')
feature_names = joblib.load('models/feature_names.joblib')
model = joblib.load('models/model_stress_voting.joblib')

print(f"\n📊 Feature Names Count: {len(feature_names)}")
print(f"   {feature_names}\n")

print(f"📐 Scaler Expected Features: {scaler.n_features_in_}")
if hasattr(scaler, 'feature_names_in_'):
    print(f"   Scaler Feature Names: {scaler.feature_names_in_}")

print(f"\n🤖 Model Type: {type(model).__name__}")
if hasattr(model, 'n_features_in_'):
    print(f"   Model Expected Features: {model.n_features_in_}")

# Try to get scaler mean and scale info
if hasattr(scaler, 'mean_'):
    print(f"\n📏 Scaler dimensions:")
    print(f"   Mean shape: {scaler.mean_.shape}")
    print(f"   Scale shape: {scaler.scale_.shape}")

print("\n" + "=" * 60)
print("⚠️  ISSUE DETECTED:")
print(f"   Feature names: {len(feature_names)} features")
print(f"   Scaler expects: {scaler.n_features_in_} features")
print("   → Mismatch! Need to use correct feature names")
print("=" * 60)
