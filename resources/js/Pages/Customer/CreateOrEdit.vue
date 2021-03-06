<template>
    <jet-form-section @submitted="saveInfo">
        <template #title> Customer Information </template>

        <template #description> Update customer information. </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    autocomplete="name"
                />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="phone" value="Phone" />
                <jet-input
                    id="phone"
                    type="tel"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    autocomplete="phone"
                />
                <jet-input-error :message="form.errors.phone" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="email" value="Email" />
                <jet-input
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                />
                <jet-input-error :message="form.errors.email" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
import { defineComponent } from "vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { useForm } from "@inertiajs/inertia-vue3";

export default defineComponent({
    components: {
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
    },

    props: {
        user: Object,
        edit: false
    },

    data() {
        return {
            form: useForm({
                name: this.user.name,
                phone: this.user.phone,
                email: this.user.email,
                photo: null,
            }),

            photoPreview: null,
        };
    },
    methods: {

        saveInfo() {
            if (this.edit) {
                this.updateCustomerInformation();
            }else{
                this.createCustomerInformation();
            }
        },

        createCustomerInformation() {
            this.form.post(route("customers.store"), {
                preserveScroll: true,
                onSuccess: () => this.clearPhotoFileInput(),
            });
        },

        updateCustomerInformation() {
            this.form.put(route("customers.update", this.user.id), {
                errorBag: "updateCustomerInformation",
                preserveScroll: true,
                onSuccess: () => this.clearPhotoFileInput(),
            });
        },

        clearPhotoFileInput() {
            this.$emit("close", true);
        },
    },
});
</script>
