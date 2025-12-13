# 🎯 Panduan Integrasi Model ML ke Laravel

## 📋 Struktur File yang Sudah Ada

```
ml_service/
├── models/
│   ├── model_stress_voting.joblib    ✅ Model ML Anda
│   ├── scaler_stress.joblib           ✅ Scaler untuk normalisasi
│   ├── feature_names.joblib           ✅ Nama-nama fitur
│   └── shap_background_data.joblib    ✅ Data untuk SHAP explainability
├── app.py                             ✅ Flask API (BARU)
├── requirements.txt                   ✅ Dependencies Python (BARU)
├── test_api.py                       ✅ Script untuk testing (BARU)
└── README.md                         ✅ Dokumentasi (BARU)
```

## 🚀 Langkah-Langkah Selanjutnya

### 1️⃣ Install Dependencies Python

Buka terminal di folder `ml_service`:

```bash
cd ml_service
pip install -r requirements.txt
```

### 2️⃣ Jalankan Flask API

Masih di folder `ml_service`:

```bash
python app.py
```

Server akan berjalan di: `http://localhost:5000`

Anda akan melihat output:
```
✅ Model loaded successfully!
📊 Features: ['feature1', 'feature2', ...]
 * Running on http://0.0.0.0:5000
```

### 3️⃣ Test Flask API

Di terminal baru, jalankan test script:

```bash
cd ml_service
python test_api.py
```

Ini akan test 3 endpoint:
- ✅ Health check
- ✅ Get features list  
- ✅ Make prediction

### 4️⃣ Integrasikan dengan Laravel

#### A. Update Controller (QuestionnaireController.php)

Tambahkan method untuk submit kuesioner:

```php
use App\Services\StressPredictionService;

public function submit(Request $request, StressPredictionService $mlService)
{
    // Validasi input
    $validated = $request->validate([
        // Sesuaikan dengan features Anda
        'academic_pressure' => 'required|numeric',
        'sleep_hours' => 'required|numeric',
        // ... features lainnya
    ]);
    
    // Panggil ML API
    $prediction = $mlService->predict($validated);
    
    if ($prediction && $prediction['status'] === 'success') {
        // Simpan ke database
        $predictionRecord = auth()->user()->predictions()->create([
            'prediction_result' => $prediction['prediction']['label'],
            'confidence' => $prediction['prediction']['confidence'],
            'probabilities' => json_encode($prediction['prediction']['probabilities']),
            'input_data' => json_encode($validated),
            'explanation' => json_encode($prediction['explanation']),
        ]);
        
        // Dapatkan rekomendasi
        $recommendation = $mlService->getRecommendation(
            $prediction['prediction']['label']
        );
        
        return view('user.prediction-result', [
            'prediction' => $prediction,
            'recommendation' => $recommendation
        ]);
    }
    
    return back()->with('error', 'Gagal melakukan prediksi');
}
```

#### B. Buat View untuk Hasil Prediksi

File: `resources/views/user/prediction-result.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Hasil Prediksi Stres</h2>
        
        <!-- Hasil Prediksi -->
        <div class="mb-6 p-4 rounded-lg {{ $prediction['prediction']['label'] === 'High' ? 'bg-red-100' : ($prediction['prediction']['label'] === 'Medium' ? 'bg-yellow-100' : 'bg-green-100') }}">
            <h3 class="text-xl font-semibold">
                Tingkat Stres: {{ $prediction['prediction']['label'] }}
            </h3>
            <p class="text-gray-700">
                Confidence: {{ number_format($prediction['prediction']['confidence'], 2) }}%
            </p>
        </div>
        
        <!-- Probabilitas -->
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Detail Probabilitas:</h4>
            @foreach($prediction['prediction']['probabilities'] as $level => $prob)
                <div class="mb-2">
                    <div class="flex justify-between mb-1">
                        <span>{{ $level }}</span>
                        <span>{{ number_format($prob, 2) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $prob }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Faktor Utama -->
        <div class="mb-6">
            <h4 class="font-semibold mb-2">Faktor-faktor Utama:</h4>
            <ul class="list-disc list-inside">
                @foreach($prediction['explanation']['top_factors'] as $factor)
                    <li class="text-gray-700">{{ $factor }}</li>
                @endforeach
            </ul>
        </div>
        
        <!-- Rekomendasi -->
        <div class="p-4 bg-blue-50 rounded-lg">
            <h4 class="font-semibold mb-2">{{ $recommendation['message'] }}</h4>
            <ul class="list-disc list-inside space-y-1">
                @foreach($recommendation['tips'] as $tip)
                    <li class="text-gray-700">{{ $tip }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
```

## 🔍 Testing Flow Lengkap

1. **Pastikan MySQL berjalan**
2. **Jalankan Flask API** (Terminal 1):
   ```bash
   cd ml_service
   python app.py
   ```

3. **Jalankan Laravel** (Terminal 2):
   ```bash
   cd prediksi_stres
   php artisan serve
   ```

4. **Akses aplikasi**: `http://localhost:8000`
5. **Login** sebagai user
6. **Isi kuesioner** dengan data sesuai features
7. **Submit** dan lihat hasil prediksi

## 📊 Format Data yang Diperlukan

Pastikan form kuesioner mengirim data dengan nama field yang **sama persis** dengan feature names di model Anda.

Contoh: Jika `feature_names.joblib` berisi:
```python
['academic_pressure', 'sleep_hours', 'social_support', ...]
```

Maka form HTML harus:
```html
<input name="academic_pressure" type="number" step="0.1">
<input name="sleep_hours" type="number" step="0.1">
<input name="social_support" type="number" step="0.1">
```

## ⚠️ Troubleshooting

**Flask API tidak bisa diakses:**
- Cek apakah `python app.py` berjalan tanpa error
- Pastikan port 5000 tidak digunakan aplikasi lain
- Test dengan: `curl http://localhost:5000/health`

**Prediction error:**
- Cek log Laravel: `storage/logs/laravel.log`
- Pastikan semua features terkirim lengkap
- Validasi data sesuai dengan range training data

**CORS error:**
- Sudah dihandle dengan `flask-cors` di Flask API

## 📝 Next Steps

1. ✅ Cek features apa saja yang dibutuhkan model
2. ✅ Sesuaikan form kuesioner dengan features tersebut
3. ✅ Test prediksi dengan data real
4. ✅ Fine-tune UI untuk menampilkan hasil yang lebih informatif

Apakah ada yang ingin ditanyakan atau perlu bantuan lebih lanjut? 🚀
