-- Jalankan di Supabase: SQL Editor > New query > paste > Run

create table if not exists public.projects (
  id          bigint generated always as identity primary key,
  title       text not null,
  category    text,
  description text,
  image_url   text,
  tags        text[] default '{}',
  link        text,
  sort        int default 0,
  created_at  timestamptz default now()
);

create table if not exists public.photos (
  id         bigint generated always as identity primary key,
  title      text,
  category   text,
  image_url  text not null,
  sort       int default 0,
  created_at timestamptz default now()
);

create table if not exists public.messages (
  id         bigint generated always as identity primary key,
  name       text not null,
  email      text not null,
  subject    text,
  message    text not null,
  created_at timestamptz default now()
);

-- Tabel Pesanan (Order)
create table if not exists public.orders (
  id           bigint generated always as identity primary key,
  invoice_no   text not null unique,
  client_name  text not null,
  client_contact text,
  service      text,
  title        text not null,
  brief        text,
  budget       numeric(12,2) default 0,
  total        numeric(12,2) default 0,
  dp           numeric(12,2) default 0,
  status       text not null default 'baru', -- baru|diproses|dikerjakan|selesai|batal
  order_date   date not null default current_date,
  due_date     date,
  notes        text,
  created_at   timestamptz default now(),
  updated_at   timestamptz default now()
);

-- Trigger updated_at otomatis
create or replace function public.set_updated_at()
returns trigger as $$
begin
  new.updated_at = now();
  return new;
end;
$$ language plpgsql;

drop trigger if exists orders_updated_at on public.orders;
create trigger orders_updated_at
before update on public.orders
for each row execute procedure public.set_updated_at();

-- Keamanan (Row Level Security)
alter table public.projects enable row level security;
alter table public.photos   enable row level security;
alter table public.messages enable row level security;
alter table public.orders   enable row level security;

-- Pengunjung boleh MEMBACA proyek & foto
create policy "public read projects" on public.projects for select using (true);
create policy "public read photos"   on public.photos   for select using (true);

-- messages & orders: tidak ada policy publik. Hanya service role (server + Supabase Dashboard) boleh baca/tulis.
-- Storage: buat bucket publik bernama "portfolio" lewat dashboard (Storage > New bucket > Public)

-- Contoh data (opsional)
insert into public.projects (title, category, description, image_url, tags, sort)
values ('Contoh Proyek', 'Web App', 'Ganti dengan proyek Anda.', 'https://picsum.photos/seed/aan-x/900/600', '{Laravel,Supabase}', 1)
on conflict do nothing;
