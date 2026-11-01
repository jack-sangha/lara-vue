<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import { Label } from "@/components/ui/label";

const props = defineProps<{
    ticketID: number;
}>();
</script>

<template>
    <Dialog>
        <form>
            <DialogTrigger as-child>View</DialogTrigger>
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Ticket Details</DialogTitle>
                    <DialogDescription>
                        View the details of the selected ticket.
                    </DialogDescription>
                </DialogHeader>
                <div class="grid gap-4">
                    <div class="grid gap-3">
                        <Label for="name-1">Title</Label>
                        <h2>{{ ticket.title }}</h2>
                    </div>
                    <div class="grid gap-3">
                        <Label for="username-1">Description</Label>
                        <p>{{ ticket.description }}</p>
                    </div>
                </div>
                <DialogFooter>
                    <div>{{ ticket.createdAt }}</div>
                </DialogFooter>
            </DialogContent>
        </form>
    </Dialog>
</template>
