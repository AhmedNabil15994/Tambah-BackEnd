<?php
view()->composer([
    "catalog::dashboard.products.create",
    "catalog::dashboard.products.edit",
], \Modules\Attribute\ViewComposers\Dashboard\AttributeComposer::class);
