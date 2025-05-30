@extends('layouts.app')

@section('title', 'Custom Cake Requests')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-4xl font-extrabold mb-8 text-center text-[#556B2F]">Custom Cake Requests</h1>

<div class="mb-6 flex justify-center gap-4">
    <a href="{{ route('admin.custom.index', ['filter' => 'all']) }}"
       class="px-4 py-2 rounded {{ ($filter ?? '') === 'all' ? 'bg-green-600 text-white' : 'bg-gray-200' }}">
       Semua
    </a>
    <a href="{{ route('admin.custom.index', ['filter' => 'daily']) }}"
       class="px-4 py-2 rounded {{ ($filter ?? '') === 'daily' ? 'bg-green-600 text-white' : 'bg-gray-200' }}">
       Harian
    </a>
    <a href="{{ route('admin.custom.index', ['filter' => 'weekly']) }}"
       class="px-4 py-2 rounded {{ ($filter ?? '') === 'weekly' ? 'bg-green-600 text-white' : 'bg-gray-200' }}">
       Mingguan
    </a>
    <a href="{{ route('admin.custom.index', ['filter' => 'monthly']) }}"
       class="px-4 py-2 rounded {{ ($filter ?? '') === 'monthly' ? 'bg-green-600 text-white' : 'bg-gray-200' }}">
       Bulanan
    </a>
</div>

    @if($customRequests->isEmpty())
        <p class="text-center text-gray-500 italic text-lg">No custom cake requests available.</p>
    @else
    <div class="overflow-x-auto shadow rounded-lg">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300">
                    <th class="text-sm font-semibold text-gray-600 uppercase px-6 py-4 text-center">#</th>
                    <th class="text-sm font-semibold text-gray-600 uppercase px-6 py-4 text-left">Customer Name</th>
                    <th class="text-sm font-semibold text-gray-600 uppercase px-6 py-4 text-left">Design</th>
                    <th class="text-sm font-semibold text-gray-600 uppercase px-6 py-4 text-center">Requested At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customRequests as $request)
                    <tr class="@if($loop->even) bg-gray-50 @endif border-b border-gray-200 hover:bg-gray-100 transition">
                        <td class="px-6 py-3 text-center text-gray-700 font-medium">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3 text-gray-800 font-semibold">{{ $request->nama ?? $request->user->name ?? 'Anonymous' }}</td>
                        <td class="px-6 py-3 text-gray-700 whitespace-normal">{{ \Illuminate\Support\Str::limit($request->desain, 70) }}</td>
                        <td class="px-6 py-3 text-center text-gray-600 text-sm">{{ $request->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
