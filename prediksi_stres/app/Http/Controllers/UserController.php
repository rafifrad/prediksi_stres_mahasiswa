<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use App\Models\StressFactor;
use App\Models\AcademicStressSurvey;
use App\Services\StressPredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentPredictions = $user->predictions()
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('recentPredictions'));
    }

    public function showQuestionnaire()
    {
        return view('user.questionnaire');
    }

    public function submitQuestionnaire(Request $request)
    {
        // Validasi langsung menggunakan nama fitur model ML (13 fitur + 2 calculated)
        $validated = $request->validate([
            'Tekanan_Akademik' => 'required|numeric|min:1|max:5',
            'Kesulitan_Akumulasi' => 'required|numeric|min:1|max:5',
            'Stres_Tugas_Deadline' => 'required|numeric|min:1|max:5',
            'Tekanan_Eksternal' => 'required|numeric|min:1|max:5',
            'Kurang_Kendali' => 'required|numeric|min:1|max:5',
            'Rasa_Tidak_Sanggup' => 'required|numeric|min:1|max:5',
            'Stres_Pribadi' => 'required|numeric|min:1|max:5',
            'Marah_Eksternal_Studi' => 'required|numeric|min:1|max:5',
            'Stres_Perubahan_Akademik' => 'required|numeric|min:1|max:5',
            'Tekanan_IPK' => 'required|numeric|min:1|max:5',
            'Cemas_Karir' => 'required|numeric|min:1|max:5',
            'Kebiasaan_Buruk' => 'required|numeric|min:1|max:5',
            'Proses_Sesuai_Harapan' => 'required|numeric|min:1|max:5',
        ]);

        // Convert ke float
        $questionnaireData = array_map('floatval', $validated);

        // Calculate Academic_Stress_Score (rata-rata dari faktor stres)
        $academicStressScore = (
            $questionnaireData['Tekanan_Akademik'] +
            $questionnaireData['Kesulitan_Akumulasi'] +
            $questionnaireData['Stres_Tugas_Deadline'] +
            $questionnaireData['Tekanan_Eksternal'] +
            $questionnaireData['Stres_Perubahan_Akademik'] +
            $questionnaireData['Tekanan_IPK']
        ) / 6;

        // Calculate Academic_Confidence_Score (inverse dari faktor negatif)
        $academicConfidenceScore = (
            $questionnaireData['Proses_Sesuai_Harapan'] + 
            (6 - $questionnaireData['Kurang_Kendali']) + 
            (6 - $questionnaireData['Rasa_Tidak_Sanggup']) + 
            (6 - $questionnaireData['Kebiasaan_Buruk']) +
            (6 - $questionnaireData['Cemas_Karir'])
        ) / 5;

        // Add calculated scores
        $questionnaireData['Academic_Stress_Score'] = round($academicStressScore, 2);
        $questionnaireData['Academic_Confidence_Score'] = round($academicConfidenceScore, 2);

        try {
            // Call ML API untuk prediksi
            $predictionService = new StressPredictionService();
            
            // Log data yang dikirim
            Log::info('Sending data to ML API', $questionnaireData);
            
            // Check API health
            $healthCheck = $predictionService->healthCheck();
            if (!$healthCheck['status']) {
                Log::error('ML API is not available', $healthCheck);
                return back()->with('error', 'Layanan prediksi sedang tidak tersedia. Silakan coba lagi nanti.');
            }

            // Call predict API
            $mlResult = $predictionService->predict($questionnaireData);
            
            Log::info('ML API Response', $mlResult);
            
            if (!$mlResult || $mlResult['status'] !== 'success') {
                Log::error('ML prediction failed', $mlResult ?? ['error' => 'No response']);
                return back()->with('error', 'Gagal melakukan prediksi: ' . ($mlResult['error'] ?? 'Unknown error'));
            }

            // Create prediction dengan hasil dari ML model
            $prediction = Prediction::create([
                'user_id' => Auth::id(),
                'stress_level' => $mlResult['prediction']['label'], // Low, Medium, High dari model
                'confidence_score' => $mlResult['prediction']['confidence'],
            ]);

            // Save to academic_stress_surveys table
            $questionnaireData['prediction_id'] = $prediction->id;
            AcademicStressSurvey::create($questionnaireData);

            // Store ML result details in session untuk ditampilkan di result page
            session([
                'ml_result' => [
                    'prediction' => $mlResult['prediction']['label'],
                    'confidence' => $mlResult['prediction']['confidence'],
                    'probabilities' => $mlResult['prediction']['probabilities'] ?? null,
                    'top_factors' => $mlResult['explanation']['top_factors'] ?? [],
                    'feature_importance' => $mlResult['explanation']['feature_importance'] ?? null,
                ]
            ]);

            // Redirect to prediction result
            return redirect()->route('prediction.result', $prediction->id)
                ->with('success', 'Prediksi berhasil dilakukan!');

        } catch (\Exception $e) {
            Log::error('Error during prediction', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat memproses prediksi. Silakan coba lagi.');
        }
    }

    public function showResult($id)
    {
        $prediction = Prediction::with('academicStressSurvey')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Get ML result from session
        $mlResult = session('ml_result');

        return view('user.result', compact('prediction', 'mlResult'));
    }

    public function history()
    {
        $predictions = Auth::user()
            ->predictions()
            ->with('academicStressSurvey')
            ->latest()
            ->paginate(10);

        return view('user.history', compact('predictions'));
    }
}

