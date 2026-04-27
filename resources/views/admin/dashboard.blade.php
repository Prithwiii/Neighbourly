@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="rounded-3xl bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-600 text-white shadow-2xl p-6 md:p-8 border border-emerald-500/30">
            <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs uppercase tracking-[0.3em] text-emerald-100/90 font-semibold">Admin Control Center</p>
                    <h1 class="mt-3 text-3xl md:text-4xl font-bold">Admin Dashboard</h1>
                    <p class="mt-3 text-emerald-50/90 leading-7">
                        Welcome, admin. Manage announcements, service providers, and donation requests from one clean workspace.
                    </p>
                </div>

            </div>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-3xl bg-white/90 backdrop-blur-xl border border-white/60 shadow-xl p-6">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-semibold">Announcements</p>
                <h2 class="mt-3 text-xl font-bold text-slate-900">Create announcement posts</h2>
                <p class="mt-2 text-sm text-slate-600">Publish community updates and notices for everyone.</p>
                <a href="{{ route('announcements.create') }}" class="mt-5 inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white no-underline transition hover:bg-slate-800">
                    Create Announcement
                </a>
            </div>

            <div class="rounded-3xl bg-white/90 backdrop-blur-xl border border-white/60 shadow-xl p-6">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-semibold">Service Providers</p>
                <h2 class="mt-3 text-xl font-bold text-slate-900">Review provider applications</h2>
                <p class="mt-2 text-sm text-slate-600">Approve or reject provider verification requests.</p>
                <a href="{{ route('admin.providers.index') }}" class="mt-5 inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white no-underline transition hover:bg-slate-800">
                    Review Provider Applications
                </a>
            </div>
        </div>
    </div>
@endsection