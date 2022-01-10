import { mount } from "@vue/test-utils";
import CreateOrEdit from "../../js/Pages/Customer/CreateOrEdit.vue";

test("uses mounts", async () => {
    const wrapper = mount(CreateOrEdit, {
        props: {
            user: new Object()
        },
    });
});
