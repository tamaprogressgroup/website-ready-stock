<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\PropertyUnit;
use App\Models\PropertyUnitTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadsController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::guard('admin')->id();

        $propertyIds = PropertyUnit::where('created_user_id', $userId)
            ->pluck('property_id');

        $leads = Lead::whereIn('property_id', $propertyIds)
            ->orderByDesc('created_at')
            ->paginate(25);

        $propertyTitles = PropertyUnitTrans::whereIn('property_id', $propertyIds)
            ->where('locale', 'id')
            ->pluck('title', 'property_id');

        return view('back.leads.leads', compact('leads', 'propertyTitles'));
    }
}
