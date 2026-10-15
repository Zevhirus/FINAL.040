<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Supabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private Supabase $supabase)
    {
    }

    public function index(Request $request): View
    {
        $status = $request->input('status');
        $query = ['order' => 'created_at.desc'];
        if ($status) {
            $query['status'] = 'eq.'.$status;
        }
        $orders = $this->supabase->select('orders', $query) ?? [];
        $messages = $this->supabase->select('messages', ['order' => 'created_at.desc', 'limit' => 15]) ?? [];

        return view('admin.orders.index', compact('orders', 'messages', 'status'));
    }

    public function create(): View
    {
        $next = $this->nextInvoiceNo();
        return view('admin.orders.form', ['order' => null, 'next' => $next]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['invoice_no'] = $request->input('invoice_no') ?: $this->nextInvoiceNo();
        $data['budget'] = $this->toDecimal($request->input('budget'));
        $data['total']  = $this->toDecimal($request->input('total'));
        $data['dp']     = $this->toDecimal($request->input('dp'));
        $data['status'] = $request->input('status') ?: 'baru';
        $data['order_date'] = $request->input('order_date') ?: date('Y-m-d');
        $data['due_date']   = $request->input('due_date') ?: null;

        if (! $this->supabase->insert('orders', $data)) {
            return back()->withErrors(['invoice_no' => 'Gagal menyimpan pesanan.'])->withInput();
        }
        $this->flush();
        return redirect()->route('admin.orders.index')->with('status', 'Pesanan ditambahkan.');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $order = $this->find($id);
        if (! $order) return redirect()->route('admin.orders.index')->with('status', 'Pesanan tidak ditemukan.');
        return view('admin.orders.form', ['order' => $order, 'next' => $order['invoice_no']]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        if (! $this->find($id)) return redirect()->route('admin.orders.index')->with('status', 'Pesanan tidak ditemukan.');
        $data = $this->validated($request);
        $data['budget'] = $this->toDecimal($request->input('budget'));
        $data['total']  = $this->toDecimal($request->input('total'));
        $data['dp']     = $this->toDecimal($request->input('dp'));
        $data['status'] = $request->input('status') ?: 'baru';
        $data['order_date'] = $request->input('order_date') ?: date('Y-m-d');
        $data['due_date']   = $request->input('due_date') ?: null;
        if (! $this->supabase->update('orders', $id, $data)) {
            return back()->withErrors(['invoice_no' => 'Gagal memperbarui pesanan.'])->withInput();
        }
        $this->flush();
        return redirect()->route('admin.orders.index')->with('status', 'Pesanan diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->supabase->delete('orders', $id);
        $this->flush();
        return redirect()->route('admin.orders.index')->with('status', 'Pesanan dihapus.');
    }

    public function convertFromMessage(int $messageId): RedirectResponse
    {
        $msg = $this->findMessage($messageId);
        if (! $msg) return back()->with('status', 'Pesan tidak ditemukan.');
        $brief = ($msg['subject'] ? 'Subjek: '.$msg['subject']."\n\n" : '').($msg['message'] ?? '');
        $data = [
            'invoice_no' => $this->nextInvoiceNo(),
            'client_name' => $msg['name'] ?? '',
            'client_contact' => $msg['email'] ?? '',
            'title' => $msg['subject'] ?: 'Pesanan dari kontak',
            'brief' => trim($brief),
            'status' => 'baru',
            'order_date' => now()->format('Y-m-d'),
        ];
        if ($this->supabase->insert('orders', $data)) {
            $this->flush();
            return redirect()->route('admin.orders.index')->with('status', 'Pesan berhasil dikonversi jadi pesanan.');
        }
        return back()->withErrors(['convert' => 'Gagal mengonversi pesan.']);
    }

    public function invoice(int $id): View
    {
        $order = $this->find($id);
        abort_if(! $order, 404);
        return view('admin.orders.invoice', compact('order'));
    }

    private function find(int $id): ?array
    {
        $rows = $this->supabase->select('orders', ['select' => '*', 'id' => 'eq.'.$id, 'limit' => 1]);
        return $rows[0] ?? null;
    }
    private function findMessage(int $id): ?array
    {
        $rows = $this->supabase->select('messages', ['select' => '*', 'id' => 'eq.'.$id, 'limit' => 1]);
        return $rows[0] ?? null;
    }
    private function validated(Request $request): array
    {
        $request->validate([
            'client_name' => ['required', 'string', 'max:150'],
            'client_contact' => ['nullable', 'string', 'max:150'],
            'service' => ['nullable', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:150'],
            'brief' => ['nullable', 'string', 'max:2000'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'total'  => ['nullable', 'numeric', 'min:0'],
            'dp'     => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:baru,diproses,dikerjakan,selesai,batal'],
            'order_date' => ['nullable', 'date'],
            'due_date'   => ['nullable', 'date'],
            'notes'      => ['nullable', 'string', 'max:2000'],
        ], [
            'client_name.required' => 'Nama klien wajib diisi.',
            'title.required' => 'Nama proyek wajib diisi.',
            'status.in' => 'Status tidak valid.',
        ]);
        return [
            'client_name' => $request->input('client_name'),
            'client_contact' => $request->input('client_contact'),
            'service' => $request->input('service'),
            'title' => $request->input('title'),
            'brief' => $request->input('brief'),
            'notes' => $request->input('notes'),
        ];
    }
    private function toDecimal(?string $v): float
    {
        if ($v === null || $v === '') return 0;
        $v = str_replace(['.', ','], ['', '.'], $v);
        return (float) $v;
    }
    private function nextInvoiceNo(): string
    {
        $y = date('Y');
        $rows = $this->supabase->select('orders', ['order' => 'id.desc', 'limit' => 1]) ?? [];
        $last = $rows[0]['invoice_no'] ?? null;
        $seq = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $seq = ((int)$m[1]) + 1;
        }
        return sprintf('NT-%d-%04d', $y, $seq);
    }
    private function flush(): void
    {
        Cache::forget('supabase:orders:'.md5(json_encode(['order' => 'created_at.desc'])));
    }
}
