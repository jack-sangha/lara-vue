<script setup lang="ts">
import { Form } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

defineProps<{ ticket?: any; categories: { id: number; name: string }[] }>();
</script>

<template>
    <AppLayout>
        <!-- <Form> submits named fields and exposes API validation errors via slot props -->
        <Form
            class="mx-auto max-w-2xl space-y-4 p-6"
            :action="ticket ? `/tickets/${ticket.id}` : '/tickets'"
            :method="ticket ? 'put' : 'post'"
            #default="{ errors, processing }"
        >
            <Label>Title</Label>
            <Input name="title" :default-value="ticket?.title" />
            <p class="text-sm text-red-600">{{ errors.title }}</p>

            <Label>Description</Label>
            <Textarea name="description" :default-value="ticket?.description" />
            <p class="text-sm text-red-600">{{ errors.description }}</p>

            <Select
                name="priority"
                :default-value="ticket?.priority ?? 'medium'"
            >
                <SelectTrigger><SelectValue /></SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="p in ['low', 'medium', 'high']"
                        :key="p"
                        :value="p"
                        >{{ p }}</SelectItem
                    >
                </SelectContent>
            </Select>

            <label
                v-for="c in categories"
                :key="c.id"
                class="mr-4 inline-flex items-center gap-1 text-sm"
            >
                <input
                    type="checkbox"
                    name="category_ids[]"
                    :value="c.id"
                    :checked="
                        ticket?.categories.some((x: any) => x.id === c.id)
                    "
                />
                {{ c.name }}
            </label>

            <Button :disabled="processing" class="block">{{
                ticket ? "Save" : "Create"
            }}</Button>
        </Form>
    </AppLayout>
</template>
