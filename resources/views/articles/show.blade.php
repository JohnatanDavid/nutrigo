@extends('layouts.app')
@section('title', $article->title)
@section('page-title', $article->title)

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="space-y-8 rounded-[28px] bg-white p-8 shadow-[0_24px_80px_rgba(24,84,42,0.12)] ring-1 ring-black/5">
            <div class="space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.28em] text-[#f55c1f]">
                            {{ ucfirst($article->category) }}</p>
                        <h1 class="mt-3 text-4xl font-black text-[#17311f]">{{ $article->title }}</h1>
                    </div>
                </div>
                @if ($article->read_time)
                    <p class="text-sm text-[#6b5f46]">{{ $article->read_time }} menit baca</p>
                @endif
            </div>

            @if ($article->image)
                <div class="overflow-hidden rounded-[24px] border border-[#ece1c1] bg-[#f8f1dc]">
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}"
                        class="w-full object-cover">
                </div>
            @endif

            <div class="space-y-6 text-[#4d5a4f] prose prose-lg max-w-none">
                {!! $article->content !!}
            </div>
        </div>
    </div>
@endsection
