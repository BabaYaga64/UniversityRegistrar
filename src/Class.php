<?php
    class Class
    {
        private $name;
        private $number;
        private $id;
        function __construct($initial_name, $initial_id = null,$initial_number)
        {
            $this->name = $initial_name;
            $this->id = $initial_id;
            $this->number = $initial_number;
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
            return $this->number;
        }
        function setNumber($new_number)
        {
            $this->number = (int) $new_number);
        }
        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO classes (name,number) VALUES ('{$this->getName()}', {$this->getNumber()}) RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }
        static function getAll()
        {
            $returned_classes = $GLOBALS['DB']->query("SELECT * FROM classes;");
            $classes = array();
            foreach($returned_classes as $class) {
                $name = $class['name'];
                $id = $class['id'];
                $number = $class['number'];
                $new_class = new Class($name, $id, $number);
                array_push($classes, $new_class);
            }
            return $classes;
        }
        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM classes *;");
        }

        function find($search_id){
            $found_class= null;
            $returned_classes= Class::getAll();
            foreach($returned_classes as $class){
                $class_id= $class->getId();
                if($class_id ==$search_id ){

                    $found_class=$class;

                }
            }

        return $found_class;

        }

        function update($new_class)
        {

            $GLOBALS['DB']->exec("UPDATE classes SET name= '{$new_class}' WHERE id = {$this->getId()};");
            $this->setName($new_class);

        }
        //save the id of the current student with the id of the input $new_student into a row in the join table called students_classes.
        function addStudent($new_student){
            $GLOBALS['DB']->exec("INSERT INTO students_classes (class_id , student_id) VALUES ({$this->getId()},{$new_student->getId()});" );


        }

            //get all student ids
           //from the join table where student ids are stored with class ids
           //return the student ids which correspond to class ids equal to the current class id.
        function getStudents(){
            $query = $GLOBALS['DB']->exec("SELECT student_id FROM students_classes WHERE class_id= {$this->getId()};");
            $student_id= $query->fetchAll(PDO::FETCH_ASSOC);
            $student_array= array();

            foreach($student_id as $id){

                //pull out its value with the key 'student_id' and store it in variable $student_id
                $student_id = $id['student_id'];

                //get all students matching the current student id out of the students table (including their name, id, number ).
                $student= $GLOBALS['DB']->exec("SELECT * FROM students WHERE id= {$student_id};");
                $returned_students= $student->fetchAll(PDO::FETCH_ASSOC);
                $name=$returned_students[0]['name'];
                $number=$returned_students[0]['number'];
                $id=$returned_students[0]['id'];

                $new_student = new Student($name, $id, $number);
                array_push($student_array, $new_student);
            }

            return $student_array;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM classes WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM students_classes WHERE category_id = {$this->getId()};");
        }
}


?>
