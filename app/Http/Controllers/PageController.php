<?php

namespace App\Http\Controllers;

use App\Services\Supabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct(private Supabase $supabase) {}

    public function home()
    {
        $projects = $this->supabase->select('projects', ['order' => 'sort.asc', 'limit' => 6])
            ?: array_slice(config('portfolio.sample_projects'), 0, 6);

        $photos = $this->supabase->select('photos', ['order' => 'sort.asc', 'limit' => 4])
            ?: array_slice(config('portfolio.sample_photos'), 0, 4);

        return view('home', compact('projects', 'photos'));
    }

    public function projects()
    {
        $projects = $this->supabase->select('projects', ['order' => 'sort.asc'])
            ?: config('portfolio.sample_projects');

        return view('projects', compact('projects'));
    }

    public function project(string $ref)
    {
        $project = null;

        if (ctype_digit($ref)) {
            $rows = $this->supabase->select('projects', ['id' => 'eq.'.$ref, 'limit' => 1]);
            $project = $rows[0] ?? null;
        }

        if (! $project) {
            $project = collect(config('portfolio.sample_projects'))
                ->first(fn ($p) => Str::slug($p['title']) === $ref);
        }

        abort_if(! $project, 404);

        $others = $this->supabase->select('projects', ['order' => 'sort.asc', 'limit' => 6])
            ?: config('portfolio.sample_projects');

        $others = collect($others)
            ->reject(fn ($p) => ($p['id'] ?? Str::slug($p['title'])) == ($project['id'] ?? $ref))
            ->take(3)
            ->values()
            ->all();

        return view('project', ['p' => $project, 'others' => $others]);
    }

    public function photography()
    {
        $photos = $this->supabase->select('photos', ['order' => 'sort.asc'])
            ?: config('portfolio.sample_photos');

        $categories = collect($photos)->pluck('category')->filter()->unique()->values();

        return view('photography', compact('photos', 'categories'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function send(Request $request): RedirectResponse
    {
        // Honeypot sederhana: bot biasanya mengisi kolom tersembunyi ini.
        if ($request->filled('website')) {
            return back()->with('status', 'Terima kasih! Pesan Anda sudah terkirim.');
        }

        $throttleKey = 'contact:'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors(['message' => 'Terlalu banyak percobaan. Coba lagi beberapa menit lagi.'])->withInput();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'message.required' => 'Pesan wajib diisi.',
            'message.min' => 'Pesan minimal 10 karakter.',
        ]);

        RateLimiter::hit($throttleKey, 600);

        $saved = $this->supabase->insert('messages', $data);

        if (! $saved) {
            return back()->withErrors(['message' => 'Pesan belum bisa dikirim saat ini. Silakan hubungi lewat email.'])->withInput();
        }

        return back()->with('status', 'Terima kasih! Pesan Anda sudah terkirim.');
    }
}
