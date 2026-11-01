<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoRequest;
use App\Models\PageView;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    private const TYPES = [
        'demos' => [
            'model' => DemoRequest::class,
            'title' => 'Demo requests',
            'singular' => 'Demo request',
            'statuses' => ['new' => 'New', 'contacted' => 'Contacted', 'scheduled' => 'Scheduled', 'completed' => 'Completed', 'cancelled' => 'Cancelled'],
        ],
        'quotes' => [
            'model' => QuoteRequest::class,
            'title' => 'Quote requests',
            'singular' => 'Quote request',
            'statuses' => ['new' => 'New', 'contacted' => 'Contacted', 'quoted' => 'Quote sent', 'won' => 'Won', 'lost' => 'Lost'],
        ],
    ];

    public function index(string $type)
    {
        $cfg = self::TYPES[$type];

        // DataTables handles search, sorting and paging in the browser.
        $leads = $cfg['model']::with('product')->latest()->limit(3000)->get();

        return view('admin.leads.index', [
            'type' => $type,
            'cfg' => $cfg,
            'leads' => $leads,
            'counts' => $leads->countBy('status'),
        ]);
    }

    public function show(string $type, string $id)
    {
        $cfg = self::TYPES[$type];
        $lead = $cfg['model']::with('product')->findOrFail($id);

        $journey = $lead->visitor_id
            ? PageView::where('visitor_id', $lead->visitor_id)->orderBy('created_at')->limit(60)->get()
            : collect();

        return view('admin.leads.show', compact('type', 'cfg', 'lead', 'journey'));
    }

    public function update(Request $request, string $type, string $id)
    {
        $cfg = self::TYPES[$type];
        $lead = $cfg['model']::findOrFail($id);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys($cfg['statuses']))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $lead->status = $data['status'];
        $lead->admin_notes = $data['admin_notes'] ?? null;
        $lead->save();

        return back()->with('success', 'Changes saved.');
    }

    public function destroy(string $type, string $id)
    {
        $cfg = self::TYPES[$type];
        $cfg['model']::findOrFail($id)->delete();

        return redirect()->route('admin.leads.index', $type)->with('success', $cfg['singular'] . ' deleted.');
    }
}
