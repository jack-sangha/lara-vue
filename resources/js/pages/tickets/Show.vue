<script setup lang="ts">
import { Form, Link, router } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { formatDate } from "@/lib/utils";

const props = defineProps<{
    ticket: any;
    agents: { id: number; name: string }[];
    can: { update: boolean; changeStatus: boolean; assign: boolean }; // from Laravel policies
}>();

const next: Record<string, string | undefined> = {
    open: "in_progress",
    in_progress: "resolved",
};
const base = `/tickets/${props.ticket.id}`;
</script>

<template>
    <AppLayout>
        <div class="mx-auto max-w-3xl space-y-4 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">{{ ticket.title }}</h1>
                <Button v-if="can.update" as-child variant="outline" size="sm">
                    <Link :href="`${base}/edit`">Edit</Link>
                </Button>
            </div>

            <div class="flex flex-wrap gap-2">
                <Badge>{{ ticket.status }}</Badge>
                <Badge variant="outline">{{ ticket.priority }}</Badge>
                <Badge
                    v-for="c in ticket.categories"
                    :key="c.id"
                    variant="secondary"
                    >{{ c.name }}</Badge
                >
            </div>

            <p class="whitespace-pre-line text-sm">{{ ticket.description }}</p>
            <p class="text-muted-foreground text-xs">
                {{ ticket.creator.name }} ·
                {{ formatDate(ticket.created_at, true) }}
            </p>

            <div
                v-if="can.changeStatus || can.assign"
                class="flex gap-3 border-t pt-4"
            >
                <Button
                    v-if="can.changeStatus && next[ticket.status]"
                    as-child
                    size="sm"
                >
                    <Link
                        as="button"
                        method="patch"
                        :href="`${base}/status`"
                        :data="{ status: next[ticket.status] }"
                        preserve-scroll
                    >
                        Mark {{ next[ticket.status] }}
                    </Link>
                </Button>

                <Select
                    v-if="can.assign"
                    :model-value="String(ticket.assignee?.id ?? '')"
                    @update:model-value="
                        (id) =>
                            router.post(
                                `${base}/assign`,
                                { assigned_to: id },
                                { preserveScroll: true },
                            )
                    "
                >
                    <SelectTrigger class="w-48"
                        ><SelectValue placeholder="Assign to…"
                    /></SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="a in agents"
                            :key="a.id"
                            :value="String(a.id)"
                            >{{ a.name }}</SelectItem
                        >
                    </SelectContent>
                </Select>
            </div>

            <div class="space-y-3 border-t pt-4">
                <p
                    v-if="!ticket.comments.length"
                    class="text-muted-foreground text-sm"
                >
                    No comments yet.
                </p>
                <div
                    v-for="c in ticket.comments"
                    :key="c.id"
                    class="rounded-md border p-3 text-sm"
                >
                    {{ c.body }}
                    <p class="text-muted-foreground mt-1 text-xs">
                        {{ c.user.name }} · {{ formatDate(c.created_at, true) }}
                    </p>
                </div>

                <!-- reset-on-success clears the textarea after a successful post -->
                <Form
                    :action="`${base}/comments`"
                    method="post"
                    reset-on-success
                    :options="{ preserveScroll: true }"
                    #default="{ errors, processing }"
                    class="space-y-2"
                >
                    <Textarea name="body" placeholder="Add a comment" />
                    <p class="text-sm text-red-600">{{ errors.body }}</p>
                    <Button size="sm" :disabled="processing">Post</Button>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
