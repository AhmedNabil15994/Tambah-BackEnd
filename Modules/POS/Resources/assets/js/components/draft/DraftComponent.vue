<template>
    <div class="bg-white list-items mt-3">
        <div class="head-item"><h6>{{ $t("main.Draft") }} #{{ drafts.length }} </h6></div>
        <div v-if="drafts.length > 0" class="custdata-table  res-table" style="max-height:200px  !important">
            <table class="table ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ $t("main.No_Items") }}</th>
                        <th scope="col"> {{ $t("main.Customer") }}</th>
                        <th scope="col"> {{ $t("main.Sub_Total") }}</th>
                        <th scope="col"> {{ $t("main.Total") }}</th>

                        <th scope="col" class="dataTable-action">{{ $t("main.Action") }}</th>
                    </tr>
                </thead>
                <tbody class="table-items">
                    <tr v-for="(order, index) in drafts" :key="`draft_${index}_${order.id}`">
                        <th scope="row">#{{ index + 1 }}</th>

                        <td>{{ order.items.length }}</td>
                        <td>{{ 'user' in order ? order.user.name : "" }}</td>
                        <td>{{ currency + " " + order.subTotal.toFixed(3) }}</td>
                        <td>{{ currency + " " + order.total.toFixed(3) }}</td>

                        <td class="dataTable-action">

                            <button class="btn btn-view" @click.prevent="(event) => repalceCart(index, event.target)"
                                type="button"><i class="ti-pencil"></i></button>

                            <button class="btn remove-item" @click.prevent="() => removeDraft(index)" type="button"><i
                                    class="ti-trash"></i></button>
                        </td>
                    </tr>




                </tbody>
            </table>

        </div>
        <div v-else>
            <p class="message-nofound text-center"><i class="ti-face-sad"></i> {{ $t("main.There_is_no_Draft_items") }}
            </p>
        </div>
    </div>
</template>

<script>
import services from "../../services";
export default {
    props: ["userId"],
    created() {
        this.drafts = this.getLocalStorage()
        // end 
        this.$root.$on('draft_update', (data) => {
            // alert("ho")
            this.drafts = this.getLocalStorage()
        })

    },
    methods: {
        getLocalStorage() {
            let drafts = JSON.parse(localStorage.getItem("drafts"))
            return drafts ? drafts : []
        },
        removeDraft(index) {
            swal({
                title: "",
                text: this.$t("main.Are_you_sure_removing_this_item?"),
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                closeOnConfirm: false,
                animation: false,
                customClass: {
                    popup: 'animated tada'
                }
            }, (isConfirm) => {
                if (isConfirm) {
                    this.drafts.splice(index, 1)
                    localStorage.setItem("drafts", JSON.stringify(this.drafts))
                    swal.close()
                }

            })
            // let drafts = this.getLocalStorage()

        },
        repalceCart(index, button) {
            let draft = this.drafts[index];
            button.disabled = true
            this.$root.$emit("loading:screen-start")
            let carts = draft;
            this.$emit("selected", carts);
        
            this.drafts.splice(index, 1);
            localStorage.setItem("drafts", JSON.stringify(this.drafts))
            button.disabled = false
            this.$toast.success(this.$t("main.draft_selected"));
            toast.success()
            this.$root.$emit("loading:screen-end")
        },
        handleErrorInAjex(error) {
            let res = error.response
            if (res) this.$toast.error(res.data.message);
        },
    },
    data() {
        return {
            drafts: [],
        }
    },
}
</script>
