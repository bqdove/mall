import Layout from '../layouts/Layout.vue';
import Dashboard from '../pages/Dashboard.vue';
import Home from '../pages/Home.vue';
import Goods from '../pages/Goods.vue';
import GoodsAdd from '../pages/GoodsAdd.vue';
import GoodsEdit from '../pages/GoodsEdit.vue';
import GoodsEditCategory from '../pages/GoodsEditCategory.vue';
import GoodsNotice from '../pages/GoodsNotice.vue';

export default function (injection) {
    injection.useModuleRoute([
        {
            children: [
                {
                    beforeEnter: injection.middleware.requireAuth,
                    component: Dashboard,
                    path: '/',
                },
                {
                    beforeEnter: injection.middleware.requireAuth,
                    component: Home,
                    path: 'home',
                },
                {
                    beforeEnter: injection.middleware.requireAuth,
                    component: Goods,
                    path: 'goods',
                },
                {
                    beforeEnter: injection.middleware.requireAuth,
                    component: GoodsAdd,
                    path: 'goods/add',
                },
                {
                    beforeEnter: injection.middleware.requireAuth,
                    component: GoodsEdit,
                    path: 'goods/edit',
                },
                {
                    beforeEnter: injection.middleware.requireAuth,
                    component: GoodsEditCategory,
                    path: 'goods/edit/category',
                },
                {
                    beforeEnter: injection.middleware.requireAuth,
                    component: GoodsNotice,
                    path: 'goods/notice',
                },
            ],
            component: Layout,
            path: '/seller',
        },
    ]);
}