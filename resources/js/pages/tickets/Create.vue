<script setup lang="ts">
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import Input from "@/components/ui/input/Input.vue";
</script>

<template>
    <Form>
        <FormField>
            <FormItem>
                <FormLabel />
                <FormControl>
                    <Input />
                </FormControl>
                <FormDescription />
                <FormMessage />
            </FormItem>
        </FormField>
    </Form>
</template>
