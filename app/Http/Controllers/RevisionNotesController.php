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

    public function show(RevisionTopic $topic, RevisionNote $note): View
    {
        if ($note->revision_topic_id !== $topic->id) {
            abort(404);
        }

        $related = $topic->notes()
            ->where('id', '!=', $note->id)
            ->orderBy('title')
            ->limit(6)
            ->get();

        return view('revision-notes.show', compact('topic', 'note', 'related'));
    }
}
