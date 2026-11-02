<script setup lang="ts">
import { reactive } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { watchDebounced } from "@vueuse/core";
import AppLayout from "@/layouts/AppLayout.vue";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import { formatDate } from "@/lib/utils";
import { index } from "@/routes/tickets";

const props = defineProps<{
    tickets: {
        data: any[];
        meta: {
            links: { url: string | null; label: string; active: boolean }[];
        };
    };
    filters: { title?: string; status?: string };
}>();

const filters = reactive({
    title: props.filters.title ?? "",
    status: props.filters.status ?? "all",
});

watchDebounced(
    filters,
    () =>
        router.get(
            "/tickets",
            Object.fromEntries(
                Object.entries(filters).filter(([, v]) => v && v !== "all"),
            ),
            { preserveState: true, replace: true },
        ),
    { debounce: 400, deep: true },
);

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
    <div class="space-y-4 p-6">
        <div class="flex flex-wrap gap-3">
            <Input
                v-model="filters.title"
                placeholder="Search title…"
                class="w-64"
            />

            <Select v-model="filters.status">
                <SelectTrigger class="w-40"
                    ><SelectValue placeholder="Status"
                /></SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="s in ['all', 'open', 'in_progress', 'resolved']"
                        :key="s"
                        :value="s"
                        >{{ s }}</SelectItem
                    >
                </SelectContent>
            </Select>

            <Button as-child class="ml-auto">
                <Link href="/tickets/create">New ticket</Link>
            </Button>
        </div>

        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Title</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Created</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-if="!tickets.data.length">
                    <TableCell
                        colspan="4"
                        class="text-muted-foreground text-center"
                        >No tickets found.</TableCell
                    >
                </TableRow>
                <TableRow v-for="t in tickets.data" :key="t.id">
                    <TableCell class="max-w-[260px] truncate" :title="t.title">
                        <Link
                            :href="`/tickets/${t.id}`"
                            class="font-medium hover:underline"
                            >{{ t.title }}</Link
                        >
                    </TableCell>
                    <TableCell
                        ><Badge>{{ t.status }}</Badge></TableCell
                    >
                    <TableCell>{{
                        (t.creator.name ?? "") + " " + formatDate(t.created_at)
                    }}</TableCell>
                    <TableCell>
                        <div class="flex gap-2">
                            <Button as-child variant="outline" size="sm">
                                <Link :href="`/tickets/${t.id}`">Show</Link>
                            </Button>
                            <Button as-child variant="outline" size="sm">
                                <Link :href="`/tickets/${t.id}/edit`"
                                    >Edit</Link
                                >
                            </Button>
                        </div>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>

        <!-- Laravel's paginator already provides the links; labels contain HTML entities (&laquo;) -->
        <div class="flex flex-wrap gap-1 justify-end">
            <Button
                v-for="(l, i) in tickets.meta.links"
                :key="i"
                as-child
                size="sm"
                :variant="l.active ? 'default' : 'outline'"
                :disabled="!l.url"
            >
                <Link :href="l.url ?? '#'" preserve-state v-html="l.label" />
            </Button>
        </div>
    </div>
</template>
