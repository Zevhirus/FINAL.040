<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Supabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private Supabase $supabase)
    {
    }

    public function index(): View
    {
        $projects = $this->supabase->select('projects', ['order' => 'sort.asc']) ?? [];

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('admin.projects.form', ['project' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($url = $this->uploadImage($request)) {
            $data['image_url'] = $url;
        }

        $data['tags'] = $this->parseTags($request->input('tags'));
        $data['sort'] = (int) $request->input('sort', 0);

        if (! $this->supabase->insert('projects', $data)) {
            return back()->withErrors(['image' => 'Gagal menyimpan proyek. Cek log / koneksi Supabase.'])->withInput();
        }

        $this->flush();

        return redirect()->route('admin.projects.index')->with('status', 'Proyek ditambahkan.');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $project = $this->find($id);

        if (! $project) {
            return redirect()->route('admin.projects.index')->with('status', 'Proyek tidak ditemukan.');
        }

        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        if (! $this->find($id)) {
            return redirect()->route('admin.projects.index')->with('status', 'Proyek tidak ditemukan.');
        }

        $data = $this->validated($request);

        if ($url = $this->uploadImage($request)) {
            $data['image_url'] = $url;
        }

        $data['tags'] = $this->parseTags($request->input('tags'));
        $data['sort'] = (int) $request->input('sort', 0);

        if (! $this->supabase->update('projects', $id, $data)) {
            return back()->withErrors(['image' => 'Gagal memperbarui proyek.'])->withInput();
        }

        $this->flush();

        return redirect()->route('admin.projects.index')->with('status', 'Proyek diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->supabase->delete('projects', $id);
        $this->flush();

        return redirect()->route('admin.projects.index')->with('status', 'Proyek dihapus.');
    }

    private function find(int $id): ?array
    {
        $rows = $this->supabase->select('projects', ['select' => '*', 'id' => 'eq.'.$id, 'limit' => 1]);

        return $rows[0] ?? null;
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'category'    => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:500'],
            'link'        => ['nullable', 'url', 'max:300'],
            'image'       => ['nullable', 'image', 'max:4096'],
            'sort'        => ['nullable', 'integer', 'min:0', 'max:9999'],
            'tags'        => ['nullable', 'string', 'max:300'],
        ], [
            'title.required' => 'Nama proyek wajib diisi.',
            'link.url'       => 'Link harus berupa URL lengkap (https://...).',
            'image.image'    => 'File harus berupa gambar.',
            'image.max'      => 'Gambar maksimal 4 MB.',
        ]);

        return [
            'title'       => $request->input('title'),
            'category'    => $request->input('category'),
            'description' => $request->input('description'),
            'link'        => $request->input('link') ?: null,
        ];
    }

    private function uploadImage(Request $request): ?string
    {
        if (! $request->hasFile('image') || ! $request->file('image')->isValid()) {
            return null;
        }

        $file = $request->file('image');
        $path = $this->supabase->objectPath('projects', $file->getClientOriginalName());
        $url = $this->supabase->upload($path, (string) file_get_contents($file->getRealPath()), $file->getMimeType() ?: 'image/jpeg');

        if (! $url) {
            abort(back()->withErrors(['image' => 'Upload gambar ke Supabase Storage gagal. Pastikan bucket dibuat & service key benar.']));
        }

        return $url;
    }

    /**
     * "laravel, supabase" -> ['laravel', 'supabase']
     */
    private function parseTags(?string $raw): array
    {
        $tags = array_filter(array_map('trim', explode(',', (string) $raw)));

        return array_values($tags);
    }

    private function flush(): void
    {
        Cache::forget('supabase:projects:'.md5(json_encode(['order' => 'sort.asc'])));
        Cache::forget('supabase:projects:'.md5(json_encode(['order' => 'sort.asc', 'limit' => 6])));
    }
}
