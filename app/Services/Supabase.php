<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Klien tipis untuk Supabase REST API (PostgREST) & Storage.
 */
class Supabase
{
    public function configured(): bool
    {
        return config('supabase.url') !== '' && config('supabase.anon_key') !== '';
    }

    /**
     * Ambil baris dari tabel. Mengembembalikan null jika gagal / belum dikonfigurasi.
     *
     * @param  array<string,mixed> $query  contoh: ['order' => 'sort.asc', 'limit' => 3]
     */
    public function select(string $table, array $query = []): ?array
    {
        if (! $this->configured()) {
            return null;
        }

        $key = 'supabase:'.$table.':'.md5(json_encode($query));

        return Cache::remember($key, config('supabase.cache_ttl'), function () use ($table, $query) {
            try {
                $response = Http::withHeaders($this->headers(config('supabase.anon_key')))
                    ->timeout(8)
                    ->get(config('supabase.url')."/rest/v1/{$table}", ['select' => '*'] + $query);

                return $response->successful() ? $response->json() : null;
            } catch (\Throwable $e) {
                Log::warning('Supabase select gagal: '.$e->getMessage());

                return null;
            }
        });
    }

    /**
     * Simpan satu baris (memakai service key, jalankan hanya di server).
     */
    public function insert(string $table, array $row): bool
    {
        return $this->send($table, fn (PendingRequest $http) => $http->post(config('supabase.url')."/rest/v1/{$table}", $row));
    }

    /**
     * Perbarui baris berdasarkan id (memakai service key).
     */
    public function update(string $table, int $id, array $row): bool
    {
        return $this->send($table, fn (PendingRequest $http) => $http->patch(
            config('supabase.url')."/rest/v1/{$table}?id=eq.{$id}",
            $row
        ));
    }

    /**
     * Hapus baris berdasarkan id (memakai service key).
     */
    public function delete(string $table, int $id): bool
    {
        return $this->send($table, fn (PendingRequest $http) => $http->delete(
            config('supabase.url')."/rest/v1/{$table}?id=eq.{$id}"
        ));
    }

    /**
     * Upload gambar ke Supabase Storage, kembalikan URL publiknya.
     * Kembalikan null jika gagal.
     */
    public function upload(string $path, string $contents, string $mime): ?string
    {
        $key = config('supabase.service_key');
        $bucket = config('admin.storage_bucket');

        if (config('supabase.url') === '' || $key === '' || $bucket === '') {
            return null;
        }

        try {
            $response = Http::withHeaders($this->headers($key) + [
                'x-upsert' => 'true',
            ])->timeout(20)->withBody($contents, $mime)->put(
                config('supabase.url').'/storage/v1/object/'.$bucket.'/'.$path
            );

            return $response->successful() ? $this->publicUrl($bucket, $path) : null;
        } catch (\Throwable $e) {
            Log::error('Supabase upload gagal: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Hapus objek dari Storage. Aman dipanggil walau file tidak ada.
     */
    public function deleteObject(string $path): bool
    {
        $key = config('supabase.service_key');
        $bucket = config('admin.storage_bucket');

        if (config('supabase.url') === '' || $key === '') {
            return false;
        }

        try {
            return Http::withHeaders($this->headers($key))->timeout(10)
                ->delete(config('supabase.url').'/storage/v1/object/'.$bucket.'/'.$path)
                ->successful();
        } catch (\Throwable $e) {
            Log::warning('Supabase delete object gagal: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Bikin nama file unik dari nama file asli.
     */
    public function objectPath(string $folder, string $originalName): string
    {
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $safe = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        if ($safe === '') {
            $safe = 'file';
        }

        return trim($folder, '/').'/'.$safe.'-'.Str::lower(Str::random(8)).($ext !== '' ? '.'.$ext : '');
    }

    private function publicUrl(string $bucket, string $path): string
    {
        return rtrim(config('supabase.url'), '/').'/storage/v1/object/public/'.$bucket.'/'.$path;
    }

    private function send(string $table, \Closure $request): bool
    {
        $key = config('supabase.service_key');

        if (config('supabase.url') === '' || $key === '') {
            Log::info('Supabase belum dikonfigurasi, data tidak disimpan.', ['table' => $table]);

            return false;
        }

        try {
            $response = $request(
                Http::withHeaders($this->headers($key) + ['Prefer' => 'return=minimal'])
                    ->timeout(8)
            );

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Supabase request gagal: '.$e->getMessage());

            return false;
        }
    }

    private function headers(string $key): array
    {
        return ['apikey' => $key, 'Authorization' => 'Bearer '.$key];
    }
}
