<?php

view()->composer(['apps::dashboard.index'], \Modules\Report\ViewComposers\Dashboard\UserComposer::class);
view()->composer(['apps::dashboard.index'], \Modules\Report\ViewComposers\Dashboard\OrderComposer::class);

