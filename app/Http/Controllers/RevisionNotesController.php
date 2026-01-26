<?php

namespace App\Http\Controllers;

use App\Models\RevisionNote;
use App\Models\RevisionTopic;
use App\Models\Topic;
use App\Models\User;
use Illuminate\View\View;

class RevisionNotesController extends Controller
{
    public function index(): View
    {
        $examType = $this->resolveExamType(auth()->user());
        $topics = RevisionTopic::withCount('notes')
            ->where('exam_type', $examType)
            ->orderBy('name')
            ->get();

        return view('revision-notes.index', compact('topics'));
    }

    public function topic(RevisionTopic $topic): View
    {
        $examType = $this->resolveExamType(auth()->user());
        if ($topic->exam_type !== $examType) {
            abort(404);
        }

        $notes = $topic->notes()
            ->orderBy('title')
            ->get();

        return view('revision-notes.topic', compact('topic', 'notes'));
    }

    public function show(RevisionTopic $topic, RevisionNote $note): View
    {
        $examType = $this->resolveExamType(auth()->user());
        if ($topic->exam_type !== $examType) {
            abort(404);
        }

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

    private function resolveExamType(?User $user): string
    {
        $examType = $user?->activeSubscription()?->plan?->exam_type;
        if (in_array($examType, Topic::EXAM_TYPES, true)) {
            return $examType;
        }

        return Topic::EXAM_PRIMARY;
    }
}
