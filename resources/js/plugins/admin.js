import AdminGuestGuard from "../shared/guards/guest-admin-guard";
import AdminLogin from "../components/dashboard/login.vue";
import DashboardLayout from '../layouts/dashboard-layout';
import ItemTable from '../components/dashboard/item/item-table';
import Test from '../components/dashboard/test/index';
import TaxTable from '../components/dashboard/tax/item-table';
import TaxForm from '../components/dashboard/tax/item-form';
import TagTable from '../components/dashboard/tag/item-table';
import TagForm from '../components/dashboard/tag/item-form';

import UnitTable from '../components/dashboard/unit/item-table';
import Pos from '../components/dashboard/Pos/index';
import UnitForm from '../components/dashboard/unit/item-form';

import BrandTable from '../components/dashboard/brand/item-table';
import BrandForm from '../components/dashboard/brand/item-form';
import CategoryForm from '../components/dashboard/category/item-form';
import LocationForm from '../components/dashboard/location/item-form';
import VariationTable from '../components/dashboard/variation/item-table';
import CategoryTable from '../components/dashboard/category/item-table';
import ProductTable from '../components/dashboard/product/item-table';
import OrderTable from '../components/dashboard/order/index';
import ProductForm from '../components/dashboard/product/item-form';
import LocationTable from '../components/dashboard/location/item-table';
import VariationForm from '../components/dashboard/variation/item-form';

import VariationValueTable from '../components/dashboard/variation-value/item-table';
import VariationValueForm from '../components/dashboard/variation-value/item-form';


import ManyImageTable from '../components/dashboard/many-image/item-table';
import addStock from '../components/dashboard/add-stock/index';
import MediaManager from '../components/dashboard/media-manager/item-list';
import AuthenticatedAdminGuard from "../shared/guards/authenticated-admin-guard";

export default [{
    path: "/admin",
    component: DashboardLayout,
    beforeEnter: [
        AuthenticatedAdminGuard
    ],
    children: [
        { path: "test", component: Test },
        { path: "items", component: ItemTable },
        { path: "pos", component: Pos, name: "pos-system" },
        { path: "taxes", component: TaxTable },
        { path: "taxes-form/:id", component: TaxForm },
        { path: "variations", component: VariationTable },
        { path: "variations-form/:id", component: VariationForm },
        { path: "categories", component: CategoryTable },
        { path: "brands", component: BrandTable },

        { path: "products", component: ProductTable },
        { path: "orders", component: OrderTable },
        { path: "product-form/:id", component: ProductForm },
        { path: "locations", component: LocationTable },
        { path: "categories-form/:id", component: CategoryForm },
        { path: "locations-form/:id", component: LocationForm },
         { path: "brands-form/:id", component: BrandForm },
        
        { path: "tags", component: TagTable },
        { path: "tags-form/:id", component: TagForm },
        { path: "variation-values/:id/:name_ar/:name_en", component: VariationValueTable },
        { path: "variation-values-form/:id", component: VariationValueForm },

        { path: "units", component: UnitTable },
        { path: "units-form/:id", component: UnitForm },
        { path: "media-manager", component: MediaManager },
        { path: "many-image", component: ManyImageTable },
        { path: "add-stock", component: addStock },
    ]
},
{
    path: "/admin",
    beforeEnter: [AdminGuestGuard],
    children: [
        { path: "login", component: AdminLogin },
    ]
},
];