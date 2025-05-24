<?php

view()->composer(['setting::dashboard.index'], \Modules\Page\ViewComposers\Dashboard\PageComposer::class);


//view()->composer(['apps::frontend.layouts.app'], \Modules\Page\ViewComposers\FrontEnd\PageComposer::class);

view()->composer(['apps::frontend.layouts.master'], \Modules\Page\ViewComposers\FrontEnd\PageComposer::class);
