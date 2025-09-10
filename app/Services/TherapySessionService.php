<?php

namespace App\Services;

use App\Models\TherapySession;
use App\Models\Trial;

class TherapySessionService
{
    /**
     * Рассчитывает процент успеха для занятия
     * @param int $session_id
     * @return float
     */
    public function calculate_success_rate(int $session_id): float
    {
        $total_trials = Trial::where('session_id', $session_id)->count();
        if ($total_trials === 0) {
            return 0.0;
        }

        $successful_trials = Trial::where('session_id', $session_id)
            ->where('is_successful', true)
            ->count();

        return round(($successful_trials / $total_trials) * 100, 2);
    }

    /**
     * Проверяет, достигнут ли критерий перехода на новый уровень
     * (3 занятия подряд с >= 80% успеха, без нежелательного поведения)
     * @param int $session_id
     * @return bool
     */
    public function check_promotion_criteria(int $session_id): bool
    {
        $session = TherapySession::findOrFail($session_id);
        $child_id = $session->child_id;

        // Получаем 3 последних занятия для этого ребенка (включая текущее)
        $recent_session = TherapySession::where('child_id', $child_id)
            ->orderBy('date_time', 'desc')
            ->limit(3)
            ->get();

        if ($recent_session->count() < 3) {
            return false;
        }

        foreach ($recent_session as $sess) {
            $rate = $this->calculate_success_rate($sess->id);
            if ($rate < 80.0) {
                return false;
            }

            // Проверка на нежелательное поведение (если в trials есть behavior_notes - считаем, что было НП)
            $has_bad_behavior = Trial::where('session_id', $sess->id)
                ->whereNotNull('behavior_notes')
                ->exists();

            if ($has_bad_behavior) {
                return false;
            }
        }

        return true;
    }

    /**
     * Генерируем отчет по занятию
     * @param int $session_id
     * @return array
     */
    public function generate_session_report(int $session_id): array
    {
        $session = TherapySession::with('child', 'therapist')->findOrFail($session_id);
        $success_rate = $this->calculate_success_rate($session->id);
        $can_promote = $this->check_promotion_criteria($session->id);

        return [
            'session_id' => $session->id,
            'child_name' => $session->child->full_name,
            'therapist_name' => $session->therapist->name,
            'date_time' => $session->date_time,
            'success_rate' => $success_rate,
            'can_promote' => $can_promote,
            'total_trials' => Trial::where('session_id', $session_id)->count(),
            'successful_trials' => Trial::where('session_id', $session_id)->where('is_successful', true)->count(),
        ];
    }

    /**
     * Завершает занятие, обновляет статус, проверяет критерии
     * @param int $session_id
     * @return void
     */
    public function complete_session(int $session_id): void
    {
        $session = TherapySession::findOrFail($session_id);
        $session->update(['completed_at' => now()]);

        // Логика уведомления куратора, если can_promote = true (Доработать)
        $can_promote = $this->check_promotion_criteria($session->id);

        if ($can_promote) {
            echo "Ребенок {$session->child->full_name} готов к переходу на новый уровень!";
        }
    }
}
