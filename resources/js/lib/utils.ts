import type { InertiaLinkProps } from "@inertiajs/vue3";
import { clsx } from "clsx";
import type { ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(href: NonNullable<InertiaLinkProps["href"]>) {
    return typeof href === "string" ? href : href?.url;
}

export function truncate(text: string, max = 50): string {
    return text.length > max ? text.slice(0, max).trimEnd() + "…" : text;
}

export function formatDate(value: string | null, withTime = false): string {
    if (!value) return "";
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return "";

    return new Intl.DateTimeFormat("en-IN", {
        dateStyle: "medium",
        ...(withTime && { timeStyle: "short" }),
    }).format(date);
}
