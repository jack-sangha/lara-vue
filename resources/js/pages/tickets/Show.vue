<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';
import { formatDate } from '@/lib/utils';
import type { Ticket } from '@/types';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { details as ticketDetailsRoute } from '@/routes/tickets';

const props = defineProps<{
    ticket: Ticket;
}>();

const http = useHttp<Record<string, never>, Ticket>({});
const isOpen = ref(false);
const isLoading = ref(false);
const loadError = ref<string | null>(null);
const ticketDetails = ref<Ticket | null>(null);
let requestId = 0;

const fetchTicketDetails = async (): Promise<void> => {
    const currentRequestId = ++requestId;
    isLoading.value = true;
    loadError.value = null;
    ticketDetails.value = null;

    try {
        const freshTicket = await http.get(
            ticketDetailsRoute(props.ticket.id).url,
        );

        if (currentRequestId === requestId) {
            ticketDetails.value = freshTicket;
        }
    } catch {
        if (currentRequestId === requestId) {
            loadError.value =
                'Unable to load ticket details. Please try again.';
        }
    } finally {
        if (currentRequestId === requestId) {
            isLoading.value = false;
        }
    }
};

const handleOpenChange = (open: boolean): void => {
    isOpen.value = open;

    if (open) {
        void fetchTicketDetails();
        return;
    }

    requestId++;
    http.cancel();
    isLoading.value = false;
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="handleOpenChange">
        <DialogTrigger as-child>
            <slot name="trigger">
                <Button variant="ghost">View</Button>
            </slot>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>
                    {{ ticketDetails?.title ?? ticket.title }}
                </DialogTitle>
                <DialogDescription>Ticket details</DialogDescription>
            </DialogHeader>

            <p
                v-if="isLoading"
                role="status"
                class="text-sm text-muted-foreground"
            >
                Loading ticket details...
            </p>

            <div v-else-if="loadError" role="alert" class="grid gap-3">
                <p class="text-sm text-destructive">{{ loadError }}</p>
                <Button
                    variant="outline"
                    class="w-fit"
                    @click="fetchTicketDetails"
                >
                    Try again
                </Button>
            </div>

            <div v-else-if="ticketDetails" class="grid gap-4">
                <div class="grid gap-2">
                    <h2 class="text-sm font-medium">Description</h2>
                    <p class="text-sm text-muted-foreground">
                        {{ ticketDetails.description }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <h2 class="text-sm font-medium">Created</h2>
                    <time
                        class="text-sm text-muted-foreground"
                        :datetime="ticketDetails.created_at"
                    >
                        {{ formatDate(ticketDetails.created_at) }}
                    </time>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
