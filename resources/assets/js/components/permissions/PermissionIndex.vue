<template>
    <div class="wrapper">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-12">
                    <b-card header="Permissions Lists">
                        <div class="form-group">
                            <b-button v-b-modal.modal-prevent variant="primary">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </b-button>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body" id="role-table">
                                <v-server-table url="/permission/permissions" :columns="columns" :data="permissions" :options="options" ref="table">
                                    <div slot="uri" slot-scope="props">
                                        <a id="btn-edit" v-b-modal.modal-edit class="btn btn-warning" v-on:click="edit(props.row.id, props.row.index)" title="Edit Role">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a id="btn-delete" class="btn btn-danger" v-on:click="deleteEntry(props.row.id, props.row.index)" title="Delete Role">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </v-server-table>
                            </div>
                        </div>
                        <b-modal id="modal-prevent" ref="modal" title="Nhập permission mới" @ok="handleOk" @shown="clearName">
                            <form @submit.stop.prevent="handleSubmit">
                                <b-form-group label="Nhập name:">
                                    <b-form-input v-model="permissions.name" type="text" required placeholder="Nhập name nà"></b-form-input>
                                </b-form-group>
                                <b-form-group label="Nhập display name:">
                                    <b-form-input v-model="permissions.display_name" type="text" placeholder="Nhập display name nà"></b-form-input>
                                </b-form-group>
                            </form>
                        </b-modal>
                        <b-modal id="modal-edit" ref="myModal" title="Edit permission" @ok="handleOkEdit" @shown="clearName">
                            <form @submit.stop.prevent="handleSubmitEdit" @focus.native="handleSubmitEdit()">
                                <b-form-group label="Nhập role:">
                                    <b-form-input v-model="editPermission.name" type="text" required placeholder="Nhập name nà"></b-form-input>
                                </b-form-group>
                                <b-form-group label="Nhập display name:">
                                    <b-form-input v-model="editPermission.display_name" type="text" placeholder="Nhập display name nà"></b-form-input>
                                </b-form-group>
                            </form>
                        </b-modal>
                    </b-card>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
// Vue.use(VueTables.ClientTable);
export default {
    data: function() {
        return {
            permissions: [],
            permission: {
                name: "",
                display_name: ""
            },
            editPermission: {},
            columns: ["id", "name", "display_name",'created_at','updated_at', "uri"],
            options: {
                headings: {
                    name: "Name",
                    code: "Display Name",
                    created: 'created_at',
                    updated: 'updated_at',
                    uri: "Action"
                },
                sortIcon: {
                    base: "fa fa-sort",
                    up: "fa fa-sort-desc",
                    down: "fa fa-sort-asc"
                },
                sortable: ["id", "name", "display_name","created_at","updated_at"],
                filterable: ["id", "name", "display_name","created_at","updated_at"],
                orderBy: { ascending: true },
                orderBy: { column: "id" }
            }
        };
    },
    text:{
    	filterPlaceholder:"Tìm kiếm",
    },
    mounted() {},
    methods: {
        getVueItems: function getVueItems() {
            var app = this;
            axios
                .get("/permission/permissions")
                .then(function(resp) {
                    app.permissions = resp.data;
                })
                .catch(function(resp) {
                    console.log(resp);
                    alert("Could not load Permissions");
                });
        },
        deleteEntry(id, index) {
            this.$swal("Chắc chưa??Xóa rồi không lấy lại được đâu!", {
                buttons: {
                    cancel: {
                        text: "Hủy",
                        value: null,
                        visible: true,
                        className: "",
                        closeModal: true,
                        icon: "warning"
                    },
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                }
            }).then(confirmed => {
                if (confirmed) {
                    axios
                        .delete("/permission/permissions/" + id)
                        .then(response => {
                            this.$refs.table.getData();
                            this.$swal("Poof! Đã xóa thành công!", {
                                icon: "success"
                            });
                        })
                        .catch(error => {
                            if (error.response) {
                                this.$toasted.error('Chưa xóa được', {
			                        theme: 'toasted-primary',
			                        iconPack: 'fontawesome',
			                        icon: 'check',
			                        position: 'top-right',
			                        duration: 5000
			                    })
                            }
                        });
                } else {
                    swal("Đã hủy", "Chưa xóa được !", "error");
                }
            });
        },
        clearName() {
            this.permissions.name = "";
            this.permissions.display_name = "";
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault();
            if (!this.permissions.name) {
                	this.$toasted.error('Không được bỏ trống name', {
                        theme: 'toasted-primary',
                        iconPack: 'fontawesome',
                        icon: 'exclamation',
                        position: 'top-right',
                        duration: 5000
                    })
            } else {
                this.handleSubmit();
            }
        },
        handleSubmit() {
            axios
                .post("/permission/permissions", {
                    name: this.permissions.name,
                    display_name: this.permissions.display_name
                })
                .then(response => {
                    this.$refs.modal.hide();
                    console.log(response.data.id);
                    // console.log(this.getVueItems());
                    // this.getVueItems();
                    this.$refs.table.getData();
                    	this.$toasted.success('Thêm mới thành công', {
                            theme: 'toasted-primary',
                            iconPack: 'fontawesome',
                            icon: 'check',
                            position: 'top-right',
                            duration: 5000
                        })
                })
                .catch(error => {
                    if (error.response) {
                        if (error.response.status) {
                            // console.log("DUPLICATE NAME");
                            this.$toasted.error('Trùng tên nha', {
		                        theme: 'toasted-primary',
		                        iconPack: 'fontawesome',
		                        icon: 'exclamation',
		                        position: 'top-right',
		                        duration: 5000
		                    })
                        }
                    }
                });
        },
        handleOkEdit(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault();
            if (!this.editPermission.name) {
                	this.$toasted.error('Không được bỏ trống name', {
                        theme: 'toasted-primary',
                        iconPack: 'fontawesome',
                        icon: 'exclamation',
                        position: 'top-right',
                        duration: 5000
                    })
            } else {
                this.handleSubmitEdit();
            }
        },
        edit(id, index) {
            let app = this;

            app.permissionId = id;
            axios
                .get("/permission/permissions/" + id)
                .then(function(resp) {
                    app.editPermission = resp.data;
                })
                .catch(function() {
                    this.$toasted.error('Không load được dữ liệu nha', {
                        theme: 'toasted-primary',
                        iconPack: 'fontawesome',
                        icon: 'exclamation',
                        position: 'top-right',
                        duration: 5000
                    })
                });
        },
        handleSubmitEdit() {
            event.preventDefault();
            // console.log(this.$refs.myModal);
            console.log(this.$refs["myModal"].hide());
            this.$refs["myModal"].hide();
            var newPermission = this.editPermission;
            axios
                .patch("/permission/permissions/" + this.permissionId, newPermission)
                .then(response => {
                    this.$refs.myModal.hide();
                    this.$refs.table.getData();
                    this.$toasted.success('Sửa thành công', {
                            theme: 'toasted-primary',
                            iconPack: 'fontawesome',
                            icon: 'check',
                            position: 'top-right',
                            duration: 5000
                    })
                })
                .catch(error => {
                    if (error.response) {
                        if (error.response.status) {
                            this.$toasted.error('Chưa sửa được nha', {
		                        theme: 'toasted-primary',
		                        iconPack: 'fontawesome',
		                        icon: 'exclamation',
		                        position: 'top-right',
		                        duration: 5000
		                    })
                        }
                    }
                });
        }
    }
};
</script>
<style>
	tr td:nth-child(1) {
	width: 7%;
	vertical-align: text-top;
	}
	tr td:nth-child(2) {
	width: 20%;
	vertical-align: text-top;
	}
	tr td:nth-child(3) {
	width: 20%;
	vertical-align: text-top;
	}
	tr td:nth-child(4) {
	width: 18%;
	vertical-align: text-top;
	}
	tr td:nth-child(5) {
	width: 18%;
	vertical-align: text-top;
	}
	tr td:nth-child(6) {
	width: 17%;
	vertical-align: text-top;
	}
	#btn-edit {
	border-radius: 5px;
	color: floralwhite;
	font-size: 15px;
	}
	#btn-delete {
	border-radius: 5px;
	color: white;
	font-size: 15px;
	}

	legend {
	    font-size: 20px;
	}

	.modal-dialog {
	    margin-top: 80px;
	}
	.VueTables__search-field label{
		display: none;
	}

</style>