import { mount } from "@vue/test-utils";
import CreateOrEdit from "../../resources/js/Pages/Customer/CreateOrEdit.vue";

test("uses mounts", async () => {
    const wrapper = mount(CreateOrEdit, {
        props: {
            user: {
                id: "1",
                name: "Cedrick Franecki DDS",
                email: "test@test.com",
                email_verified_at: "2022-01-09 17:00:44",
                remember_token: "6gTECBFcwv",
                current_team_id: null,
                profile_photo_path: null,
                created_at: "2022-01-09 17:00:44",
                updated_at: "2022-01-09 17:00:44",
                phone: "+1.303.433.8962",
                banned_at: null,
            },
        },
    });
    wrapper.vm.saveInfo();
    expect(wrapper.html()).toContain('Customer Information')
});
