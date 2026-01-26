<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RevisionTopic;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RevisionTopicController extends Controller
{
    public function index(Request $request): View
    {
        $examTypes = $this->examTypes();
        $examType = $request->input('exam_type');
        if (!array_key_exists($examType, $examTypes)) {
            $examType = null;
        }

        $topics = RevisionTopic::withCount('notes')
            ->when($examType, fn ($query) => $query->where('exam_type', $examType))
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('admin.revision-topics.index', compact('topics', 'examTypes', 'examType'));
    }

    public function create(Request $request): View
    {
        $examTypes = $this->examTypes();
        $examType = $request->input('exam_type');
        if (!array_key_exists($examType, $examTypes)) {
            $examType = Topic::EXAM_PRIMARY;
        }

        return view('admin.revision-topics.form', [
            'topic' => new RevisionTopic(),
            'examTypes' => $examTypes,
            'examType' => $examType,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateTopic($request);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?? $data['name']);

        RevisionTopic::create($data);

        return redirect()->route('admin.revision-topics.index')
            ->with('status', 'Revision topic created.');
    }

    public function edit(RevisionTopic $revisionTopic): View
    {
        return view('admin.revision-topics.form', [
            'topic' => $revisionTopic,
            'examTypes' => $this->examTypes(),
            'examType' => $revisionTopic->exam_type ?? Topic::EXAM_PRIMARY,
        ]);
    }

    public function update(Request $request, RevisionTopic $revisionTopic): RedirectResponse
    {
        $data = $this->validateTopic($request);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?? $data['name'], $revisionTopic->id);

        $revisionTopic->update($data);

        return redirect()->route('admin.revision-topics.index')
            ->with('status', 'Revision topic updated.');
    }

    public function destroy(RevisionTopic $revisionTopic): RedirectResponse
    {
        $revisionTopic->delete();

        return redirect()->route('admin.revision-topics.index')
            ->with('status', 'Revision topic deleted.');
    }

    private function validateTopic(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:150'],
            'exam_type' => ['required', Rule::in(Topic::EXAM_TYPES)],
            'description' => ['nullable', 'string'],
        ]);
    }

    private function examTypes(): array
    {
        return [
            Topic::EXAM_PRIMARY => 'MRCEM Primary',
            Topic::EXAM_INTERMEDIATE => 'MRCEM Intermediate',
        ];
    }

    private function makeUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);
        $slug = $slug !== '' ? $slug : Str::slug(now()->format('Y-m-d-His'));
        $original = $slug;
        $suffix = 2;

        while (RevisionTopic::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
