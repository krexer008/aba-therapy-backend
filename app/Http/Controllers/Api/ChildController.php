<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\TherapySession;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    /**
     * Получить список детей, прикрепленных к текущему пользователю
     * GET /api/children
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Если пользователь - терапист, показываем только его детей
        if ($user->role === 'therapist') {
            $children = Child::whereHas('therapy_sessions', function ($query) use ($user) {
                $query->where('therapist_id', $user->id);
            })->with('branch')->get();
        } else {
            // Для кураторов и родителей - пока всех (в будущем доработаем)
            $children = Child::with('branch')->get();
        }

        return response()->json($children);
    }

    /**
     * Получить профиль ребенка
     * GET /api/children/{id}
     */
    public function show($id)
    {
        $child = Child::with([
            'branch',
            'preferences',
            'parents' => function ($query) {
                $query->select('users.id', 'users.name', 'users.email', 'child_parents.relation');
            }
        ])->findOrFail($id);

        return response()->json($child);
    }

    /**
     * Получить прогресс ребенка по навыкам
     * GET /api/children/{id}/progress
     */
    public function progress($id)
    {
        // Заглушка - будущем реализуем расчет прогресса
        return response()->json([
            'child_id' => $id,
            'skills_progress' => [
                ['skill' => 'Мелкая моторика', 'progress_percent' => 75],
                ['skill' => 'Речь', 'progress_percent' => 40],
            ]
        ]);
    }

    /**
     * Получить историю занятий ребенка
     * GET /api/children/{id}/sessions
     */
    public function esssions($id)
    {
        $sessions = TherapySession::where('child_id', $id)->get()
            ->with('therapist', 'room')
            ->orderBy('date_time', 'desc')
            ->get();
    }
}
