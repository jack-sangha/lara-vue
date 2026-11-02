<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { formatDate } from "@/lib/utils";
import { index, show } from "@/routes/tickets";

const props = defineProps<{
    ticket: any;
}>();

const base = `/tickets/${props.ticket.id}`;

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: "Tickets",
                href: index(),
            },
            {
                title: "View Ticket",
            },
        ],
    },
});
</script>

<template>
    <div class="mx-auto max-w-3xl space-y-4 p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">{{ ticket.title }}</h1>
            <Button as-child variant="outline" size="sm">
                <Link :href="`${base}/edit`">Edit</Link>
            </Button>
        </div>

        <p class="whitespace-pre-line text-sm">{{ ticket.description }}</p>
        <p class="text-muted-foreground text-xs">
            {{ ticket.creator.name }} ·
            {{ formatDate(ticket.created_at, true) }}
        </p>
    </div>
</template>
