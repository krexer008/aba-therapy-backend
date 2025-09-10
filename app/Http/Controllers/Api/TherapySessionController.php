<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StartTherapySessionRequest;
use App\Services\TherapySessionService;
use Illuminate\Http\Request;

class TherapySessionController extends Controller
{
    protected $therapy_session_service;

    public function __construct(TherapySessionService $therapy_session_service)
    {
        $this->therapy_session_service = $therapy_session_service;
    }

    /**
     * Начать новое занятие
     * POST /api/sessions/start
     */
    public function start_session(StartTherapySessionRequest $request)
    {
        $session = \App\Models\TherapySession::create($request->validated());

        return response()->json([
            'message' => 'Занятие начато',
            'session_id' => $session->id,
        ], 201);
    }

    /**
     * Завершить занятие
     * POST /api/sessions/{id}/complete
     */
    public function complete_session($id)
    {
        $this->therapy_session_service->complete_session($id);

        $report = $this->therapy_session_service->generate_session_report($id);

        return response()->json([
            'message' => 'Занятие завершено',
            'report' => $report,
        ]);
    }

    /**
     * Получить отчет по занятию
     * GET /api/sessions/{id}/report
     */
    public function get_session_report($id)
    {
        $report = $this->therapy_session_service->generate_session_report($id);
        return response()->json($report);
    }
}
