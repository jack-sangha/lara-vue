<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Ticket } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    create as createTicket,
    index as ticketsIndex,
    edit as editTicket,
    destroy as destroyTicket,
} from '@/routes/tickets';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Ellipsis } from '@lucide/vue';
import { formatDate, truncate } from '@/lib/utils';
import Show from './Show.vue';

type Props = {
    tickets: Ticket[];
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tickets',
                href: ticketsIndex(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Tickets" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="flex items-center justify-between">
            <div>
                <h1 class="sr-only">Tickets</h1>

                <div class="flex flex-col space-y-6">
                    <Heading
                        variant="small"
                        title="Tickets"
                        description="Listing of all your tickets."
                    />
                </div>
            </div>
            <Link :href="createTicket()">
                <Button>Create Ticket</Button>
            </Link>
        </div>

        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Title</TableHead>
                    <TableHead>Description</TableHead>
                    <TableHead>Created At</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="ticket in tickets" :key="ticket.id">
                    <TableCell class="font-medium">
                        {{ truncate(ticket.title, 30) }}
                    </TableCell>
                    <TableCell>{{
                        truncate(ticket.description, 50)
                    }}</TableCell>
                    <TableCell>
                        {{ formatDate(ticket.created_at) }}
                    </TableCell>
                    <TableCell class="flex gap-1 text-right">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="outline">
                                    <Ellipsis class="h-4 w-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-56" align="start">
                                <DropdownMenuGroup>
                                    <Show :ticket="ticket">
                                        <template #trigger>
                                            <DropdownMenuItem>
                                                View
                                                <DropdownMenuShortcut>
                                                    ⇧⌘V
                                                </DropdownMenuShortcut>
                                            </DropdownMenuItem>
                                        </template>
                                    </Show>
                                    <DropdownMenuItem as-child>
                                        <Link :href="editTicket(ticket.id)">
                                            Edit
                                            <DropdownMenuShortcut>
                                                ⇧⌘E
                                            </DropdownMenuShortcut>
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <Link :href="destroyTicket(ticket.id)">
                                            <Button>Delete</Button>
                                        </Link>
                                        <DropdownMenuShortcut>
                                            ⇧⌘D
                                        </DropdownMenuShortcut>
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
