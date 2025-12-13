import requests
import json

# URL Flask API
API_URL = "http://localhost:5000"

# Daftar features yang dibutuhkan
FEATURES = [
    "Academic_Stress_Score",
    "Tekanan_Akademik",
    "Kesulitan_Akumulasi",
    "Stres_Tugas_Deadline",
    "Tekanan_Eksternal",
    "Kurang_Kendali",
    "Rasa_Tidak_Sanggup",
    "Stres_Pribadi",
    "Marah_Eksternal_Studi",
    "Stres_Perubahan_Akademik",
    "Tekanan_IPK",
    "Cemas_Karir",
    "Kebiasaan_Buruk",
    "Proses_Sesuai_Harapan",
    "Academic_Confidence_Score"
]

# Deskripsi untuk setiap feature (opsional, untuk membantu user)
FEATURE_DESCRIPTIONS = {
    "Academic_Stress_Score": "Skor stres akademik keseluruhan (1-5)",
    "Tekanan_Akademik": "Tingkat tekanan dari beban akademik (1-5)",
    "Kesulitan_Akumulasi": "Akumulasi kesulitan yang dihadapi (1-5)",
    "Stres_Tugas_Deadline": "Stres dari tugas dan deadline (1-5)",
    "Tekanan_Eksternal": "Tekanan dari faktor eksternal (1-5)",
    "Kurang_Kendali": "Perasaan kurang kendali atas situasi (1-5)",
    "Rasa_Tidak_Sanggup": "Perasaan tidak sanggup menghadapi masalah (1-5)",
    "Stres_Pribadi": "Tingkat stres pribadi (1-5)",
    "Marah_Eksternal_Studi": "Kemarahan terhadap faktor eksternal studi (1-5)",
    "Stres_Perubahan_Akademik": "Stres dari perubahan akademik (1-5)",
    "Tekanan_IPK": "Tekanan untuk mencapai IPK tertentu (1-5)",
    "Cemas_Karir": "Kecemasan tentang karir masa depan (1-5)",
    "Kebiasaan_Buruk": "Kebiasaan buruk yang mempengaruhi studi (1-5)",
    "Proses_Sesuai_Harapan": "Apakah proses belajar sesuai harapan (1-5)",
    "Academic_Confidence_Score": "Skor kepercayaan diri akademik (1-5)"
}

def check_api_connection():
    """Cek apakah Flask API berjalan"""
    try:
        response = requests.get(f"{API_URL}/health", timeout=5)
        if response.status_code == 200:
            return True
        return False
    except:
        return False

def get_user_input():
    """Dapatkan input dari user untuk semua features"""
    print("\n" + "=" * 70)
    print("📝 INPUT DATA UNTUK PREDIKSI STRES")
    print("=" * 70)
    print("\nSilakan masukkan nilai untuk setiap faktor (1-5):")
    print("1 = Sangat Rendah, 2 = Rendah, 3 = Sedang, 4 = Tinggi, 5 = Sangat Tinggi")
    print("-" * 70)
    
    data = {}
    
    for i, feature in enumerate(FEATURES, 1):
        while True:
            try:
                description = FEATURE_DESCRIPTIONS.get(feature, feature)
                prompt = f"\n{i:2d}. {description}\n    Nilai: "
                value = float(input(prompt))
                
                if 1 <= value <= 5:
                    data[feature] = value
                    break
                else:
                    print("    ⚠️  Nilai harus antara 1 dan 5!")
            except ValueError:
                print("    ⚠️  Input tidak valid! Masukkan angka 1-5.")
            except KeyboardInterrupt:
                print("\n\n❌ Input dibatalkan.")
                return None
    
    return data

def predict_stress(data):
    """Kirim data ke Flask API untuk prediksi"""
    max_retries = 3
    retry_delay = 2  # seconds
    
    for attempt in range(max_retries):
        try:
            response = requests.post(
                f"{API_URL}/predict",
                json={"data": data},
                headers={"Content-Type": "application/json"},
                timeout=30
            )
            
            if response.status_code == 200:
                return response.json()
            else:
                print(f"\n❌ Error: {response.status_code}")
                print(response.text)
                return None
                
        except requests.exceptions.ConnectionError as e:
            if attempt < max_retries - 1:
                print(f"\n⚠️  Koneksi terputus. Mencoba lagi ({attempt + 1}/{max_retries})...")
                import time
                time.sleep(retry_delay)
            else:
                print(f"\n❌ Error: Tidak dapat terhubung ke Flask API setelah {max_retries} percobaan")
                print("   Pastikan Flask server masih berjalan: python app.py")
                return None
                
        except requests.exceptions.Timeout:
            print(f"\n❌ Error: Request timeout (>30 detik)")
            return None
            
        except Exception as e:
            print(f"\n❌ Error tidak diketahui: {str(e)}")
            return None
    
    return None

def display_result(result):
    """Tampilkan hasil prediksi dengan format yang bagus"""
    if not result or result.get('status') != 'success':
        print("\n❌ Gagal mendapatkan hasil prediksi")
        return
    
    prediction = result['prediction']
    explanation = result['explanation']
    
    print("\n" + "=" * 70)
    print("🎯 HASIL PREDIKSI TINGKAT STRES")
    print("=" * 70)
    
    # Tingkat Stres
    label = prediction['label']
    confidence = prediction['confidence']
    
    # Warna berdasarkan tingkat stres
    if label == 'Low':
        emoji = "✅"
        color_msg = "RENDAH"
    elif label == 'Medium':
        emoji = "⚠️"
        color_msg = "SEDANG"
    else:
        emoji = "🚨"
        color_msg = "TINGGI"
    
    print(f"\n{emoji} Tingkat Stres: {color_msg}")
    print(f"   Confidence: {confidence:.2f}%")
    
    # Probabilitas Detail
    print("\n📊 Detail Probabilitas:")
    probs = prediction['probabilities']
    for level, prob in probs.items():
        bar_length = int(prob / 2)  # Scale to max 50 chars
        bar = "█" * bar_length
        print(f"   {level:8s}: {bar:50s} {prob:5.2f}%")
    
    # Top 5 Faktor
    print("\n🔍 5 Faktor Paling Berpengaruh:")
    for i, factor in enumerate(explanation['top_factors'], 1):
        importance = explanation['feature_importance'][factor]
        arrow = "↑" if importance > 0 else "↓"
        print(f"   {i}. {factor:30s} {arrow} {abs(importance):.4f}")
    
    # Rekomendasi berdasarkan tingkat stres
    print("\n💡 Rekomendasi:")
    if label == 'Low':
        print("   ✅ Tingkat stres Anda rendah. Pertahankan kondisi ini!")
        print("   - Tetap jaga pola tidur yang teratur")
        print("   - Lanjutkan aktivitas fisik rutin")
        print("   - Pertahankan pola makan sehat")
    elif label == 'Medium':
        print("   ⚠️  Tingkat stres Anda sedang. Perhatikan hal berikut:")
        print("   - Atur waktu belajar dengan baik")
        print("   - Istirahat cukup minimal 7-8 jam per hari")
        print("   - Lakukan aktivitas relaksasi")
        print("   - Berbagi cerita dengan teman atau keluarga")
    else:
        print("   🚨 Tingkat stres Anda tinggi. Segera ambil tindakan!")
        print("   - Konsultasi dengan psikolog atau konselor kampus")
        print("   - Atur ulang prioritas dan deadline")
        print("   - Lakukan teknik pernapasan dalam")
        print("   - Olahraga ringan secara teratur")
        print("   - Cari dukungan dari orang terdekat")
    
    print("\n" + "=" * 70)

def save_result_to_file(data, result):
    """Simpan hasil ke file JSON"""
    try:
        import datetime
        timestamp = datetime.datetime.now().strftime("%Y%m%d_%H%M%S")
        filename = f"prediction_result_{timestamp}.json"
        
        output = {
            'timestamp': datetime.datetime.now().isoformat(),
            'input_data': data,
            'prediction_result': result
        }
        
        with open(filename, 'w', encoding='utf-8') as f:
            json.dump(output, f, indent=2, ensure_ascii=False)
        
        print(f"\n💾 Hasil disimpan ke: {filename}")
        return True
    except Exception as e:
        print(f"\n⚠️  Gagal menyimpan hasil: {str(e)}")
        return False

def main():
    """Main function"""
    print("\n" + "=" * 70)
    print("🎓 SISTEM PREDIKSI TINGKAT STRES MAHASISWA")
    print("=" * 70)
    
    # Cek koneksi API
    print("\n🔍 Mengecek koneksi ke ML API...")
    if not check_api_connection():
        print("❌ Error: Flask API tidak berjalan!")
        print("   Pastikan Flask server sudah dijalankan dengan: python app.py")
        return
    
    print("✅ Koneksi ke ML API berhasil!")
    
    # Loop untuk multiple predictions
    while True:
        # Get user input
        data = get_user_input()
        
        if data is None:
            break
        
        # Konfirmasi data
        print("\n" + "=" * 70)
        print("📋 RINGKASAN DATA INPUT")
        print("=" * 70)
        for feature, value in data.items():
            print(f"   {feature:35s}: {value}")
        
        confirm = input("\n❓ Lanjutkan prediksi? (y/n): ").lower()
        if confirm != 'y':
            print("   Prediksi dibatalkan.")
            continue
        
        # Predict
        print("\n🔮 Sedang melakukan prediksi...")
        result = predict_stress(data)
        
        if result:
            display_result(result)
            
            # Tanya apakah mau save
            save = input("\n❓ Simpan hasil ke file? (y/n): ").lower()
            if save == 'y':
                save_result_to_file(data, result)
        
        # Tanya apakah mau prediksi lagi
        print("\n" + "-" * 70)
        again = input("❓ Prediksi lagi? (y/n): ").lower()
        if again != 'y':
            break
    
    print("\n" + "=" * 70)
    print("👋 Terima kasih telah menggunakan sistem prediksi!")
    print("=" * 70 + "\n")

if __name__ == "__main__":
    try:
        main()
    except KeyboardInterrupt:
        print("\n\n👋 Program dihentikan.\n")
    except Exception as e:
        print(f"\n❌ Error: {str(e)}\n")
