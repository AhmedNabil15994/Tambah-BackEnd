<?php

// Dashboard View Composer
view()->composer([
    'catalog::dashboard.products.*',
], \Modules\Tags\ViewComposers\Dashboard\TagComposer::class);
