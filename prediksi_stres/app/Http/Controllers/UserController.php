<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use App\Models\StressFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentPredictions = $user->predictions()
            ->with('stressFactors')
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
        $validated = $request->validate([
            'academic_load' => 'required|integer|min:1|max:5',
            'sleep_hours' => 'required|integer|min:0|max:24',
            'social_support' => 'required|integer|min:1|max:5',
            'financial_stress' => 'required|integer|min:1|max:5',
            'time_management' => 'required|integer|min:1|max:5',
            'health_condition' => 'required|integer|min:1|max:5',
            'health_condition_2' => 'required|integer|min:1|max:5',
            'family_issues' => 'required|integer|min:1|max:5',
            'relationship_status' => 'required|integer|min:1|max:5',
            'study_environment' => 'required|integer|min:1|max:5',
            'future_anxiety' => 'required|integer|min:1|max:5',
        ]);

        // Map form fields to database columns
        $questionnaireData = [
            'Tekanan_Akademik' => (float)$validated['academic_load'],
            'Kesulitan_Akumulasi' => (float)$validated['financial_stress'],
            'Stres_Tugas_Deadline' => (float)$validated['time_management'],
            'Tekanan_Eksternal' => (float)$validated['social_support'],
            'Kurang_Kendali' => (float)$validated['health_condition'],
            'Rasa_Tidak_Sanggup' => (float)$validated['health_condition_2'],
            'Stres_Pribadi' => (float)$validated['family_issues'],
            'Marah_Eksternal_Studi' => (float)$validated['relationship_status'],
            'Stres_Perubahan_Akademik' => (float)$validated['study_environment'],
            'Tekanan_IPK' => (float)$validated['future_anxiety'],
            'Cemas_Karir' => (float)$validated['future_anxiety'],
            'Kebiasaan_Buruk' => (float)$validated['sleep_hours'] <= 5 ? 5.0 : (7 - $validated['sleep_hours']),
            'Proses_Sesuai_Harapan' => (float)$validated['study_environment'],
        ];

        // Calculate Academic_Stress_Score
        $academicStressScore = (
            $questionnaireData['Tekanan_Akademik'] +
            $questionnaireData['Kesulitan_Akumulasi'] +
            $questionnaireData['Stres_Tugas_Deadline'] +
            $questionnaireData['Tekanan_Eksternal'] +
            $questionnaireData['Stres_Perubahan_Akademik'] +
            $questionnaireData['Tekanan_IPK']
        ) / 6;

        // Calculate Academic_Confidence_Score with inverse for negative factors
        $academicConfidenceScore = (
            $questionnaireData['Proses_Sesuai_Harapan'] + // Positive factor
            (6 - $questionnaireData['Kurang_Kendali']) + // Inversed negative factor
            (6 - $questionnaireData['Rasa_Tidak_Sanggup']) + // Inversed negative factor
            (6 - $questionnaireData['Kebiasaan_Buruk']) + // Inversed negative factor
            (6 - $questionnaireData['Cemas_Karir']) // Inversed negative factor
        ) / 5;

        // Add calculated scores to questionnaire data
        $questionnaireData['Academic_Stress_Score'] = round($academicStressScore, 2);
        $questionnaireData['Academic_Confidence_Score'] = round($academicConfidenceScore, 2);

        // Create prediction
        $prediction = Prediction::create([
            'user_id' => Auth::id(),
            'stress_level' => $this->predictStressLevel($questionnaireData),
            'confidence_score' => $academicConfidenceScore,
        ]);

        // Save to academic_stress_surveys table
        $questionnaireData['prediction_id'] = $prediction->id;
        \App\Models\AcademicStressSurvey::create($questionnaireData);

        // Redirect to prediction result
        return redirect()->route('prediction.result', $prediction->id);
    }

    public function showResult($id)
    {
        $prediction = Prediction::with('stressFactors')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.result', compact('prediction'));
    }

    public function history()
    {
        $predictions = Auth::user()
            ->predictions()
            ->with('stressFactors')
            ->latest()
            ->paginate(10);

        return view('user.history', compact('predictions'));
    }

    private function predictStressLevel(array $data): string
    {
        // Calculate stress level based on Academic_Stress_Score (1-5 scale)
        $stressScore = $data['Academic_Stress_Score'];
        
        // Map to stress levels (enum: 'Low', 'Moderate', 'High')
        if ($stressScore >= 3.5) return 'High';
        if ($stressScore >= 2.0) return 'Moderate';
        return 'Low';
    }

    private function calculateConfidence(array $data): float
    {
        // Simple confidence calculation based on variance
        $values = array_values($data);
        $mean = array_sum($values) / count($values);
        $variance = 0;
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        $variance /= count($values);
        
        // Higher variance = lower confidence
        $confidence = max(60, 100 - ($variance * 5));
        return round($confidence, 2);
    }

    private function analyzeStressFactors(array $data): array
    {
        $factors = [
            ['name' => 'Beban Akademik', 'score' => (6 - $data['academic_load']) * 20],
            ['name' => 'Kurang Tidur', 'score' => max(0, (7 - $data['sleep_hours'])) * 10],
            ['name' => 'Stres Finansial', 'score' => $data['financial_stress'] * 20],
            ['name' => 'Kecemasan Masa Depan', 'score' => $data['future_anxiety'] * 20],
            ['name' => 'Kondisi Kesehatan', 'score' => ((6 - $data['health_condition']) + (6 - $data['health_condition_2'])) * 10],
            ['name' => 'Masalah Keluarga', 'score' => $data['family_issues'] * 15],
            ['name' => 'Manajemen Waktu', 'score' => (6 - $data['time_management']) * 15],
            ['name' => 'Dukungan Sosial', 'score' => (6 - $data['social_support']) * 15],
            ['name' => 'Lingkungan Belajar', 'score' => (6 - $data['study_environment']) * 15],
            ['name' => 'Status Hubungan', 'score' => (6 - $data['relationship_status']) * 10],
        ];

        // Sort by score descending
        usort($factors, fn($a, $b) => $b['score'] <=> $a['score']);

        // Return top 5
        return array_slice($factors, 0, 5);
    }
}

