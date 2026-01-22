<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TopicController extends Controller
{
    public function index(): View
    {
        $topics = Topic::withCount('questions')
            ->orderBy('name')
            ->paginate(12);

        return view('admin.topics.index', compact('topics'));
    }

    public function create(): View
    {
        return view('admin.topics.form', [
            'topic' => new Topic(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name']);

        Topic::create($data);

        return redirect()
            ->route('admin.topics.index')
            ->with('status', 'Topic created.');
    }

    public function edit(Topic $topic): View
    {
        return view('admin.topics.form', compact('topic'));
    }

    public function update(Request $request, Topic $topic): RedirectResponse
    {
        $data = $this->validatedData($request, $topic);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $topic->id);

        $topic->update($data);

        return redirect()
            ->route('admin.topics.index')
            ->with('status', 'Topic updated.');
    }

    public function destroy(Topic $topic): RedirectResponse
    {
        $topic->delete();

        return redirect()
            ->route('admin.topics.index')
            ->with('status', 'Topic deleted.');
    }

    private function validatedData(Request $request, ?Topic $topic = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('topics', 'name')->ignore($topic?->id),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('topics', 'slug')->ignore($topic?->id),
            ],
        ]);
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base ?: 'topic';
        $counter = 2;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return Topic::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }
}
