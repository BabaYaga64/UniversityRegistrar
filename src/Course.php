<?php
Class Course
{
    private $name;
    private $course_number;
    private $id;
    function __construct($initial_name, $initial_id = null, $initial_number)
    {
        $this->name          = $initial_name;
        $this->id            = $initial_id;
        $this->course_number = $initial_number;
    }
    function getName()
    {
        return $this->name;
    }
    function setName($new_name)
    {
        $this->name = (string) $new_name;
    }
    function getId()
    {
        return $this->id;
    }
    function setId($new_id)
    {
        $this->id = (int) $new_id;
    }
    function getNumber()
    {
        return $this->course_number;
    }
    function setNumber($new_number)
    {
        $this->course_number = (int) $new_number;
    }
    function save()
    {
        $statement = $GLOBALS['DB']->query("INSERT INTO courses (name, course_number) VALUES ('{$this->getName()}', {$this->getNumber()}) RETURNING id;");
        $result    = $statement->fetch(PDO::FETCH_ASSOC);
        $this->setId($result['id']);
    }
    static function getAll()
    {
        $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
        $courses          = array();
        foreach ($returned_courses as $course) {
            $name          = $course['name'];
            $id            = $course['id'];
            $course_number = $course['course_number'];
            $new_course    = new Course($name, $id, $course_number);
            array_push($courses, $new_course);
        }
        return $courses;
    }
    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM courses *;");
    }
    static function find($search_id)
    {
        $found_course     = null;
        $returned_courses = Course::getAll();
        foreach ($returned_courses as $course) {
            $course_id = $course->getId();
            if ($course_id == $search_id) {
                $found_course = $course;
            }
        }
        return $found_course;
    }
    function update($new_course)
    {
        $GLOBALS['DB']->exec("UPDATE courses SET name= '{$new_course}' WHERE id = {$this->getId()};");
        $this->setName($new_course);
    }
    //save the id of the current student with the id of the input $new_student into a row in the join table called students_courses.
    function addStudent($new_student)
    {
        $GLOBALS['DB']->exec("INSERT INTO students_courses (course_id , student_id) VALUES ({$this->getId()},{$new_student->getId()});");
    }
    //get all student ids
    //from the join table where student ids are stored with course ids
    //return the student ids which correspond to course ids equal to the current course id.
    function getStudents()
    {
        $query         = $GLOBALS['DB']->query("SELECT students.* FROM
            courses JOIN students_courses ON (courses.id = students_courses.course_id)
            JOIN students ON (students_courses.student_id = students.id) WHERE courses.id = {$this->getId()};");




        // $student_id    = $query->fetchAll(PDO::FETCH_ASSOC);
        $student_array = array();
        foreach ($query as $student) {
            //pull out its value with the key 'student_id' and store it in variable $student_id
            // $student_id        = $id['student_id'];
            //get all students matching the current student id out of the students table (including their name, id, course_number ).
            // $student           = $GLOBALS['DB']->query("SELECT * FROM students WHERE id= {$student_id};");
            // $returned_students = $student->fetchAll(PDO::FETCH_ASSOC);


            $name              = $student['name'];
            $id                = $student['id'];
            $date    = $student['enroll_date'];
            $new_student       = new Student($name, $id, $date);
            array_push($student_array, $new_student);


        }
        return $student_array;
    }
    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM courses WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM students_courses WHERE course_id = {$this->getId()};");
    }
}
?>
