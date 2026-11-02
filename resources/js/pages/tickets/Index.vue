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

const props = defineProps<{
    tickets: {
        data: any[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: { search?: string; status?: string; priority?: string };
}>();

const filters = reactive({
    search: props.filters.search ?? "",
    status: props.filters.status ?? "all",
    priority: props.filters.priority ?? "all",
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
</script>

<template>
    <AppLayout>
        <div class="space-y-4 p-6">
            <div class="flex flex-wrap gap-3">
                <Input
                    v-model="filters.search"
                    placeholder="Search title…"
                    class="w-64"
                />

                <Select v-model="filters.status">
                    <SelectTrigger class="w-40"
                        ><SelectValue placeholder="Status"
                    /></SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="s in [
                                'all',
                                'open',
                                'in_progress',
                                'resolved',
                            ]"
                            :key="s"
                            :value="s"
                            >{{ s }}</SelectItem
                        >
                    </SelectContent>
                </Select>

                <Select v-model="filters.priority">
                    <SelectTrigger class="w-40"
                        ><SelectValue placeholder="Priority"
                    /></SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="p in ['all', 'low', 'medium', 'high']"
                            :key="p"
                            :value="p"
                            >{{ p }}</SelectItem
                        >
                    </SelectContent>
                </Select>

                <Button as-child class="ml-auto"
                    ><Link href="/tickets/create">New ticket</Link></Button
                >
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Title</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Priority</TableHead>
                        <TableHead>Assignee</TableHead>
                        <TableHead>Created</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-if="!tickets.data.length">
                        <TableCell
                            colspan="5"
                            class="text-muted-foreground text-center"
                            >No tickets found.</TableCell
                        >
                    </TableRow>
                    <TableRow v-for="t in tickets.data" :key="t.id">
                        <TableCell
                            class="max-w-[260px] truncate"
                            :title="t.title"
                        >
                            <Link
                                :href="`/tickets/${t.id}`"
                                class="font-medium hover:underline"
                                >{{ t.title }}</Link
                            >
                        </TableCell>
                        <TableCell
                            ><Badge>{{ t.status }}</Badge></TableCell
                        >
                        <TableCell
                            ><Badge variant="outline">{{
                                t.priority
                            }}</Badge></TableCell
                        >
                        <TableCell>{{ t.assignee?.name ?? "—" }}</TableCell>
                        <TableCell>{{ formatDate(t.created_at) }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Laravel's paginator already provides the links; labels contain HTML entities (&laquo;) -->
            <div class="flex flex-wrap gap-1">
                <Button
                    v-for="(l, i) in tickets.links"
                    :key="i"
                    as-child
                    size="sm"
                    :variant="l.active ? 'default' : 'outline'"
                    :disabled="!l.url"
                >
                    <Link
                        :href="l.url ?? '#'"
                        preserve-state
                        v-html="l.label"
                    />
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
