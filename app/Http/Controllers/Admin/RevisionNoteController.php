<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RevisionNote;
use App\Models\RevisionTopic;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RevisionNoteController extends Controller
{
    public function index(Request $request): View
    {
        $examTypes = $this->examTypes();
        $examType = $request->input('exam_type');
        if (!array_key_exists($examType, $examTypes)) {
            $examType = null;
        }

        $notes = RevisionNote::with('topic')
            ->when($examType, function ($query) use ($examType) {
                $query->whereHas('topic', fn ($subquery) => $subquery->where('exam_type', $examType));
            })
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('admin.revision-notes.index', compact('notes', 'examTypes', 'examType'));
    }

    public function create(Request $request): View
    {
        $topics = RevisionTopic::orderBy('name')->get();
        $examTypes = $this->examTypes();
        $examType = $request->input('exam_type');
        if (!array_key_exists($examType, $examTypes)) {
            $examType = Topic::EXAM_PRIMARY;
        }

        return view('admin.revision-notes.form', [
            'note' => new RevisionNote(),
            'topics' => $topics,
            'examTypes' => $examTypes,
            'examType' => $examType,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateNote($request);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?? $data['title']);

        RevisionNote::create($data);

        return redirect()->route('admin.revision-notes.index')
            ->with('status', 'Revision note created.');
    }

    public function edit(RevisionNote $revisionNote): View
    {
        $topics = RevisionTopic::orderBy('name')->get();
        $examType = $revisionNote->topic?->exam_type ?? Topic::EXAM_PRIMARY;

        return view('admin.revision-notes.form', [
            'note' => $revisionNote,
            'topics' => $topics,
            'examTypes' => $this->examTypes(),
            'examType' => $examType,
        ]);
    }

    public function update(Request $request, RevisionNote $revisionNote): RedirectResponse
    {
        $data = $this->validateNote($request);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?? $data['title'], $revisionNote->id);

        $revisionNote->update($data);

        return redirect()->route('admin.revision-notes.index')
            ->with('status', 'Revision note updated.');
    }

    public function destroy(RevisionNote $revisionNote): RedirectResponse
    {
        $revisionNote->delete();

        return redirect()->route('admin.revision-notes.index')
            ->with('status', 'Revision note deleted.');
    }

    private function validateNote(Request $request): array
    {
        return $request->validate([
            'revision_topic_id' => ['required', 'exists:revision_topics,id'],
            'title' => ['required', 'string', 'max:140'],
            'slug' => ['nullable', 'string', 'max:160'],
            'summary' => ['nullable', 'string'],
            'content' => ['required', 'string'],
        ]);
    }

    private function makeUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);
        $slug = $slug !== '' ? $slug : Str::slug(now()->format('Y-m-d-His'));
        $original = $slug;
        $suffix = 2;

        while (RevisionNote::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function examTypes(): array
    {
        return [
            Topic::EXAM_PRIMARY => 'MRCEM Primary',
            Topic::EXAM_INTERMEDIATE => 'MRCEM Intermediate',
        ];
    }
}
