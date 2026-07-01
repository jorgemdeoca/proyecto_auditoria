<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AuthLog;
use App\Models\ReadAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditoriaController extends Controller
{
    // ── Helpers ──────────────────────────────────────────────────────────────

    /** Devuelve los IDs de consultorios que puede ver el admin actual. Null = todos. */
    private function getConsultorioIds(): ?array
    {
        $admin = Auth::user()->administrador;
        if (!$admin || $admin->tipo_admin === 'Root') {
            return null; // Root ve todo
        }
        return $admin->consultorios->pluck('id')->toArray();
    }

    /** Aplica filtro de consultorio a un query de AuditLog sobre citas. */
    private function aplicarFiltroConsultorio($query, ?array $ids)
    {
        if ($ids === null) return $query;
        return $query->whereIn('auditable_type', ['App\\Models\\Cita'])
            ->whereIn('auditable_id', function ($sub) use ($ids) {
                $sub->select('id')->from('citas')->whereIn('consultorio_id', $ids);
            });
    }

    /** Aplica filtros comunes de fecha a un query. */
    private function aplicarFiltrosFecha($query, Request $request): mixed
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        if ($desde && $hasta) {
            $query->enRango($desde, $hasta);
        } elseif ($desde) {
            $query->whereDate('created_at', '>=', $desde);
        } elseif ($hasta) {
            $query->whereDate('created_at', '<=', $hasta);
        }
        return $query;
    }

    // ── Index / Dashboard ─────────────────────────────────────────────────────

    public function index()
    {
        $consultorioIds = $this->getConsultorioIds();
        $isRoot         = $consultorioIds === null;

        // KPIs
        $totalEventosCitas = AuditLog::delModulo('citas')
            ->when(!$isRoot, fn($q) => $this->aplicarFiltroConsultorio($q->where('auditable_type', 'App\\Models\\Cita'), $consultorioIds))
            ->count();

        $totalEventosPagos = AuditLog::delModulo('pagos')->count();

        $totalLoginFallidos = AuthLog::where('event_type', 'LOGIN_FAIL')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->count();

        $totalCuentasBloqueadas = AuthLog::where('event_type', 'LOCKOUT')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->count();

        $totalLecturasHistorias = ReadAuditLog::count();

        $ultimosEventos = AuditLog::latest('created_at')->limit(10)->get();
        $ultimosAuthLogs = AuthLog::latest('created_at')->limit(8)->get();

        return view('admin.auditoria.index', compact(
            'totalEventosCitas',
            'totalEventosPagos',
            'totalLoginFallidos',
            'totalCuentasBloqueadas',
            'totalLecturasHistorias',
            'ultimosEventos',
            'ultimosAuthLogs',
            'isRoot'
        ));
    }

    // ── Citas ─────────────────────────────────────────────────────────────────

    public function citas(Request $request)
    {
        $consultorioIds = $this->getConsultorioIds();

        $query = AuditLog::delModulo('citas')->latest('created_at');

        if ($consultorioIds !== null) {
            $citaIds = \DB::table('citas')
                ->whereIn('consultorio_id', $consultorioIds)
                ->pluck('id');
            $query->where('auditable_type', 'App\\Models\\Cita')
                  ->whereIn('auditable_id', $citaIds);
        }

        $this->aplicarFiltrosFecha($query, $request);

        if ($request->filled('evento')) {
            $query->where('event', $request->evento);
        }

        $registros = $query->paginate(20)->withQueryString();

        return view('admin.auditoria.citas', compact('registros'));
    }

    // ── Pagos ─────────────────────────────────────────────────────────────────

    public function pagos(Request $request)
    {
        $query = AuditLog::delModulo('pagos')->latest('created_at');

        $this->aplicarFiltrosFecha($query, $request);

        if ($request->filled('evento')) {
            $query->where('event', $request->evento);
        }

        $registros = $query->paginate(20)->withQueryString();

        return view('admin.auditoria.pagos', compact('registros'));
    }

    // ── Acceso a Historias Clínicas ───────────────────────────────────────────

    public function accesoMedico(Request $request)
    {
        $query = ReadAuditLog::latest('created_at');

        $this->aplicarFiltrosFecha($query, $request);

        if ($request->filled('tipo')) {
            $query->porTipo($request->tipo);
        }

        $registros = $query->paginate(20)->withQueryString();

        return view('admin.auditoria.acceso_medico', compact('registros'));
    }

    // ── Auth Logs ─────────────────────────────────────────────────────────────

    public function authLogs(Request $request)
    {
        $query = AuthLog::latest('created_at');

        $this->aplicarFiltrosFecha($query, $request);

        if ($request->filled('evento')) {
            $query->porEvento($request->evento);
        }

        if ($request->filled('correo')) {
            $query->where('correo', 'like', '%' . $request->correo . '%');
        }

        $registros = $query->paginate(20)->withQueryString();

        return view('admin.auditoria.auth_logs', compact('registros'));
    }

    // ── Exportaciones Excel (maatwebsite/excel v1.x) ─────────────────────────

    public function exportarCitasExcel(Request $request)
    {
        $exp = new \App\Exports\AuditCitasExport($request->all());
        return Excel::create($exp->filename(), function ($excel) use ($exp) {
            $excel->sheet($exp->sheetTitle(), function ($sheet) use ($exp) {
                $sheet->fromArray(array_merge([$exp->headings()], $exp->rows()),
                    null, 'A1', false, false);
                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                    $row->setBackground('#10b981');
                    $row->setFontColor('#ffffff');
                });
            });
        })->download('xlsx');
    }

    public function exportarPagosExcel(Request $request)
    {
        $exp = new \App\Exports\AuditPagosExport($request->all());
        return Excel::create($exp->filename(), function ($excel) use ($exp) {
            $excel->sheet($exp->sheetTitle(), function ($sheet) use ($exp) {
                $sheet->fromArray(array_merge([$exp->headings()], $exp->rows()),
                    null, 'A1', false, false);
                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                    $row->setBackground('#3b82f6');
                    $row->setFontColor('#ffffff');
                });
            });
        })->download('xlsx');
    }

    public function exportarAuthExcel(Request $request)
    {
        $exp = new \App\Exports\AuditAuthExport($request->all());
        return Excel::create($exp->filename(), function ($excel) use ($exp) {
            $excel->sheet($exp->sheetTitle(), function ($sheet) use ($exp) {
                $sheet->fromArray(array_merge([$exp->headings()], $exp->rows()),
                    null, 'A1', false, false);
                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                    $row->setBackground('#f59e0b');
                    $row->setFontColor('#ffffff');
                });
            });
        })->download('xlsx');
    }

    // ── Exportaciones PDF ─────────────────────────────────────────────────────

    public function exportarCitasPdf(Request $request)
    {
        $consultorioIds = $this->getConsultorioIds();

        $query = AuditLog::delModulo('citas')->latest('created_at');
        if ($consultorioIds !== null) {
            $citaIds = \DB::table('citas')
                ->whereIn('consultorio_id', $consultorioIds)->pluck('id');
            $query->where('auditable_type', 'App\\Models\\Cita')
                  ->whereIn('auditable_id', $citaIds);
        }
        $this->aplicarFiltrosFecha($query, $request);
        $registros = $query->limit(500)->get();

        $pdf = Pdf::loadView('admin.auditoria.pdf.citas', compact('registros'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('auditoria_citas_' . now()->format('Ymd') . '.pdf');
    }

    public function exportarPagosPdf(Request $request)
    {
        $query = AuditLog::delModulo('pagos')->latest('created_at');
        $this->aplicarFiltrosFecha($query, $request);
        $registros = $query->limit(500)->get();

        $pdf = Pdf::loadView('admin.auditoria.pdf.pagos', compact('registros'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('auditoria_pagos_' . now()->format('Ymd') . '.pdf');
    }
}
