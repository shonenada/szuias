<?php
return array(
    "export" => function($app) {
        $app->get("/", function() use($app) {
            $app->render("index.html", get_defined_vars());
        });
    }
);
