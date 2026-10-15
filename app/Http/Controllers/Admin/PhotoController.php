<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Supabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PhotoController extends Controller
{
    public function __construct(private Supabase $supabase)
    {
    }

    public function index(): View
    {
        $photos = $this->supabase->select('photos', ['order' => 'sort.asc']) ?? [];

        return view('admin.photos.index', compact('photos'));
    }

    public function create(): View
    {
        return view('admin.photos.form', ['photo' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if (! ($data['image_url'] = $this->uploadImage($request))) {
            return back()->withErrors(['image' => 'Upload gambar gagal. Pastikan bucket Supabase Storage sudah dibuat.'])->withInput();
        }

        $data['sort'] = (int) $request->input('sort', 0);

        if (! $this->supabase->insert('photos', $data)) {
            return back()->withErrors(['image' => 'Gagal menyimpan foto. Cek log / koneksi Supabase.'])->withInput();
        }

        $this->flush();

        return redirect()->route('admin.photos.index')->with('status', 'Foto ditambahkan.');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $photo = $this->find($id);

        if (! $photo) {
            return redirect()->route('admin.photos.index')->with('status', 'Foto tidak ditemukan.');
        }

        return view('admin.photos.form', compact('photo'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        if (! $this->find($id)) {
            return redirect()->route('admin.photos.index')->with('status', 'Foto tidak ditemukan.');
        }

        $data = $this->validated($request);
        $data['sort'] = (int) $request->input('sort', 0);

        if ($url = $this->uploadImage($request)) {
            $data['image_url'] = $url;
        } elseif ($request->filled('keep_image')) {
            unset($data['image_url']);
        }

        if (! $this->supabase->update('photos', $id, $data)) {
            return back()->withErrors(['image' => 'Gagal memperbarui foto.'])->withInput();
        }

        $this->flush();

        return redirect()->route('admin.photos.index')->with('status', 'Foto diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $photo = $this->find($id);

        if ($photo) {
            if ($path = $this->pathFromUrl($photo['image_url'] ?? '')) {
                $this->supabase->deleteObject($path);
            }

            $this->supabase->delete('photos', $id);
        }

        $this->flush();

        return redirect()->route('admin.photos.index')->with('status', 'Foto dihapus.');
    }

    private function find(int $id): ?array
    {
        $rows = $this->supabase->select('photos', ['select' => '*', 'id' => 'eq.'.$id, 'limit' => 1]);

        return $rows[0] ?? null;
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'title'    => ['nullable', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:80'],
            'image'    => ['nullable', 'image', 'max:6144'],
            'sort'     => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'image.image' => 'File harus berupa gambar.',
            'image.max'   => 'Gambar maksimal 6 MB.',
        ]);

        return [
            'title'      => $request->input('title'),
            'category'   => $request->input('category'),
            'image_url'  => $request->input('image_url', ''),
        ];
    }

    private function uploadImage(Request $request): ?string
    {
        if (! $request->hasFile('image') || ! $request->file('image')->isValid()) {
            return null;
        }

        $file = $request->file('image');
        $path = $this->supabase->objectPath('photos', $file->getClientOriginalName());
        $url = $this->supabase->upload($path, (string) file_get_contents($file->getRealPath()), $file->getMimeType() ?: 'image/jpeg');

        if (! $url) {
            abort(back()->withErrors(['image' => 'Upload gambar ke Supabase Storage gagal. Pastikan bucket dibuat & service key benar.']));
        }

        return $url;
    }

    /**
     * Ambil path relatif dari URL publik Supabase Storage.
     * https://.../storage/v1/object/public/bucket/foto/a.jpg -> foto/a.jpg
     */
    private function pathFromUrl(string $url): ?string
    {
        $marker = '/storage/v1/object/public/';
        $bucket = config('admin.storage_bucket');

        $pos = strpos($url, $marker.$bucket.'/');

        if ($pos === false) {
            return null;
        }

        return substr($url, $pos + strlen($marker.$bucket.'/'));
    }

    private function flush(): void
    {
        Cache::forget('supabase:photos:'.md5(json_encode(['order' => 'sort.asc'])));
        Cache::forget('supabase:photos:'.md5(json_encode(['order' => 'sort.asc', 'limit' => 4])));
    }
}
