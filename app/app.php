<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Course.php";

    $app = new Silex\Application();
    $app['debug'] = true;
    $DB = new PDO('pgsql:host=localhost;dbname=registrar');

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.twig');

    });

///COURSES ROUTES

    //Route goes from main page to /courses route to display all courses.
    $app->get("/courses", function() use ($app) {
        return $app['twig']->render('courses.twig', array('courses' => Course::getAll()));

    });

    $app->post("/courses", function() use ($app) {
        $name = $_POST['name'];
        $number = $_POST['number'];

        $new_course = new Course($name, $id = null, $number);
        $new_course->save();
        return $app['twig']->render('courses.twig', array('courses' => Course::getAll()));

    });
    //Goes to a spisific course page from courses.twig
    $app->get("/course/{id}", function($id) use ($app) {
        $course= Course::find($id);
        return $app['twig']->render('current_course.twig', array("course"=>$course, "students"=> Student::getAll()));

    });


return $app;

?>
