<?php

require __DIR__."/vendor/autoload.php";

use Silex\Application as App;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints as Assert;

$app = new App([
    "debug" => true
]);

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    "db.options" => [
        "driver" => "pdo_mysql",
        "host" => "localhost",
        "dbname" => "jedirect",
        "user" => "root",
        "password" => "",
        "charset" => "utf8"
    ]
]);
$app->register(new Silex\Provider\TwigServiceProvider(), [
    "twig.path" => __DIR__."/views",
]);
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

/**
 * Generates a random string of desired length
 * @var string $length
 */
$getRandomString = function($length) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890";
    $string = "";

    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, strlen($chars) - 1);
        $string .= $chars[$index];
    }

    return $string;
};

/**
 * Renders the main page with the form
 */
$app->get("/", function(App $app) {
    return $app["twig"]->render("index.html.twig");
})->bind("home");

/**
 * Generates and stores the URL
 */
$app->post("/", function(App $app, Request $request) use ($getRandomString) {
    $url = $request->get("url");

    if (filter_var($url, FILTER_VALIDATE_URL) === false || strpos($url, "http") !== 0) {
        return $app["twig"]->render("index.html.twig", [
            "error" => "URL doesn't appear to be valid"
        ]);
    }

    $link = $getRandomString(10);
    $links = $app["db"]->query("SELECT link FROM links")->fetchAll(PDO::FETCH_COLUMN);

    while (in_array($link, $links)) {
        $link = $getRandomString(10);
    }

    $query = $app["db"]->prepare(
        "INSERT INTO links (link, url, date_created, date_expired)
        VALUES (:link, :url, :created, :expiry)"
    );

    $query->execute([
        "link" => $link,
        "url" => $request->get("url"),
        "created" => date("Y-m-d H:i"),
        "expiry" => date("Y-m-d H:i", strtotime("+24 hours"))
    ]);

    return $app["twig"]->render("index.html.twig", [
        "link" => $app["url_generator"]->generate("link", ["ident" => $link], UrlGeneratorInterface::ABSOLUTE_URL)
    ]);
});

/**
 * Redirects the user to their destination and deletes the record from the database
 * In case no URL is found the user is simply redirected to the homepage
 */
$app->get("/{ident}", function(App $app, $ident) {
    $query = $app["db"]->prepare("SELECT url FROM links WHERE link = :link AND date_expired > NOW()");
    $query->execute(["link" => $ident]);

    $url = $query->fetchColumn();

    if ($url === false) {
        return $app->redirect(
            $app["url_generator"]->generate("home")
        );
    }

    $query = $app["db"]->prepare("DELETE FROM links WHERE link = :link");
    $query->execute(["link" => $ident]);

    return $app->redirect($url);
})->assert("ident", ".*")->bind("link");

$app->run();
