<script setup lang="ts">
import { Form } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";

defineProps<{ ticket?: any }>();
</script>

<template>
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

        <Button :disabled="processing" class="block">{{
            ticket ? "Save" : "Create"
        }}</Button>
    </Form>
</template>
