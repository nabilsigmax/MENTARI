<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Masuk - Keripik Mentari</title><script src="https://cdn.tailwindcss.com"></script><script>tailwind.config = { theme: { extend: { colors: { mentari: { red: '#EE4D2D', 'red-dark': '#D73211', green: '#16A34A', 'green-dark': '#15803D', gray: '#F5F5F5' } } } } }</script></head>
<body class="min-h-screen bg-stone-100 px-4 py-10 text-stone-800">
<main class="mx-auto w-full max-w-md rounded-3xl bg-white p-8 shadow-xl">
<a href="{{ url('/') }}" class="inline-flex items-center gap-3"><img src="{{ asset('images/logo.png') }}" alt="Keripik Mentari" class="h-12"><span class="text-lg font-black text-mentari-red">MENTARI</span></a>
<h1 class="mt-8 text-2xl font-black">Masuk ke akun Anda</h1><p class="mt-2 text-sm text-stone-500">Kelola katalog produk Keripik Mentari.</p>
<form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">@csrf
<div><label for="email" class="text-sm font-bold">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="mt-2 w-full rounded-xl border border-stone-300 px-4 py-3">@error('email')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror</div>
<div><label for="password" class="text-sm font-bold">Kata sandi</label><input id="password" name="password" type="password" required autocomplete="current-password" class="mt-2 w-full rounded-xl border border-stone-300 px-4 py-3">@error('password')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror</div>

<button type="submit" class="w-full rounded-xl bg-mentari-green px-4 py-3 font-bold text-white hover:bg-mentari-green-dark transition">Masuk</button>
</form><p class="mt-7 text-center text-sm">Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-mentari-red">Daftar sekarang</a></p>
</main></body></html>
