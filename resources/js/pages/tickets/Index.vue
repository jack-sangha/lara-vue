<script setup lang="ts">
import Heading from "@/components/Heading.vue";
import Button from "@/components/ui/button/Button.vue";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import { index } from "@/routes/tickets";
import { Ticket } from "@/types";
import { Head } from "@inertiajs/vue3";

type Props = {
    tickets: Ticket[];
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: "Tickets",
                href: index(),
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
        <h1 class="sr-only">Tickets</h1>

        <div class="flex flex-col space-y-6">
            <Heading
                variant="small"
                title="Tickets"
                description="Listing of all your tickets."
            />
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Title</TableHead>
                        <TableHead>Description</TableHead>
                        <TableHead>Created At</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="ticket in tickets" :key="ticket.id">
                        <TableCell class="font-medium">
                            {{ ticket.title }}
                        </TableCell>
                        <TableCell>{{ ticket.description }}</TableCell>
                        <TableCell class="text-right">
                            {{ ticket.created_at }}
                        </TableCell>
                        <TableCell class="text-right flex gap-1"> </TableCell>
                    </TableRow>
                </TableBody>
                <TableFooter>
                    <TableRow>
                        <TableCell colspan="3"> Total </TableCell>
                        <TableCell class="text-right"> $2,500.00 </TableCell>
                    </TableRow>
                </TableFooter>
            </Table>
        </div>
    </div>
</template>
