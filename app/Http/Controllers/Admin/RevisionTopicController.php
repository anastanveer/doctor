<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RevisionTopic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RevisionTopicController extends Controller
{
    public function index(): View
    {
        $topics = RevisionTopic::withCount('notes')
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('admin.revision-topics.index', compact('topics'));
    }

    public function create(): View
    {
        return view('admin.revision-topics.form', [
            'topic' => new RevisionTopic(),
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
            'description' => ['nullable', 'string'],
        ]);
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
