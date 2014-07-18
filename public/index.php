<?php

require __DIR__."/../vendor/autoload.php";

use Silex\Application as App;
use Symfony\Component\HttpFoundation\Request;

$app = new App([
    "debug" => true
]);

$app->register(new Silex\Provider\FormServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), [
    "twig.path" => __DIR__."/../views",
]);

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$getForm = function(App $app) {
    $data = [
        "url" => "Destination URL"
    ];

    return $app["form.factory"]->createBuilder("form", $data)
        ->add("url")
        ->getForm();
};

/**
 * Renders the main page with the form
 */
$app->get("/", function(App $app) use ($getForm) {
    return $app["twig"]->render("index.html.twig", [
        "form" => $getForm($app)->createView()
    ]);
})->bind("home");

/**
 * Generates and stores the URL
 */
$app->post("/", function(App $app, Request $request) use ($getForm) {
    $form = $getForm($app);

    $form->handleRequest($request);

    if ($form->isValid()) {
        // Generate URL
    }

    return $app["twig"]->render("index.html.twig", [
        "form" => $getForm($app)->createView(),
        "error" => "The URL you provided is not valid"
    ]);
});

/**
 * Redirects the user to their destination and deletes the record from the database
 * In case no URL is found the user is simply redirected to the homepage
 */
$app->get("/{ident}", function(App $app, $ident) {
    return $app->redirect(
        $app["url_generator"]->generate("home")
    );
});

$app->run();
