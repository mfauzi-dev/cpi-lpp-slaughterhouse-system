<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role->name;

        switch ($role) {
            case 'Admin':
                return $this->adminDashboard($request);

            case 'General Manager':
                return $this->generalManagerDashboard($request);

            case 'Manager':
                return $this->managerDashboard($request);

            case 'Admin Production':
                return $this->adminProductionDashboard();

            case 'Operator':
                return $this->operatorDashboard();

            default:
                abort(403);
        }
    }

    public function operatorDashboard()
    {
        return view('pages.dashboard.operator');
    }

    public function managerDashboard()
    {
        return view('pages.dashboard.manager');
    }
}
