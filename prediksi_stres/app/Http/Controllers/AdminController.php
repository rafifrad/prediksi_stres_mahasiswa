<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalPredictions = Prediction::count();
        $recentPredictions = Prediction::with(['user', 'questionnaire', 'stressFactors'])
            ->latest()
            ->take(10)
            ->get();

        $stressDistribution = [
            'Low' => Prediction::where('stress_level', 'Low')->count(),
            'Moderate' => Prediction::where('stress_level', 'Moderate')->count(),
            'High' => Prediction::where('stress_level', 'High')->count(),
        ];

        return view('admin.dashboard', compact('totalUsers', 'totalPredictions', 'recentPredictions', 'stressDistribution'));
    }

    public function users(Request $request)
    {
        $query = User::where('role', 'user')->withCount('predictions');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function userDetail($id)
    {
        $user = User::with(['predictions.questionnaire', 'predictions.stressFactors'])
            ->findOrFail($id);

        return view('admin.user-detail', compact('user'));
    }

    public function predictions(Request $request)
    {
        $query = Prediction::with(['user', 'questionnaire', 'stressFactors']);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('stress_level')) {
            $query->where('stress_level', $request->stress_level);
        }

        $predictions = $query->latest()->paginate(15);

        return view('admin.predictions', compact('predictions'));
    }

    public function predictionDetail($id)
    {
        $prediction = Prediction::with(['user', 'questionnaire', 'stressFactors'])
            ->findOrFail($id);

        return view('admin.prediction-detail', compact('prediction'));
    }
}

