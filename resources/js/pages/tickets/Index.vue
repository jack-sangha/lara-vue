<script setup lang="ts">
import Heading from "@/components/Heading.vue";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import { Ticket } from "@/types";
import { Head, Link } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { tickets } from "@/routes/tickets";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Ellipsis } from "@lucide/vue";
import { formatDate, truncate } from "@/lib/utils";

type Props = {
    tickets: Ticket[];
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: "Tickets",
                href: tickets.index(),
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
        <div class="flex justify-between items-center">
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
            <Link :href="tickets.create()">
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
                    <TableCell class="text-right flex gap-1">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="outline">
                                    <Ellipsis class="h-4 w-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-56" align="start">
                                <DropdownMenuGroup>
                                    <DropdownMenuItem>
                                        View
                                        <DropdownMenuShortcut>
                                            ⇧⌘V
                                        </DropdownMenuShortcut>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        Edit
                                        <DropdownMenuShortcut>
                                            ⇧⌘E
                                        </DropdownMenuShortcut>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        Delete
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
