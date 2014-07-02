<?php

namespace Model;

/**
 * @Entity
 * @Table(name="class_application")
 *
 **/

class ClassApplication extends ModelBase {

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @Column(name="name", type="string", length=20)
     **/
    public $name;

    /**
     * @Column(name="gender", type="string", length=2)
     **/
    public $gender;

    /**
     * @Column(name="birthday", type="datetime")
     **/
    public $birthday;

    /**
     * @Column(name="identity", type="string", length=30)
     **/
    public $identity;

    /**
     * @Column(name="middle_school", type="string", length=50)
     **/
    public $middle_school;

    /**
     * @Column(name="exam_id", type="string", length=20)
     **/
    public $exam_id;
    
    /**
         * @Column(name="major", type="string", length=30)
     **/
    public $major;
   
    /**
         * @Column(name="stu_id", type="string", length=20)
     **/
    public $stu_id;
    
    /**
     * @Column(name="phone", type="string", length=11)
     **/
    public $phone;

    /**
     * @Column(name="photo", type="string", length=250)
     **/
    public $photo;
    
    /**
     * @Column(name="email", type="string", length=50)
     **/
    public $email;
    
    /**
     * @Column(name="addr", type="string", length=250)
     **/
    public $addr;

    /**
     * @Column(name="total", type="integer")
     **/
    public $total;

    /**
     * @Column(name="chinese", type="integer")
     **/
    public $chinese;

    /**
     * @Column(name="math", type="integer")
     **/
    public $math;

    /**
     * @Column(name="english", type="integer")
     **/
    public $english;

    /**
     * @Column(name="science", type="integer")
     **/
    public $science;

    /**
     * @Column(name="student_source", type="string", length=250)
     **/
    public $student_source;

    /**
     * @Column(name="score_line", type="integer")
     **/
    public $score_line;

    /**
     * @Column(name="intro", type="text")
     **/
    public $intro;

    /**
     * @Column(name="reason", type="text")
     **/
    public $reason;

    /**
     * @Column(name="parent_name", type="string", length=20)
     **/
    public $parent_name;

    /**
     * @Column(name="relation", type="string", length=255)
     **/
    public $relation;
 
    /**
     * @Column(name="parent_phone", type="string", length=11)
     **/
    public $parent_phone;

    /**
     * @Column(name="parent_addr", type="string", length=250)
     **/
    public $parent_addr;

    /**
     * @Column(name="created", type="datetime")
     **/
    public $created;

    /**
     * @Column(name="cancel", type="integer")
     **/
    public $cancel;

    /**
     * @Column(name="cancel_time", type="datetime")
     **/
    public $cancel_time;

    static public function all($asc=true) {
        $dql = sprintf(
            'SELECT n FROM %s n '.
            'ORDER BY n.id %s ',
            get_called_class(),
            $asc ? 'ASC' : 'DESC'
        );
        $query = static::em()->createQuery($dql);
        return $query->useQueryCache(true)->getResult();
    }

    public function delete() {
        $this->remove();
        self::flush();
    }

}