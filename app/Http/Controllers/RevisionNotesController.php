<?php

namespace App\Http\Controllers;

use App\Models\RevisionNote;
use App\Models\RevisionTopic;
use Illuminate\View\View;

class RevisionNotesController extends Controller
{
    public function index(): View
    {
        $topics = RevisionTopic::withCount('notes')
            ->orderBy('name')
            ->get();

        return view('revision-notes.index', compact('topics'));
    }

    public function topic(RevisionTopic $topic): View
    {
        $notes = $topic->notes()
            ->orderBy('title')
            ->get();

        return view('revision-notes.topic', compact('topic', 'notes'));
    }

    public function show(RevisionTopic $topic, string $note): View
    {
        $noteModel = $topic->notes()
            ->where('slug', $note)
            ->orWhere('id', $note)
            ->firstOrFail();

        $related = $topic->notes()
            ->where('id', '!=', $noteModel->id)
            ->orderBy('title')
            ->limit(6)
            ->get();

        return view('revision-notes.show', [
            'topic' => $topic,
            'note' => $noteModel,
            'related' => $related,
        ]);
    }
}
