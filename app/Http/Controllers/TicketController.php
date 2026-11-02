<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Inertia\Inertia;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Ticket::class);
        $filters = $request->only('title', 'status');

        $tickets = Ticket::query()
            ->with('creator:id,name')
            ->visibleTo($request->user())
            ->filter($filters)
            ->latest()->latest('id') // id breaks created_at ties so pages never repeat rows
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('tickets/Index', [
            'tickets' => TicketResource::collection($tickets),
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Ticket::class);

        return Inertia::render('tickets/Form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        // Transaction so a failed category sync doesn't leave an orphan ticket.
        $ticket = DB::transaction(function () use ($request) {
            $ticket = Ticket::create([...$request->safe(), 'created_by' => $request->user()->id]);

            return $ticket;
        });

        return to_route('tickets.show', $ticket);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        // One query per relation, constant regardless of data size.
        $ticket->load(['creator:id,name']);

        return Inertia::render('tickets/Show', [
            'ticket' => TicketResource::make($ticket)->resolve(), // resolve() drops the "data" wrapper
        ]);
    }

    public function details(Ticket $ticket): JsonResponse
    {
        return response()->json($ticket);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        return Inertia::render('tickets/Form', [
            'ticket' => TicketResource::make($ticket)->resolve(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        DB::transaction(function () use ($request, $ticket) {
            $ticket->update([...$request->safe()]);
        });

        return to_route('tickets.show', $ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
