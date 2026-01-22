<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockPaper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MockPaperController extends Controller
{
    public function index(): View
    {
        $papers = MockPaper::withCount('questions')
            ->orderBy('order')
            ->paginate(15);

        return view('admin.mock-papers.index', compact('papers'));
    }

    public function create(): View
    {
        return view('admin.mock-papers.form', [
            'paper' => new MockPaper(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePaper($request);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?? $data['title']);

        MockPaper::create($data);

        return redirect()->route('admin.mock-papers.index')
            ->with('status', 'Mock paper created.');
    }

    public function edit(MockPaper $mockPaper): View
    {
        return view('admin.mock-papers.form', [
            'paper' => $mockPaper,
        ]);
    }

    public function update(Request $request, MockPaper $mockPaper): RedirectResponse
    {
        $data = $this->validatePaper($request);
        $data['slug'] = $this->makeUniqueSlug($data['slug'] ?? $data['title'], $mockPaper->id);

        $mockPaper->update($data);

        return redirect()->route('admin.mock-papers.index')
            ->with('status', 'Mock paper updated.');
    }

    public function destroy(MockPaper $mockPaper): RedirectResponse
    {
        $mockPaper->delete();

        return redirect()->route('admin.mock-papers.index')
            ->with('status', 'Mock paper deleted.');
    }

    private function validatePaper(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:170'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function makeUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);
        $slug = $slug !== '' ? $slug : Str::slug(now()->format('Y-m-d-His'));
        $original = $slug;
        $suffix = 2;

        while (MockPaper::query()
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
