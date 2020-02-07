<template>
    <div class="card">
        <div class="card-body">
            <p v-show="isError">情報の取得に失敗しました。</p>

            <div>
                <p><router-link to="/">ホーム</router-link></p>
                <div class="col-sm-6">
                    <ul class="pagination">
                        <li :class="{disabled: current_page <= 1}"><a href="javascript:void(0)" @click="change(1)">&laquo;</a></li>
                        <li :class="{disabled: current_page <= 1}"><a href="javascript:void(0)"
                                                                      @click="change(current_page - 1)">&lt;</a>
                        </li>
                        <li v-for="page in pages" :key="page" :class="{active: page === current_page}">
                            <a href="javascript:void(0)" @click="change(page)">{{page}}</a>
                        </li>
                        <li :class="{disabled: current_page >= last_page}"><a href="javascript:void(0)"
                                                                              @click="change(current_page + 1)">&gt;</a>
                        </li>
                        <li :class="{disabled: current_page >= last_page}"><a href="javascript:void(0)"
                                                                              @click="change(last_page)">&raquo;</a>
                        </li>
                    </ul>
                </div>
                <div style="margin-top: 40px" class="col-sm-6 text-right">全 {{total}} 件中 {{from}} 〜 {{to}} 件表示</div>

                <table class="table table-bordered table-hover table-striped table-condensed">
                    <tbody>
                    <tr>
                        <th>商品ID</th>
                        <th>登録日時</th>
                        <th>商品名</th>
                        <th>商品コード</th>
                        <th>価格</th>
                    </tr>

                    <tr v-for="( item, key, index ) in items" :key="key">
                        <td>{{item.id}}</td>
                        <td>{{item.created_at}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.product_code}}</td>
                        <td>{{item.price}}</td>
                    </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Product",
        data: function () {
            return {
                items: null,
                deleted: false,
                current_page: 1,
                last_page: 1,
                total: 1,
                from: 0,
                to: 0,
            }
        },
        mounted: function () {
            this.getItems(1);
        },
        methods: {
            getItems: function (page) {
                axios.get('/api/product?page=' + page)
                    .then((res) => {
                        this.items = res.data.data;
                        this.current_page = res.data.current_page
                        this.last_page = res.data.last_page
                        this.total = res.data.total
                        this.from = res.data.from
                        this.to = res.data.to,
                            console.log("データを取得した。" + this.to);
                    });
            },

            change: function (page) {
                if (page >= 1 && page <= this.last_page) {
                    this.getItems(page);
                }
                console.log("チェンジデータを取得した。" + page);
            },
        },
        computed: {
            pages: function () {
                let start = _.max([this.current_page - 2, 1])
                let end = _.min([start + 5, this.last_page + 1])
                start = _.max([end - 5, 1])
                return _.range(start, end)
            },
        }
    }
</script>

<style scoped>

</style>
