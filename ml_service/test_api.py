import requests
import json

# URL Flask API
API_URL = "http://localhost:5000"

def test_health():
    """Test health check endpoint"""
    print("\n🔍 Testing Health Check...")
    response = requests.get(f"{API_URL}/health")
    print(f"Status: {response.status_code}")
    print(f"Response: {json.dumps(response.json(), indent=2)}")

def test_get_features():
    """Test get features endpoint"""
    print("\n🔍 Testing Get Features...")
    response = requests.get(f"{API_URL}/features")
    print(f"Status: {response.status_code}")
    print(f"Response: {json.dumps(response.json(), indent=2)}")
    return response.json()

def test_predict():
    """Test prediction endpoint"""
    print("\n🔍 Testing Prediction...")
    
    # Dapatkan daftar features terlebih dahulu
    features_response = requests.get(f"{API_URL}/features")
    if features_response.status_code == 200:
        features = features_response.json()['features']
        
        # Buat dummy data (sesuaikan dengan features Anda)
        # Contoh: jika features adalah [academic_pressure, sleep_hours, social_support, ...]
        dummy_data = {}
        for feature in features:
            # Berikan nilai dummy (Anda perlu sesuaikan dengan range data asli)
            dummy_data[feature] = 3.5  # Contoh nilai tengah
        
        print(f"\nInput data: {json.dumps(dummy_data, indent=2)}")
        
        # Kirim request prediksi
        response = requests.post(
            f"{API_URL}/predict",
            json={"data": dummy_data},
            headers={"Content-Type": "application/json"}
        )
        
        print(f"\nStatus: {response.status_code}")
        print(f"Response: {json.dumps(response.json(), indent=2)}")
    else:
        print("❌ Gagal mendapatkan daftar features")

if __name__ == "__main__":
    print("=" * 50)
    print("🧪 Testing Flask ML API")
    print("=" * 50)
    
    try:
        test_health()
        test_get_features()
        test_predict()
        
        print("\n" + "=" * 50)
        print("✅ All tests completed!")
        print("=" * 50)
    except requests.exceptions.ConnectionError:
        print("\n❌ Error: Tidak dapat terhubung ke Flask API")
        print("   Pastikan Flask server sudah berjalan: python app.py")
    except Exception as e:
        print(f"\n❌ Error: {str(e)}")
