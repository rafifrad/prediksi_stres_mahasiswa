<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use App\Models\Questionnaire;
use App\Models\StressFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentPredictions = $user->predictions()
            ->with(['questionnaire', 'stressFactors'])
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
            'family_issues' => 'required|integer|min:1|max:5',
            'relationship_status' => 'required|integer|min:1|max:5',
            'study_environment' => 'required|integer|min:1|max:5',
            'future_anxiety' => 'required|integer|min:1|max:5',
        ]);

        // Simple prediction logic (mock ML model)
        $stressLevel = $this->predictStressLevel($validated);
        $confidenceScore = $this->calculateConfidence($validated);
        $stressFactors = $this->analyzeStressFactors($validated);

        // Create prediction
        $prediction = Prediction::create([
            'user_id' => Auth::id(),
            'stress_level' => $stressLevel,
            'confidence_score' => $confidenceScore,
        ]);

        // Create questionnaire
        Questionnaire::create([
            'prediction_id' => $prediction->id,
            ...$validated,
        ]);

        // Create stress factors
        foreach ($stressFactors as $index => $factor) {
            StressFactor::create([
                'prediction_id' => $prediction->id,
                'factor_name' => $factor['name'],
                'importance_score' => $factor['score'],
                'rank' => $index + 1,
            ]);
        }

        return redirect()->route('prediction.result', $prediction->id);
    }

    public function showResult($id)
    {
        $prediction = Prediction::with(['questionnaire', 'stressFactors'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.result', compact('prediction'));
    }

    public function history()
    {
        $predictions = Auth::user()
            ->predictions()
            ->with(['questionnaire', 'stressFactors'])
            ->latest()
            ->paginate(10);

        return view('user.history', compact('predictions'));
    }

    private function predictStressLevel(array $data): string
    {
        // Simple scoring algorithm
        $score = 0;
        $score += (6 - $data['academic_load']) * 2; // Higher load = more stress
        $score += max(0, (7 - $data['sleep_hours'])) * 1.5; // Less sleep = more stress
        $score += (6 - $data['social_support']) * 1.5; // Less support = more stress
        $score += $data['financial_stress'] * 2;
        $score += (6 - $data['time_management']) * 1.5;
        $score += (6 - $data['health_condition']) * 2;
        $score += $data['family_issues'] * 1.5;
        $score += (6 - $data['relationship_status']) * 1;
        $score += (6 - $data['study_environment']) * 1.5;
        $score += $data['future_anxiety'] * 2;

        if ($score >= 50) {
            return 'High';
        } elseif ($score >= 30) {
            return 'Moderate';
        } else {
            return 'Low';
        }
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
            ['name' => 'Kondisi Kesehatan', 'score' => (6 - $data['health_condition']) * 20],
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

