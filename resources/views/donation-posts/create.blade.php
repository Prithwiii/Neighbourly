@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="rounded-3xl bg-white/85 backdrop-blur-xl border border-white/50 shadow-2xl p-8">
        <h1 class="text-3xl font-bold text-emerald-900">Request Help</h1>
        <p class="mt-2 text-slate-600">Create a donation request. It will be marked as pending until admin approval.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('donation-posts.store') }}" enctype="multipart/form-data" class="mt-8 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="5" required
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">{{ old('description') }}</textarea>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Amount Needed (BDT)</label>
                    <input type="number" min="1" step="0.01" name="goal_amount" value="{{ old('goal_amount') }}" required
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                    <select name="category" required
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Select category</option>
                        <option value="medical" @selected(old('category') === 'medical')>Medical</option>
                        <option value="education" @selected(old('category') === 'education')>Education</option>
                        <option value="emergency" @selected(old('category') === 'emergency')>Emergency</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Location</label>
                <input type="text" name="location" value="{{ old('location') }}"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Proof (images/docs)</label>
                <input type="file" name="proof_files[]" multiple
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-white">
                <p class="mt-2 text-xs text-slate-500">Accepted: jpg, jpeg, png, webp, pdf, doc, docx. Max 5 files.</p>
            </div>

            <button type="submit" class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                Submit Request
            </button>
        </form>
    </div>
</div>
@endsection
