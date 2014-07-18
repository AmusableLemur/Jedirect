<?php

require __DIR__."/../vendor/autoload.php";

use Silex\Application as App;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

$app = new App([
    "debug" => true
]);

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), [
    "twig.path" => __DIR__."/../views",
]);
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

/**
 * Generates the form used to create the link
 * @var Silex\Application
 */
$getForm = function(App $app) {
    return $app["form.factory"]->createBuilder("form")
        ->add("url", "text", [
            "constraints" => [new Assert\NotBlank(), new Assert\Url()]
        ])
        ->add("save", "submit")
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
        "form" => $form->createView()
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
