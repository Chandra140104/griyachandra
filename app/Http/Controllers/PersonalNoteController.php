<?php

namespace App\Http\Controllers;

use App\Models\PersonalNote;
use App\Models\PersonalTask;
use App\Models\PersonalEvent;
use App\Models\PersonalReminder;
use App\Models\PersonalFinance;
use App\Models\PersonalHealthLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PersonalNoteController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('tab', 'summary');

        $data = [
            'contract_start' => $user->contract_start,
            'contract_end' => $user->contract_end,
        ];
        
        if ($tab === 'summary') {
            $today = Carbon::today();
            $data['tasks'] = $user->personalTasks()->where('is_completed', false)->get();
            $data['events'] = $user->personalEvents()->whereDate('start_time', $today)->get();
            $data['reminders'] = $user->personalReminders()->whereDate('remind_at', $today)->get();
            

        } elseif ($tab === 'notes') {
            $data['notes'] = $user->personalNotes()->orderBy('is_pinned', 'desc')->latest()->get();
        } elseif ($tab === 'schedule') {
            $data['tasks'] = $user->personalTasks()->latest()->get();
            $data['events'] = $user->personalEvents()->orderBy('start_time')->get();
            $data['reminders'] = $user->personalReminders()->orderBy('remind_at')->get();
        } elseif ($tab === 'health') {
            $data['health_logs'] = $user->personalHealthLogs()->latest()->get();
            $data['latest_bmi'] = $user->personalHealthLogs()->latest()->first();
        }

        return view('personal-notes', compact('tab', 'data'));
    }

    // --- Sub-feature Actions ---

    public function storeTask(Request $request)
    {
        $validated = $request->validate(['title' => 'required|string|max:255']);
        auth()->user()->personalTasks()->create($validated);
        return back()->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function toggleTask(PersonalTask $task)
    {
        if ($task->user_id !== auth()->id()) abort(403);
        $task->update(['is_completed' => !$task->is_completed]);
        return back();
    }

    public function updateTask(Request $request, PersonalTask $task)
    {
        if ($task->user_id !== auth()->id()) abort(403);
        $validated = $request->validate(['title' => 'required|string|max:255']);
        $task->update($validated);
        return back()->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroyTask(PersonalTask $task)
    {
        if ($task->user_id !== auth()->id()) abort(403);
        $task->delete();
        return back()->with('success', 'Tugas berhasil dihapus.');
    }

    public function storeNote(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string'
        ]);
        auth()->user()->personalNotes()->create($validated);
        return back()->with('success', 'Catatan berhasil disimpan.');
    }

    public function updateNote(Request $request, PersonalNote $note)
    {
        if ($note->user_id !== auth()->id()) abort(403);
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string'
        ]);
        $note->update($validated);
        return back()->with('success', 'Catatan berhasil diperbarui.');
    }

    public function destroyNote(PersonalNote $note)
    {
        if ($note->user_id !== auth()->id()) abort(403);
        $note->delete();
        return back()->with('success', 'Catatan berhasil dihapus.');
    }

    public function togglePinNote(PersonalNote $note)
    {
        if ($note->user_id !== auth()->id()) abort(403);
        $note->update(['is_pinned' => !$note->is_pinned]);
        return back();
    }



    public function storeHealth(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|in:Laki-laki,Perempuan',
            'age' => 'required|integer|min:1',
            'height' => 'required|numeric|min:50',
            'weight' => 'required|numeric|min:1',
        ]);

        $heightM = $validated['height'] / 100;
        $bmi = $validated['weight'] / ($heightM * $heightM);
        
        $category = '';
        if ($bmi < 18.5) $category = 'Kurus';
        elseif ($bmi < 25) $category = 'Normal';
        elseif ($bmi < 30) $category = 'Kelebihan berat badan';
        else $category = 'Obesitas';

        $validated['bmi'] = round($bmi, 1);
        $validated['category'] = $category;

        auth()->user()->personalHealthLogs()->create($validated);
        return back()->with('success', 'Data kesehatan berhasil diperbarui.');
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'description' => 'nullable|string',
        ]);
        auth()->user()->personalEvents()->create($validated);
        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function storeReminder(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'remind_at' => 'required|date',
        ]);
        auth()->user()->personalReminders()->create($validated);
        return back()->with('success', 'Reminder berhasil disetel.');
    }
}
