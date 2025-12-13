import joblib
import os

print("=" * 60)
print("📊 Model Information")
print("=" * 60)

# Load feature names
try:
    feature_names = joblib.load('models/feature_names.joblib')
    print(f"\n✅ Total Features: {len(feature_names)}")
    print("\n📝 Feature Names:")
    print("-" * 60)
    for i, feature in enumerate(feature_names, 1):
        print(f"{i:2d}. {feature}")
    
    print("\n" + "=" * 60)
    print("💡 Gunakan nama-nama feature ini untuk:")
    print("   1. Form kuesioner di Laravel")
    print("   2. Request ke Flask API")
    print("=" * 60)
    
    # Generate example data
    print("\n📋 Contoh Data Request:")
    print("-" * 60)
    print("{")
    print('  "data": {')
    for i, feature in enumerate(feature_names):
        comma = "," if i < len(feature_names) - 1 else ""
        print(f'    "{feature}": 3.5{comma}  // Ganti dengan nilai sebenarnya')
    print("  }")
    print("}")
    
except FileNotFoundError:
    print("❌ Error: models/feature_names.joblib tidak ditemukan!")
    print("   Pastikan file ada di folder models/")
except Exception as e:
    print(f"❌ Error: {str(e)}")
