<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockPaper;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MockPaperController extends Controller
{
    public function index(Request $request): View
    {
        $examTypes = $this->examTypes();
        $examType = $request->input('exam_type');
        if (!array_key_exists($examType, $examTypes)) {
            $examType = null;
        }

        $papers = MockPaper::withCount('questions')
            ->when($examType, fn ($query) => $query->where('exam_type', $examType))
            ->orderBy('order')
            ->paginate(15);

        return view('admin.mock-papers.index', compact('papers', 'examTypes', 'examType'));
    }

    public function create(Request $request): View
    {
        $examTypes = $this->examTypes();
        $examType = $request->input('exam_type');
        if (!array_key_exists($examType, $examTypes)) {
            $examType = Topic::EXAM_PRIMARY;
        }

        return view('admin.mock-papers.form', [
            'paper' => new MockPaper(),
            'examTypes' => $examTypes,
            'examType' => $examType,
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
            'examTypes' => $this->examTypes(),
            'examType' => $mockPaper->exam_type ?? Topic::EXAM_PRIMARY,
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
            'exam_type' => ['required', Rule::in(Topic::EXAM_TYPES)],
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

    private function examTypes(): array
    {
        return [
            Topic::EXAM_PRIMARY => 'MRCEM Primary',
            Topic::EXAM_INTERMEDIATE => 'MRCEM Intermediate',
        ];
    }
}
