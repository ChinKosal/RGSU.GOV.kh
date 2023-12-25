@component('admin::components.dialog', ['dialog' => 'addMapDialog'])
    <div x-data="addMapDialog" class="dialog-form" style="width: 650px">
        <div class="dialog-form-header">
            <h3 x-text="data?.title"></h3>
        </div>
        <div class="dialog-form-body">
            <div class="form-row">
                <textarea x-model="embedLink" placeholder="Enter embed link" rows="7"></textarea>
                <span class="error" x-show="error.status" x-text="error.message"></span>
            </div>
        </div>
        <div class="dialog-form-footer">
            <button type="button" class="close" @click="$store.addMapDialog.close()">Close</button>
            <button type="button" @click="onConfirm" x-bind:disabled="disabled || loading">
                <span>Add</span>
                <div class="loader" style="display: none" x-show="loading"></div>
            </button>
        </div>
    </div>
    <style>
        textarea {
            width: 100%;
            border: 1px solid #d8dce5;
            border-radius: 10px;
            padding: 10px;
        }
    </style>
    <script>
        Alpine.data("addMapDialog", () => ({
            embedLink: null,
            disabled: false,
            loading: false,
            error: {
                status: false,
                message: "Please enter google embed map"
            },
            init() {
                this.data = this.$store.addMapDialog.data;
                feather.replace();
            },
            onConfirm() {
                var filtered = /(<iframe.*?>.*?<\/iframe>)/g.test(this.embedLink);
                if (filtered) {
                    this.error.status = false;
                    this.$store.addMapDialog.close({
                        message: true,
                        embedLink: $(this.embedLink).attr('src')
                    });
                } else {
                    this.error.status = true;
                }
            }
        }))
    </script>
@endcomponent
