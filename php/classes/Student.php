<?php
class Student{
    public $name;
    public $reg_no;
    public $stud_no;
    public $supervisor_id;
    public $college_id;
    public $course_id;

    function __construct($name, $reg_no, $stud_no, $supervisor_id, $college_id, $course_id){
        $this->name = $name;
        $this->reg_no = $reg_no;
        $this->stud_no = $stud_no;
        $this->supervisor_id = $supervisor_id;
        $this->college_id = $college_id;
        $this->course_id = $course_id;
    }
    			
    public function insertStudent($db, $excel_status = "approved"){
        $sql = 'INSERT INTO student(id, name, student_number, reg_number, academic_supervisor_id, field_supervisor_id, college_id, exel_status, course_id) 
                            VALUES(:id, :name, :student_number, :reg_number, :academic_supervisor_id, :field_supervisor_id, :college_id, :exel_status, :course_id)';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', NULL);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':student_number', $this->stud_no);
        $stmt->bindParam(':reg_number', $this->reg_no);
        $stmt->bindParam(':academic_supervisor_id', $this->supervisor_id);
        $stmt->bindParam(':college_id', $this->college_id);
        $stmt->bindValue(':field_supervisor_id', NULL);
        $stmt->bindParam(':exel_status', $excel_status);
        $stmt->bindParam(':course_id', $this->course_id);
        $result = $stmt->execute();
        return $result;
    }

    public static function getStudents($db, $college_id, $course_id){
        $sql = 'SELECT * FROM student WHERE college_id = :college_id AND course_id = :course_id AND exel_status = :exel_status';
        $stmt = $db->prepare($sql);
        $stmt->bindValue("exel_status", "approved");
        $stmt->bindParam(":college_id", $college_id);
        $stmt->bindParam(":course_id", $course_id);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
   public static function studentLogin($db, $student_number){
       $sql = 'SELECT * FROM student WHERE student_number = :student_number';
       $stmt = $db->prepare($sql);
       $stmt->bindParam(':student_number', $student_number);
       $stmt->execute();
       $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
       $rows = $stmt->rowCount();
       if($rows == 1){
           return $results;
       }else{
           return false;
       }
   }

   public static function getStudentBySupervisorId($db, $supervisor_id){
    $sql = 'SELECT * FROM student WHERE academic_supervisor_id = :academic_supervisor_id';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':academic_supervisor_id', $supervisor_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rows = $stmt->rowCount();
    return $results;
   }

   public static function getStudentsFinalMarks($db, $college_id, $course_id, $lim){
       $sql = "SELECT name, student_number, reg_number, marks FROM student WHERE college_id = :college_id AND course_id = :course_id AND exel_status = :exel_status ORDER BY id ASC LIMIT $lim, 10";
       $stmt = $db->prepare($sql);
       $stmt->bindValue("exel_status", "approved");
       $stmt->bindParam(":college_id", $college_id);
       $stmt->bindParam(":course_id", $course_id);
       $stmt->execute();
       $resultset = $stmt->fetchAll(PDO::FETCH_ASSOC);
       return $resultset;
   }

   public static function getExcelStudents($db, $college_id, $course_id, $excel_status){
        $sql = 'SELECT * FROM student WHERE college_id = :college_id AND course_id = :course_id AND exel_status = :exel_status';
        $stmt = $db->prepare($sql);
        $stmt->bindParam("college_id", $college_id);
        $stmt->bindParam("course_id", $course_id);
        $stmt->bindParam("exel_status", $excel_status);
        $stmt->execute();
        $resultset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultset;

    }

    public static function approveExcel($db, $course_id){
        $sql = 'UPDATE student SET exel_status = :exel_status WHERE exel_status = :old_exel_status AND course_id = :course_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue("exel_status", "approved");
        $stmt->bindValue("old_exel_status", "pending");
        $stmt->bindParam("course_id", $course_id);
        $result = $stmt->execute();
        return $result;
    }
    
    public static function rejectExcel($db, $course_id){
        $sql = 'DELETE FROM student WHERE exel_status = :exel_status AND course_id = :course_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue("exel_status", "pending");
        $stmt->bindParam("course_id", $course_id);
        $result = $stmt->execute();
        return $result;
    }

    public static function updateEducationMarks($db, $id, $marks){
        $sql = 'UPDATE student SET marks = :marks WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":marks", $marks);
        $stmt->bindParam(":id", $id);
        $result = $stmt->execute();
        return $result;
    }

    public static function getStudentById($db, $id){
        $sql = 'SELECT * FROM student WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $rows = $stmt->rowCount();
        if($rows > 0){
            $resultset = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultset[0];
        }else{
            return false;
        }
    }

    public static function getPages($db, $college_id, $course_id){
        $sql = 'SELECT * FROM student WHERE college_id = :college_id AND course_id = :course_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam("college_id", $college_id);
        $stmt->bindParam("course_id", $course_id);
        $stmt->execute();
        $rows = $stmt->rowCount();
        $pages = ceil($rows / 10);
        return $pages;
    }

    public static function getPaginatedStudents($db, $college_id, $course_id, $lim){
        $sql = "SELECT * FROM student WHERE college_id = :college_id AND course_id = :course_id ORDER BY id ASC LIMIT $lim, 10";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("college_id", $college_id);
        $stmt->bindParam("course_id", $course_id);
        $stmt->execute();
        $resultset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultset;
    }
    
    public static function getPaginatedStudentBySupervisorId($db, $supervisor_id, $lim){
        $sql = "SELECT * FROM student WHERE academic_supervisor_id = :academic_supervisor_id ORDER BY id ASC LIMIT $lim, 10";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':academic_supervisor_id', $supervisor_id);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rows = $stmt->rowCount();
        return $results;
    }
    public static function getStudentPagesBySupervisorId($db, $supervisor_id){
        $sql = 'SELECT * FROM student WHERE academic_supervisor_id = :academic_supervisor_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':academic_supervisor_id', $supervisor_id);
        $stmt->execute();
        $rows = $stmt->rowCount();
        $pages = ceil($rows/10);
        return $pages;
    }

    public static function getStudentByStudentNumber($db, $student_number){
        $sql = 'SELECT * FROM student WHERE student_number = :student_number';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":student_number", $student_number);
        $stmt->execute();
        $rows = $stmt->rowCount();
        if($rows > 0){
        $resultset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultset[0];
        }else{
            return false;
        }
    }

    public static function updateFieldInfo($db, $supervisor_id, $field_marks, $student_id){
        $sql = 'UPDATE student SET field_supervisor_id = :field_supervisor_id, field_sipervisor_field_marks = :field_sipervisor_field_marks WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":field_supervisor_id", $supervisor_id);
        $stmt->bindParam(":field_sipervisor_field_marks", $field_marks);
        $stmt->bindParam(":id", $student_id);
        $result = $stmt->execute();
        return $result;
    }
    
    public static function rollbackFieldInfo($db, $id){
        $sql = 'UPDATE student SET field_supervisor_id = :field_supervisor_id, field_sipervisor_field_marks = :field_sipervisor_field_marks WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":field_supervisor_id", NULL);
        $stmt->bindValue(":field_sipervisor_field_marks", NULL);
        $stmt->bindParam(":id", $id);
        $result = $stmt->execute();
    }
    public static function updateMarks($db, $marks, $id){
        $sql = 'UPDATE student SET marks = :marks WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":marks", $marks);
        $stmt->bindParam(":id", $id);
        $result = $stmt->execute();
        return $result;
    }

    public static function getStudentsByFieldSupervisor($db, $supervisor_id){
        $sql ='SELECT * FROM student WHERE field_supervisor_id = :field_supervisor_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":field_supervisor_id", $supervisor_id);
        $stmt->execute();
        $resultset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultset;
    }
    
    public static function updateAccademicMarks($db, $student_id, $field_marks, $logbook_marks, $report_marks){
        $sql = 'UPDATE student SET accademic_supervisor_field_marks = :accademic_supervisor_field_marks, report_marks = :report_marks, logbook_marks = :logbook_marks WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":accademic_supervisor_field_marks", $field_marks);
        $stmt->bindParam(":report_marks", $report_marks);
        $stmt->bindParam(":logbook_marks", $logbook_marks);
        $stmt->bindParam(":id", $student_id);
        $result = $stmt->execute();
        return $result;
    }

    public static function rollbackAccademicMarks($db, $student_id){
        $sql = 'UPDATE student SET accademic_supervisor_field_marks = :accademic_supervisor_field_marks, report_marks = :report_marks, logbook_marks = :logbook_marks WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":accademic_supervisor_field_marks", NULL);
        $stmt->bindValue(":report_marks", NULL);
        $stmt->bindValue(":logbook_marks", NULL);
        $stmt->bindParam(":id", $student_id);
        $result = $stmt->execute();
    }


}